<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use App\Store;
use App\Vendor;
use App\VendorLocation;
use App\Region;
use App\PassType;
use App\PassPurchase;
use App\PassUsage;

class VendorController extends Controller
{
    public function view()
    {
        $vendors = Vendor::orderBy('name')->get();

        return response()->view('store.vendors.vendor_list', compact('vendors'));
    }

    public function create(Request $request)
    {
        $vendorInput = $request->only('region_id', 'pass_type', 'name', 'email', 'redeem_txt', 'offer_desc', 'pass_code', 'max_pass_use');
        $locationInput = $request->only('location_name', 'address1', 'address2', 'city', 'state', 'zipcode');
        $input = $request->all();
        if ($vendorInput['pass_type'] == 'bonus') {
            $vendorInput['pass_type'] = 'experience';
            $passTypeName = 'Bonus';
        }
        else if ($vendorInput['pass_type'] == 'mini') {
            $vendorInput['pass_type'] = 'experience';
            $passTypeName = 'Mini';
        }
        if ($vendorInput['pass_type'] == PassType::EXPERIENCE_PASS_TYPE) 
        {
            unset($input['max_pass_use']);
            unset($vendorInput['max_pass_use']);
        }
        $validator = \Validator::make($input, [
            'name'              => 'required',
            'email'             => 'required|email',
            'redeem_txt'        => 'required',
            'offer_desc'        => 'required',
            'pass_code'         => 'required',
            'location_name'     => 'required',
            'address1'          => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'zipcode'           => 'required',
            'max_pass_use'      => 'sometimes|integer'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        if (isset($passTypeName))
            $passType = PassType::where('region_id', $vendorInput['region_id'])->where('type', $vendorInput['pass_type'])->where('name', $passTypeName)->first();
        else
            $passType = PassType::where('region_id', $vendorInput['region_id'])->where('type', $vendorInput['pass_type'])->first();

        if (is_null($passType))
            return response()->json(['errors' => ['Region does not have that type of pass available']], 400);

        unset($vendorInput['region_id']);
        unset($vendorInput['pass_type']);
        $vendorInput['pass_type_id'] = $passType->id;

        $vendor = Vendor::create($vendorInput);
        $this->createLocation($vendor->id, $locationInput);

        if ($vendor->passType->type == PassType::EXPERIENCE_PASS_TYPE)
            $this->unsetRedeemedPassesForRegionPassType($vendor->pass_type_id);

        return response()->json(['result' => 'Venue created.']);
    }

    public function update(Request $request)
    {
        $vendorId = $request->get('vendor_id');
        $input = $request->all();
        $validator = \Validator::make($input, [
            'vendor_id'         => 'required',
            'name'              => 'required',
            'email'             => 'required|email',
            'redeem_txt'        => 'required',
            'offer_desc'        => 'required',
            'pass_code'         => 'required',
            'max_pass_use'      => 'integer'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        $vendor = Vendor::find($vendorId);
        if (is_null($vendorId))
            return response()->json(['errors' => ['Venue not found']], 400);

        unset($input['vendor_id']);

        $vendor->update($input);

        return response()->json(['result' => 'Venue updated.']);
    }

    public function withdraw(Request $request)
    {
        $vendorId = $request->get('vendor_id');
        $vendor = Vendor::find($vendorId);
        if (is_null($vendor))
            return response()->json(['errors' => ['Venue not found']], 400);

        $vendor->is_withdrawn = true;
        $vendor->withdrawal_dt = Carbon::now();
        $vendor->save();

        return response()->json(['result' => 'Venue withdrawn.']);
    }

    public function enroll(Request $request)
    {
        $vendorId = $request->get('vendor_id');
        $vendor = Vendor::find($vendorId);
        if (is_null($vendor))
            return response()->json(['errors' => ['Venue not found']], 400);

        $vendor->is_withdrawn = false;
        $vendor->withdrawal_dt = null;
        $vendor->save();

        return response()->json(['result' => 'Venue re-enrolled.']);
    }

    public function getVendorsAsync(Request $request)
    {
        $regionId = $request->get('region_id');
        $type = $request->get('pass_type');
        $vendors = Vendor::whereHas('passType', function($q) use($type, $regionId) {
                $q->where('type', $type)->where('region_id', $regionId);
        })->orderBy('vendors.name')->pluck('vendors.name', 'vendors.id');
        if ($type == 'bonus' || $type == 'mini') {
            $typeName = ($type == 'bonus') ? 'Bonus' : 'Mini';
            $type = 'experience';
            $vendors = Vendor::whereHas('passType', function($q) use($type, $typeName, $regionId) {
                $q->where('type', $type)->where('region_id', $regionId)->where('name', $typeName);
            })->orderBy('vendors.name')->pluck('vendors.name', 'vendors.id');
        }

        $vendorJson = [];
        $vendorJson[] = ['vendorId' => '', 'vendorName' => 'Select Venue'];
        foreach ($vendors as $key => $value)
            $vendorJson[] = ['vendorId' => $key, 'vendorName' => $value];

        return response()->json(['vendors' => $vendorJson]);
    }

    public function getVendorProperties(Request $request)
    {
        $vendorId = $request->get('vendor_id');
        $vendor = Vendor::find($vendorId);
        if (is_null($vendor))
            return response()->json(['errors' => ['Vendor not found']], 400); 

        $locations = [];
        $selectLocations = [];
        $selectLocations[] = ['id' => '', 'name' => 'Select Location'];
        foreach ($vendor->locations as $location) {
            $vendorLocation = [
                'id' => $location->id, 
                'name' => $location->name,
                'address1' => $location->address1,
                'address2' => $location->address2,
                'city' => $location->city,
                'state' => $location->state,
                'zipcode' => $location->zipcode
            ];
            $locations[] = $vendorLocation;
            $selectLocations[] = $vendorLocation;
        }

        $vendor = [
            'pass_type_id'   => $vendor->pass_type_id,
            'name'           => $vendor->name,
            'email'          => $vendor->email,
            'redeem_txt'     => $vendor->redeem_txt,
            'offer_desc'     => $vendor->offer_desc,
            'pass_code'      => $vendor->pass_code,
            'is_withdrawn'   => $vendor->is_withdrawn,
            'withdrawal_dt'  => $vendor->withdrawal_dt,
            'max_pass_use'   => $vendor->max_pass_use,
            'total_pass_use' => $vendor->passType->usage_limit
        ];

        return response()->json(['vendor' => $vendor, 'locations' => $locations, 'selectLocations' => $selectLocations]);
    }

    public function addLocation(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'vendor_id'         => 'required',
            'location_name'     => 'required',
            'address1'          => 'required',
            'city'              => 'required',
            'state'             => 'required',
            'zipcode'           => 'required'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        $vendor = Vendor::find($input['vendor_id']);
        if (is_null($vendor))
            return response()->json(['errors' => ['Venue for location not found']], 400);

        $this->createLocation($vendor->id, $input);

        $selectLocations[] = ['id' => '', 'name' => 'Select Location'];
        foreach($vendor->locations as $location)
            $selectLocations[] = ['id' => $location->id, 'name' => $location->name];

        return response()->json(['result' => 'Venue Location Added', 'selectLocations' => $selectLocations]);
    }

    public function updateLocation(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'vendor_location_id'    => 'required',
            'location_name'         => 'required',
            'address1'              => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            'zipcode'               => 'required'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        $vendorLocationId = $input['vendor_location_id'];
        $vendorLocation = VendorLocation::find($vendorLocationId);
        if (is_null($vendorLocation))
            return response()->json(['errors' => ['Venue location not found']], 400);

        $vendorLocation->update($input);

        return response()->json(['result' => 'Venue Location Updated']);
    }

    public function deleteLocation(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'vendor_location_id'    => 'required'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        $vendorLocationId = $input['vendor_location_id'];
        $vendorLocation = VendorLocation::find($vendorLocationId);
        $vendorLocationCount = $vendorLocation->vendor->locations->count();
        if (is_null($vendorLocation))
            return response()->json(['errors' => ['Venue location not found']], 400);

        $passUsesAtLocation = PassUsage::where('vendor_location_id', $vendorLocationId)->get();
        if ($passUsesAtLocation->count() > 0)
            return response()->json(['errors' => ['This venue location has pass usage data assocaited with it and cannot be deleted']], 400);

        if ($vendorLocationCount === 1)
            return response()->json(['errors' => ['A venue must have at least one location']], 400);

        $vendorLocation->delete($input);

        return response()->json(['result' => 'Venue Location Deleted']);
    }

    public function getVendorLocationProperties(Request $request)
    {
        $vendorLocationId = $request->get('location_id');
        $vendorLocation = VendorLocation::find($vendorLocationId);
        if (is_null($vendorLocation))
            return response()->json(['errors' => ['Venue location not found']], 400);

        $location = [
            'id' => $vendorLocation->id, 
            'name' => $vendorLocation->name,
            'address1' => $vendorLocation->address1,
            'address2' => $vendorLocation->address2,
            'city' => $vendorLocation->city,
            'state' => $vendorLocation->state,
            'zipcode' => $vendorLocation->zipcode
        ];

        return response()->json(['location' => $location]);
    }

    function createLocation($vendorId, $input)
    {
        //'vendor_id', 'name', 'address1', 'address2', 'city', 'state', 'zipcode', 'country'
        $vendorLocation = VendorLocation::create([
            'vendor_id' => $vendorId,
            'name'      => $input['location_name'],
            'address1'  => $input['address1'],
            'address2'  => $input['address2'],
            'city'      => $input['city'],
            'state'     => $input['state'],
            'zipcode'   => $input['zipcode']
        ]);

        return $vendorLocation;
    }

    function unsetRedeemedPassesForRegionPassType($passTypeId)
    {
        $redeemedPasses = PassPurchase::where('pass_type_id', $passTypeId)->where('is_redeemed', true)->get();
        foreach ($redeemedPasses as $pass)
        {
            $pass->is_redeemed = false;
            $pass->save();
        }
    }
}
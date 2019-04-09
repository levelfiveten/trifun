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

class PassTypeController extends Controller
{
    public function view()
    {
        $regions = Region::orderBy('name')->get();
        // $passTypeTxt = "";
        // foreach($regions as $region)
        // {
        //     $passTypeTxt .= "<h3>$region->name</h3>";
        //     $passTypeTxt .= "<table>";
        //     $passTypeTxt .= "<thead><th></th></thead><tbody>";
        //     foreach($region->passTypes as $passType)
        //     {
        //         $validPeriod = ($passType->days_valid == 365) ? '1 year' : $passType->days_valid . ' days';
        //         $passTypeTxt .= "<td>$passType->name - validity: $validPeriod </p>";
        //     }
        //     $passTypeTxt .= "</tbody></table>";
        // }

        // return response($passTypeTxt);

        return response()->view('store.pass_type_list', compact('regions'));
    }

    public function create(Request $request)
    {
        //use_per_vendor:
            //'experience' type -> 1
            //'dining' type -> null
        //usage_limit:
            //'experience' type -> null
            //'dining' type -> 10, per doc specifications but may want to allow change to this input
        $input = $request->all();
        $input['type'] = ($input['type'] == 'bonus' || $input['type'] == 'mini') ? 'experience' : $input['type'];
        $validator = \Validator::make($input, [
            'name'              => 'required|in:Experience,Dining,Specials,Mini,Bonus',
            'region_id'         => 'required|integer',
                // Rule::unique('pass_types')->where(function ($query) {
                //     return $query->whereIn('type', ['experience','dining','special']);
                // }),
            'type'              => 'required|in:experience,dining,special',
            'days_valid'        => 'required|integer|min:0|max:365',
            'use_per_vendor'    => 'sometimes|integer|min:0',
            'usage_limit'       => 'sometimes|integer|min:1|max:10',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        try {
            PassType::create($input);
        }
        catch (\Exception $e) {
            \Log::error($e);
            if (env('APP_ENV') == 'production')
                \Log::channel('slack')->error('Error creating pass type: ' . $e->getMessage());

            return response()->json(['errors' => [$e->getMessage()]], 500);
        }

        return response()->json(['result' => 'New region pass created.']);
    }

    public function getTotalPassUses(Request $request)
    {
        $regionId = $request->get('region_id');
        $type = $request->get('pass_type');
        if ($type == 'bonus') {
            $type = 'experience';
            $name = 'Bonus';
        }
        else if ($type == 'mini') {
            $type = 'experience';
            $name = 'Mini';
        }
        if (isset($name))
            $passType = PassType::where('region_id', $regionId)->where('type', $type)->where('name', $name)->first();
        else
            $passType = PassType::where('region_id', $regionId)->where('type', $type)->first();

        if (is_null($passType))
            return response()->json(['errors' => ['Unable to find that pass type for the region specified']], 400);
        
        $totalPassUses = $passType->usage_limit;

        return response()->json(['totalPassUses' => $totalPassUses]);
    }

    public function update()
    {

    }

    public function delete()
    {
        
    }

    public function getCurrentPassTypes(Request $request)
    {
        
    }
}
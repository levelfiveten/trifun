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

class RegionController extends Controller
{
    public function getRegionsAsync()
    {
        $regions = Region::select('name', 'id')->get();
        // $regionJson = [];
        // foreach ($regions as $key => $value)
        //     $regionJson[] = {'regionId' => $key, 'regionName' => $value};

        return response()->json($regions);
    }

    public function view()
    {
        $regions = Region::all();
        $regionTxt = "";
        foreach($regions as $region)
            $regionTxt .= "<h3 style=\"text-align:center\">$region->name <small>($region->code)</small></h3>";

        return response($regionTxt);
    }

    public function create(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|max:255|unique:regions',
            'code'  => 'required|max:255|unique:regions',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        Region::create([
            'name' => $request->get('name'),
            'code' => $request->get('code')
        ]);

        $regions = Region::pluck('name', 'id');
        $regionJson = [];
        $regionJson[] = ['regionId' => '', 'regionName' => 'Select Region'];
        foreach ($regions as $key => $value)
            $regionJson[] = ['regionId' => $key, 'regionName' => $value];

        // $regionJson = json_encode($regions);

        return response()->json(['result' => 'Region created.', 'regions' => $regionJson]);
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
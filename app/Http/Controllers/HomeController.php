<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PassHelper;
use App\PassPurchase;
use App\PassType;
use App\Vendor;
use App\VendorLocation;
use App\PassUsage;
use App\Region;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyCustomerPassUsed;
use App\Mail\NotifyVendorPassUsed;
use Carbon\Carbon;
use Hashids\Hashids;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $newpass = bcrypt('3yglpwONdoYdECUjwRK3');
        // dd($newpass);

        $user = auth()->user();
        $diningPasses = PassPurchase::whereHas('passType', function ($q)
        { 
            $q->where('type', PassType::DINING_PASS_TYPE);
        })
        ->where('user_id', $user->id)
        ->where('is_redeemed', false)->get();

        $expPasses = PassPurchase::whereHas('passType', function ($q)
        { 
            $q->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Experience');
        })
        ->where('user_id', $user->id)
        ->where('is_redeemed', false)->get();

        $bonusPasses = PassPurchase::whereHas('passType', function ($q)
        { 
            $q->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Bonus');
        })
        ->where('user_id', $user->id)
        ->where('is_redeemed', false)->get();

        $miniPasses = PassPurchase::whereHas('passType', function ($q)
        { 
            $q->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Mini');
        })
        ->where('user_id', $user->id)
        ->where('is_redeemed', false)->get();
            
        $diningRegions = collect();
        foreach($diningPasses as $diningPass)
            $diningRegions->push($diningPass->passType->region);

        $expRegions = collect();
        foreach($expPasses as $expPass)
            $expRegions->push($expPass->passType->region);

        $bonusRegions = collect();
        foreach($bonusPasses as $bonusPass)
            $bonusRegions->push($bonusPass->passType->region);

        $miniRegions = collect();
        foreach($miniPasses as $miniPass)
            $miniRegions->push($miniPass->passType->region);

        if ($diningRegions->count() > 0)
            $diningRegions = $diningRegions->unique('id');

        if ($expRegions->count() > 0)
            $expRegions = $expRegions->unique('id');

        if ($bonusRegions->count() > 0)
            $bonusRegions = $bonusRegions->unique('id');

        if ($miniRegions->count() > 0)
            $miniRegions = $miniRegions->unique('id');

        // \DB::enableQueryLog();
        $allPassUses = \DB::table('pass_usages')
            ->leftJoin('pass_purchases', 'pass_usages.pass_purchase_id', '=', 'pass_purchases.id')
            ->leftJoin('vendors', 'pass_usages.vendor_id', '=', 'vendors.id')
            ->leftJoin('vendor_locations', 'pass_usages.vendor_location_id', '=', 'vendor_locations.id')
            ->leftJoin('pass_types', 'pass_purchases.pass_type_id', '=', 'pass_types.id')
            ->where('user_id', $user->id)
            ->where('pass_usages.redeemed_at', '!=', null)
            ->orderBy('pass_usages.redeemed_at', 'DESC')
            ->select(\DB::raw('DISTINCT pass_usages.conf_code, vendors.name AS vendor_name, vendor_locations.name AS vendor_location_name, pass_usages.redeemed_at AS pass_used_dt, pass_types.name AS pass_type_name'))
            ->get();
        // dd(\DB::getQueryLog());
        // dd($allPassUses);
        return view('home', compact('diningPasses', 'expPasses', 'bonusPasses', 'miniPasses', 'diningRegions', 'expRegions', 'bonusRegions', 'miniRegions', 'allPassUses'));
    }

    public function dining($regionId)
    {
        $user = auth()->user();
        $title = 'Dining';
        $passType = PassType::where('type', PassType::DINING_PASS_TYPE)->where('region_id', $regionId)->first();
        $passTypeId = $passType->id;
        $passes = $user->passes()->where('pass_type_id', $passTypeId)->where('is_redeemed', false)->orderBy('created_at')->get();
        $redeemRoute = route('pass.redeem', ['passTypeId' => $passTypeId]);
        $vendors = Vendor::where('pass_type_id', $passTypeId)->where(function ($q) {
            $q->where('is_withdrawn', false)->orWhere('withdrawal_dt', '>', Carbon::now());
        })->orderBy('name')->get();
        $region = Region::find($regionId);

        return view('passes.redeem', compact('title', 'passType', 'passTypeId', 'redeemRoute', 'vendors', 'passes', 'region'));
    }

    public function experience($regionId)
    {
        $user = auth()->user();
        $title = 'Experience';
        $passType = PassType::where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Experience')->where('region_id', $regionId)->first();
        $passTypeId = $passType->id;
        $passes = $user->passes()->where('pass_type_id', $passTypeId)->where('is_redeemed', false)->orderBy('created_at')->get();
        $redeemRoute = route('pass.redeem', ['passTypeId' => $passTypeId]);
        $vendors = Vendor::where('pass_type_id', $passTypeId)->where(function ($q) {
            $q->where('is_withdrawn', false)->orWhere('withdrawal_dt', '>', Carbon::now());
        })->orderBy('name')->get();
        $region = Region::find($regionId);

        return view('passes.redeem', compact('title', 'passType', 'passTypeId', 'redeemRoute', 'vendors', 'passes', 'region'));
    }

    public function bonus($regionId)
    {
        $user = auth()->user();
        $title = 'Bonus Offers';
        $passType = PassType::where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Bonus')->where('region_id', $regionId)->first();
        $passTypeId = $passType->id;
        $passes = $user->passes()->where('pass_type_id', $passTypeId)->where('is_redeemed', false)->orderBy('created_at')->get();
        $redeemRoute = route('pass.redeem', ['passTypeId' => $passTypeId]);
        $vendors = Vendor::where('pass_type_id', $passTypeId)->where(function ($q) {
            $q->where('is_withdrawn', false)->orWhere('withdrawal_dt', '>', Carbon::now());
        })->orderBy('name')->get();
        $region = Region::find($regionId);

        return view('passes.redeem', compact('title', 'passType', 'passTypeId', 'redeemRoute', 'vendors', 'passes', 'region'));
    }

    public function mini($regionId)
    {
        $user = auth()->user();
        $title = 'Mini';
        $passType = PassType::where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Mini')->where('region_id', $regionId)->first();
        $passTypeId = $passType->id;
        $passes = $user->passes()->where('pass_type_id', $passTypeId)->where('is_redeemed', false)->orderBy('created_at')->get();
        $redeemRoute = route('pass.redeem', ['passTypeId' => $passTypeId]);
        $vendors = Vendor::where('pass_type_id', $passTypeId)->where(function ($q) {
            $q->where('is_withdrawn', false)->orWhere('withdrawal_dt', '>', Carbon::now());
        })->orderBy('name')->get();
        $region = Region::find($regionId);

        return view('passes.redeem', compact('title', 'passType', 'passTypeId', 'redeemRoute', 'vendors', 'passes', 'region'));
    }

    public function redeemPass(Request $request, $passTypeId)
    {
        $user = auth()->user();
        // $diningPassType = PassType::DINING_PASS_TYPE;

        $validator = \Validator::make($request->all(), [
            'redeemVendorLocationId' => 'required',
            'redeemVendorId' => 'required',
            'redeemVendorQuantity' => 'required|integer',
            'redeemCode' => 'required',
        ]);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->all()], 400);

        $vendor = Vendor::find($request->get('redeemVendorId'));
        if (is_null($vendor))
            return response()->json(['error' => 'Invalid vendor'], 400);

        $redeemPassQty = (int)$request->get('redeemVendorQuantity');
        $passes = $user->passes()->where('pass_type_id', $passTypeId)->where('is_redeemed', false)->orderBy('created_at')->get();
        $maxVendorPassQtyForUser = $vendor->getMaxPassQtyForUser($passes);

        if ($vendor->pass_type_id != $passTypeId)
            return response()->json(['error' => 'Invalid vendor'], 400);
        if (strtolower($vendor->pass_code) != strtolower($request->get('redeemCode')))
            return response()->json(['error' => 'Redemption code did not match. Please check your code and try again.'], 400);
        if ($vendor->is_withdrawn && Carbon::parse($vendor->withdrawal_dt) <= Carbon::now())
            return response()->json(['error' => 'This vendor has withdrawn and the offers are no longer available'], 400);
        if ($passes->count() === 0 || $maxVendorPassQtyForUser === 0)
            return response()->json(['error' => 'No active rewards available for this venue.'], 400);
        if ($redeemPassQty > $maxVendorPassQtyForUser)
            return response()->json(['error' => "The quantity specified exceeds available pass uses. Your maximum available pass uses for this venue is currently $maxVendorPassQtyForUser."], 400);

        // $availablePass = $this->getAvailablePass($passes, $vendor->id);
        $confCode = null;
        for ($i = 0; $i < $redeemPassQty; $i++) {
            $availablePass = $vendor->getAvailablePass($passes);
            if ($availablePass === false) {
                \Log::error("Active rewards were found for this venue but encountered a non-available pass for this vendor while looping over the calculated available pass quantity. pass_purchase_id: $availablePass->id. vendor_id: $vendor->id");
                return response()->json(['error' => 'Sorry, an error occurred while processing pass redemption.'], 400);
            }

            $passUsage = PassUsage::create([
                'pass_purchase_id'      => $availablePass->id, 
                'vendor_id'             => $vendor->id, 
                'vendor_location_id'    => $request->get('redeemVendorLocationId'),
                'conf_code'             => $confCode
            ]);

            if ($i === 0) {
                $hashids = new Hashids(env('APP_NAME'), 10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
                $passUsage->conf_code = $confCode = $hashids->encode($passUsage->id);
                $passUsage->save();
            }

            $availablePass->setRedemptionStatus();

            \Log::info('Pass redeemed! Pass use details:');
            \Log::info($passUsage);
        }

        if (env('DEMO_EMAIL_DOMAIN') != substr($user->email, strpos($user->email, "@") + 1)) //if the email is not a demo account, send out emails
        {
            Mail::to($user->email)->send(new NotifyCustomerPassUsed($passUsage, $redeemPassQty, $confCode));
            if (!is_null($vendor->email))
                Mail::to($vendor->email)->send(new NotifyVendorPassUsed($passUsage, $redeemPassQty, $confCode));
        }

        return response()->json(['result' => 'Offer redeemed!'], 200);
    }
    /*
    public function getVendorProperties($vendorId)
    {
        $vendorLocations = VendorLocation::where('vendor_id', $vendorId)->get();
        $vendorName = $vendorLocations[0]->vendor->name;
        $vendorOffer = $vendorLocations[0]->vendor->offer_desc;
        $vendorLocationOptions = [];
        foreach ($vendorLocations as $vendorLocation)
        {
            $option = "<option value=\"" . $vendorLocation->id ."\">" . $vendorLocation->name . "</option>";
            $vendorLocationOptions[] = $option;
        }
        
        return response()->json(['vendorName' => $vendorName, 'vendorOffer' => $vendorOffer, 'vendorLocationOptions' => $vendorLocationOptions, ]);
    }
    */
}

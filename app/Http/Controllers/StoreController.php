<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

use App\Store;
use Carbon\Carbon;
use App\User;
use App\UserProvider;
use App\PassPurchase;
use App\Region;
use App\PassType;
use App\Vendor;

class StoreController extends Controller
{
    use StoreTraits;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stores = auth()->user()->stores;

        return redirect()->route('shopify.store', ['storeId' => $stores->first()->id]);
    }

    public function shopifyStore(Request $request, $storeId)
    {
        $user = auth()->user();
        $store = $user->stores->where('id', $storeId)->first();
        $userProvider = auth()->user()->providers->where('provider', 'shopify')->first();
        $regions = Region::pluck('name', 'id');
        $vendors = Vendor::pluck('name', 'id');
        $passTypes = \App\Helpers\Helper::getPassTypes();
        $customerPassUsers = $this->getAllCustomersWithPassHistory();
        $vendorPassUses = $this->getPassUsageByVendor();

        $monthSelect = $this->getMonthSelection(2);

        try {
            $shopify = \Shopify::retrieve($store->domain, $userProvider->provider_token);
        }
        catch (\Exception $e) {
            \Log::error('An error was encountered while communicating with Shopify for the given domain and provider_token:' . $e->getMessage());

            Auth::logout();

            return redirect()->route('login.shopify');
        }

        return view('store.admin', compact('user', 'store', 'vendors', 'passTypes', 'regions', 'customerPassUsers', 'vendorPassUses', 'monthSelect'));
    }

    public function demoAccount(Request $request)
    {
        $input = $request->all();
        $input['email'] = $input['email'] . '@' . env('DEMO_EMAIL_DOMAIN');
        $input['is_reset'] = ($input['is_reset'] == 'true') ? true : false;
        $isReset = $input['is_reset'];
        $validator = \Validator::make($input, [
            'name'      => 'required',
            'email'     => 'required|email|regex:/(.*)tri-fun\.com$/i',
            'is_reset'  => 'required|boolean'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()->all()], 400);

        $user = User::where('email', $input['email'])->first();
        if ($isReset) {
            if (is_null($user))
                return response()->json(['errors' => ['No user found with that email']], 400);

            $this->resetPasses($user);
        }
        else {
            if (!is_null($user))
                return response()->json(['errors' => ['User is already registered']], 400);

            $user = $this->createUser($input['name'], $input['email']);
            if (!$user->hasRole('customer'))
                $user->attachRole('customer');

            $this->createPassPurchases($user);
            $this->sendNewUserLoginLink($user->email);
        }
        
        $result = ($isReset) ? 'Passes reset for ' . $input['email'] : 'User registered. Notice sent to ' . $input['email'];

        return response()->json(['result' => $result]);
    }

    public function getAllCustomersWithPassHistory()
    {
        $customerUsersWithHistory = \DB::table('users')->distinct()
        ->leftJoin('pass_purchases', 'users.id', '=', 'pass_purchases.user_id')
        ->leftJoin('pass_usages', 'pass_purchases.id', '=', 'pass_usages.pass_purchase_id')        
        ->where('pass_usages.redeemed_at', '!=', null)
        ->orderBy('users.name', 'ASC')
        ->select('users.id', 'users.name', 'users.email')
        ->get();

        $customerUsers = [];
        foreach ($customerUsersWithHistory as $customer) {
            $customerUsers[$customer->id] = $customer->name . " ($customer->email)";
        }

        return $customerUsers;
    }

    public function getPassRedemptionHistory(Request $request) 
    {
        $userId = $request->get('customerId');
        $customerUser = User::find($userId);
        if (is_null($customerUser))
            return response()->json(['errors' => ['Unable to find customer. Please reload your browser and try again']]);

        if ($customerUser->passes->count() == 0) 
            return response()->json(['errors' => ['No passes found for this customer']]);

        $allPassUses = \DB::table('pass_usages')
        ->leftJoin('pass_purchases', 'pass_usages.pass_purchase_id', '=', 'pass_purchases.id')
        ->leftJoin('vendors', 'pass_usages.vendor_id', '=', 'vendors.id')
        ->leftJoin('vendor_locations', 'pass_usages.vendor_location_id', '=', 'vendor_locations.id')
        ->leftJoin('pass_types', 'pass_purchases.pass_type_id', '=', 'pass_types.id')
        ->leftJoin('regions', 'pass_types.region_id', '=', 'regions.id')
        ->leftJoin('users', 'pass_purchases.user_id', '=', 'users.id')
        ->where('user_id', $customerUser->id)
        ->where('pass_usages.redeemed_at', '!=', null)
        ->orderBy('pass_usages.redeemed_at', 'DESC')
        ->select(\DB::raw('DISTINCT pass_usages.conf_code, users.name, users.email, regions.name AS region_name, pass_purchases.id AS pass_purchase_id, 
        vendors.name AS vendor_name, vendor_locations.name AS vendor_location_name, pass_purchases.order_number, 
        pass_usages.redeemed_at AS pass_used_dt, pass_types.name AS pass_type_name'))
        ->get();

        return response()->view('store.customer_history_list', compact('allPassUses'));
        // dd($allPassUses);

        // $passHistory = [];
        // foreach ($user->passes as $pass) {
        //     $passHistory[] = [];
        // }
    }

    public function getVenueRedemptionHistory(Request $request)
    {
        $vendorId = $request->get('vendorId');
        $vendor = Vendor::find($vendorId);
        if (is_null($vendor))
            return response()->json(['errors' => ['Unable to find venue. Please reload your browser and try again']]);

        if ($vendor->passUsages->count() == 0) 
            return response()->json(['errors' => ['No pass uses found for this venue']]);

        
        $vendorPassUses = \DB::table('pass_usages')
        ->leftJoin('pass_purchases', 'pass_usages.pass_purchase_id', '=', 'pass_purchases.id')
        ->leftJoin('vendors', 'pass_usages.vendor_id', '=', 'vendors.id')
        ->leftJoin('vendor_locations', 'pass_usages.vendor_location_id', '=', 'vendor_locations.id')
        ->leftJoin('pass_types', 'pass_purchases.pass_type_id', '=', 'pass_types.id')
        ->leftJoin('regions', 'pass_types.region_id', '=', 'regions.id')
        ->leftJoin('users', 'pass_purchases.user_id', '=', 'users.id')
        ->where('pass_usages.vendor_id', $vendor->id)
        ->where('pass_usages.redeemed_at', '!=', null)
        ->orderBy('pass_usages.redeemed_at', 'DESC')
        ->select(\DB::raw('DISTINCT pass_usages.conf_code, users.name, users.email, regions.name AS region_name, pass_purchases.id AS pass_purchase_id, 
        vendors.name AS vendor_name, vendor_locations.name AS vendor_location_name, pass_purchases.order_number, 
        pass_usages.redeemed_at AS pass_used_dt, pass_types.name AS pass_type_name'))
        ->get();

        return response()->view('store.vendors.vendor_history_list', compact('vendorPassUses'));
    }

    public function getMonthlyRedemptionHistory(Request $request)
    {
        $monthName = $request->get('monthName');
        $monthInt = (int)$request->get('monthInt');
        $monthlyPassUses = $this->getVendorsWithPassUsageCountInMonth($monthInt);        

        return response()->view('store.vendors.vendor_monthly_history_list', compact('monthName', 'monthlyPassUses'));
    }

    function resetPasses($user)
    {
        //ensure demo user has customer role
        if (!$user->hasRole('customer'))
            $user->attachRole('customer');

        $typeIds = [];
        //clear out pass usage history
        foreach ($user->passes as $pass) {
            $typeIds[] = $pass->pass_type_id;
            if ($pass->passUsages->count() > 0)
                $pass->passUsages()->delete();
        }
        //ensure user has at least one of each pass type
        $passTypeIds = array_unique($typeIds);
        $passTypes = PassType::all();
        foreach ($passTypes as $passType) {
            if (!in_array($passType->id, $passTypeIds))
                $this->createPassPurchases($user, $passType->id);
        }
    }

    function createUser($name, $email)
    {
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890');
        $password = str_random($random, 0, 32);
        $user = User::create([
            'customer_id'   => 0,
            'name'          => $name,
            'email'         => $email,
            'password'      => \Hash::make($password),
        ]); 

        return $user;
    }

    function createPassPurchases($user, $passTypeId = null)
    {
        if (!is_null($passTypeId)) {
            $pass = PassPurchase::create([
                'user_id'       => $user->id,
                'order_number'  => 0,
                'pass_type_id'  => $passTypeId
            ]);
        }
        else {
            $passTypes = PassType::all();
            foreach ($passTypes as $passType) {
                $pass = PassPurchase::create([
                    'user_id'       => $user->id,
                    'order_number'  => 0,
                    'pass_type_id'  => $passType->id
                ]);
            }
        }
    }

    /**
     * Handle order paid webhook from shopify store.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPaid(Request $request)
    {
        \Log::info('BEGIN Order Paid shopify callback');
        $orderNumber = $request->get('order_number');
        $customer = $request->get('customer');
        $passPurchases = $request->get('line_items');
        \Log::info("Order #$orderNumber");
        \Log::info($customer);

        $user = User::where('customer_id', $customer['id'])->first();
        if (is_null($user))
        {
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890');
            $password = str_random($random, 0, 32);
            $user = User::create([
                'customer_id'   => $customer['id'],
                'name'          => $customer['first_name'] .  ' ' . $customer['last_name'],
                'email'         => $customer['email'],
                'password'      => \Hash::make($password),
            ]); 
            //send email login link here
            $this->sendNewUserLoginLink($user->email);
        }
        else if ($user->email != $customer['email']) //if the customer's email has changed, update it
        {
            $user->email = $customer['email'];
            $user->save();
        }

        if (!$user->hasRole('customer'))
            $user->attachRole('customer');

        $passPurchaseOrder = PassPurchase::where('order_number', $orderNumber)->first();
        if (is_null($passPurchaseOrder))
        {
            $bonusPassRegionsAdded = [];
            foreach ($passPurchases as $passPurchase)
            {
                for ($i = 1; $i <= $passPurchase['quantity']; $i++)
                {
                    $region = Region::where('code', $passPurchase['sku'])->first();
                    if (is_null($region))
                    {
                        \Log::error("Unable to find a region (sku = regions.code) that corresponds to the product in this line item. Order #$orderNumber. Line Item:");
                        \Log::error($passPurchase);
                        if (env('APP_ENV') == 'production') {
                            \Log::channel('slack')->error("Unable to find a region (sku = regions.code) that corresponds to the product in this line item. Order #$orderNumber. Line Item:");
                            \Log::channel('slack')->error($passPurchase);
                        }
                        continue;
                    }
                    if ($passPurchase['vendor'] == 'experience')
                        $passType = PassType::where('region_id', $region->id)->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Experience')->first();
                    else if ($passPurchase['vendor'] == 'mini')
                        $passType = PassType::where('region_id', $region->id)->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Mini')->first();
                    else
                        $passType = PassType::where('region_id', $region->id)->where('type', $passPurchase['vendor'])->first();

                    if (is_null($passType))
                    {
                        \Log::error("Unable to find a pass type that corresponds to the region and vendor (pass_types.type) in this line item. Order #$orderNumber. Line Item:");
                        \Log::error($passPurchase);
                        if (env('APP_ENV') == 'production') {
                            \Log::channel('slack')->error("Unable to find a pass type that corresponds to the region and vendor (pass_types.type) in this line item. Order #$orderNumber. Line Item:");
                            \Log::channel('slack')->error($passPurchase);
                        }
                        continue;
                    }
                    $pass = PassPurchase::create([
                        'user_id'       => $user->id,
                        'order_number'  => $orderNumber,
                        'pass_type_id'  => $passType->id
                    ]);
                    //add a bonus pass if it exists for this pass
                    $bonusPassType = $this->getBonusPassType($pass);
                    if (!is_null($bonusPassType) && !in_array($region->id, $bonusPassRegionsAdded)) {
                        PassPurchase::create([
                            'user_id'       => $user->id,
                            'order_number'  => $orderNumber,
                            'pass_type_id'  => $bonusPassType->id
                        ]);
                        $bonusPassRegionsAdded[] = $region->id;
                        $bonusPassRegionsAdded = array_unique($bonusPassRegionsAdded);
                    }
                }
            }
        }
        
        \Log::info('END Order Paid shopify callback');
        return (new \Illuminate\Http\Response)->setStatusCode(200);
    }

    function getBonusPassType($pass)
    {
        $bonusPassType = PassType::where('region_id', $pass->passType->region->id)->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Bonus')->first();

        return $bonusPassType;
    }

    function getMiniPassType($pass)
    {
        $miniPassType = PassType::where('region_id', $pass->passType->region->id)->where('type', PassType::EXPERIENCE_PASS_TYPE)->where('name', 'Mini')->first();

        return $miniPassType;
    }

    public function customerUpdate(Request $request) 
    {
        $customerId = $request->get('id');
        $email = $request->get('email');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');

        $user = User::where('customer_id', $customerId)->first();
        if (!is_null($user))
        {
            $user->email = $email;
            $user->name = $firstName . ' ' . $lastName;
            $user->save();
        }
        else {
            \Log::error("A user in shopify was updated, but no associated user was found in the trifun database matching this customer_id: $customerId Updated customer: $firstName $lastName $email");
        }

        return (new \Illuminate\Http\Response)->setStatusCode(200);
    }

    /**
     * Uninstall the app from the user's shopify store.
     *
     * @return \Illuminate\Http\Response
     */
     public function uninstall(Request $request)
     {
         \Log::info('BEGIN Uninstall shopify store callback');        
         $store = Store::where('domain', $request->get('domain'))->first();
         \Log::info('Uninstalling shopify store id ' . $store->id);
         $this->removeStoreData($store);
 
         /* For apps that use trial subscriptions */
         if (!is_null($store->subscription_plan_id))
             $this->handleTrialSubscriptionEnd($store);
 
         $this->markUserForLogout($store); 
 
         \Log::info('END Uninstall shopify store callback');
         return (new \Illuminate\Http\Response)->setStatusCode(200);
     }

    //register webhook for when a shop updates their plan
    // public function updateShop(Request $request)
    // {
    //     $store = Store::where('domain', $request->get('domain'))->first();
    //     //loop through charges and make sure details match up, if they don't then correct them
    //     return (new \Illuminate\Http\Response)->setStatusCode(200);
    // }

    public function customerRedact(Request $request)
    {
        return (new \Illuminate\Http\Response)->setStatusCode(200);
    }

    public function storeRedact(Request $request)
    {
        return (new \Illuminate\Http\Response)->setStatusCode(200);
    }

    public function customerData(Request $request)
    {
        return (new \Illuminate\Http\Response)->setStatusCode(200);
    }

}

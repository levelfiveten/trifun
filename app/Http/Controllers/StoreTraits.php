<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

use App\User;
use App\Store;
use App\Charge;
use App\PassUsage;
use App\SubscriptionPlan;
use Carbon\Carbon;

trait StoreTraits {

    function getPassUsageByVendor()
    {
        $vendorsWithPassUsage = \DB::table('vendors')->distinct()
        ->leftJoin('pass_usages', 'vendors.id', '=', 'pass_usages.vendor_id')        
        ->where('pass_usages.id', '!=', null)
        ->orderBy('vendors.name', 'ASC')
        ->select('vendors.id', 'vendors.name')
        ->pluck('vendors.name', 'vendors.id');

        return $vendorsWithPassUsage;
    }

    function getVendorsWithPassUsageCountInMonth($month)
    {
        $month_request_string = substr_replace($month, "-", 4, 0);
        $month_request_string .= '-01';
        $month_date = strtotime(date($month_request_string));
        $first_day_of_month = new \DateTime('first day of ' . date('F Y', $month_date));
        $last_day_of_month = new \DateTime('last day of ' . date('F Y', $month_date));
        $first_day_of_month->setTime(00, 00, 00);
        $last_day_of_month->setTime(24, 00, 00);
        $monthStartDt = $first_day_of_month->format('Y-m-d H:i:s');
        $monthEndDt = $last_day_of_month->format('Y-m-d H:i:s');
        // dd($monthEndDt);
        // \DB::enableQueryLog();
        $vendorsInMonth = \DB::table('vendors')->distinct()
        ->leftJoin('pass_usages', 'vendors.id', '=', 'pass_usages.vendor_id')   
        ->leftJoin('pass_types', 'vendors.pass_type_id', '=', 'pass_types.id')
        ->leftJoin('regions', 'pass_types.region_id', '=', 'regions.id')
        ->where('vendors.id', '!=', null)
        ->where('pass_usages.redeemed_at', '>=', $monthStartDt)->where('pass_usages.redeemed_at', '<=', $monthEndDt)
        ->select('vendors.id', 'vendors.name', \DB::raw('pass_types.name AS pass_type_name, regions.name AS region_name, 0 AS qty'))
        ->orderBy('regions.name', 'ASC')->orderBy('vendors.name', 'ASC')->get();
        // dd(\DB::getQueryLog());
        // dd($vendorsInMonth);
        foreach($vendorsInMonth as $vendor) {
            $vendorUsage = PassUsage::where('vendor_id', $vendor->id)
            ->where('redeemed_at', '>=', $monthStartDt)
            ->where('redeemed_at', '<=', $monthEndDt)
            ->count();
            // dd($vendorUsage);
            $vendor->qty = $vendorUsage;
        }

        return $vendorsInMonth;
    }

    function getMonthSelection($month_range)
    {
        $months_select = [];
        for ($i = 0; $i < $month_range; $i++) {
            $month_year_string = date("Ym", strtotime( date( 'Y-m' )." -$i months"));
            $months_select[$month_year_string] = date("F Y", strtotime( date( 'Y-m' )." -$i months"));
        }

        return $months_select;
    }

    function sendNewUserLoginLink($email)
    {
        $credentials = ['email' => $email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        // switch ($response) {
        //     case Password::RESET_LINK_SENT:
        //         \Log::info('email login/reset link sent');
        //     case Password::INVALID_USER:
        //         \Log::error('Error sending to email');
        // }
    }

    function removeStoreData($store)
    {
        $user = $store->users->first();
        $store->uninstalled = true;
        $store->uninstall_dt = Carbon::now();
        $store->save();
    }
    
    function handleTrialSubscriptionEnd($store)
    {
        $storeCharge = $store->subscription($store->subscription_plan_id);
        if ($storeCharge)
        {
            $storeCharge->ends_at = Carbon::now();
            if (!is_null($storeCharge->trial_ends_at))
                $storeCharge->trial_ends_at = $store->uninstall_dt;

            $storeCharge->save();
        }
    }

    function markUserForLogout($store)
    {
        //webhook callback does not contain the state of a specific user, so mark the user for logout and process via middleware on next web request
        $user = User::whereHas('stores', function($q) use ($store) {
            $q->where('store_id', $store->id);
        })->first();
        $user->logout = true;
        $user->save();
    }

    function updateCurrentCharge($storeCharge, $shopifyCharge)
    {
        $updated_at = \App\Helpers\Helper::convertTimezoneToApp($shopifyCharge['updated_at']);
        $storeCharge->ends_at = ($shopifyCharge['status'] == 'active') ? null : $updated_at;
        if (!is_null($shopifyCharge['trial_ends_on']) && $shopifyCharge['status'] != 'active')
            $storeCharge->trial_ends_at = $shopifyCharge['trial_ends_on'];
            
        $storeCharge->save();
    }

    public function setChargeEndDatesViaBillingAPI($store, $userProvider)
    {
        $shopifyCharges = \ShopifyBilling::driver('RecurringBilling')
            ->getAllCharges($store->domain, $userProvider->provider_token);
        foreach ($shopifyCharges['recurring_application_charges'] as $shopifyCharge) 
        {
            $storeCharge = $store->subscriptions()->where('shopify_charge_id', $shopifyCharge['id'])->first();
            if (!is_null($storeCharge)) 
            {
                if ($storeCharge->shopify_charge_id == $shopifyCharge['id']) 
                    $this->updateCurrentCharge($storeCharge, $shopifyCharge);
            }
        }
    }

    public function getActivatedCharge($storeId)
    {
        $store = Store::findOrFail($storeId);
        $user = auth()->user()->providers->where('provider', 'shopify')->first();
        try 
        {
            $shopify = \Shopify::retrieve($store->domain, $user->provider_token);
        }
        catch (\Exception $e) 
        {
            \Log::error('An error was encountered while communicating with Shopify for the given domain and provider_token' . $e->getMessage());
            Auth::logout();

            return redirect()->route('login');
        }
        $charges = \ShopifyBilling::driver('RecurringBilling')
            ->getAllCharges($store->domain, $user->provider_token);

        $activated_charge = array_get($charges->getActivated(), 'recurring_application_charge');

        return $activated_charge;
    }

    public function validateSubscription($store, $subscriptionPlan)
    {
        if ($store->subscribed($subscriptionPlan->id))
            return redirect()->route('shopify.signup', ['storeId' => $store->id])->with('status-error', 'You already have an active account on that plan');            
    }

    public function getTrialDaysRemaining($store, $plan) 
    {
        if (is_null($store->first_subscribe_dt))
            return $plan->trial_days;
        else 
            $trialDaysRemaining = $plan->trial_days - (date_diff(new Carbon($store->first_subscribe_dt), Carbon::now())->days);

        return $trialDaysRemaining;
    }
}
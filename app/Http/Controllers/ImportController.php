<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Store;
use Carbon\Carbon;
use App\User;
use App\UserProvider;
use App\PassPurchase;
use App\PassUsage;
use App\Region;
use App\PassType;
use App\Vendor;

use Illuminate\Support\Facades\Mail;
use App\Mail\ImportWelcome;

class ImportController extends Controller
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

    public function sendWelcomeEmail(Request $request) 
    {
        $emails = explode(',', $request->emails);
        foreach ($emails as $email) {
            Mail::to($email)->send(new ImportWelcome());
        }

        return 'welcome emails sent';
    }

    public function importExistingCustomers(Request $request)
    {
        $expPassType = PassType::where('type', PassType::EXPERIENCE_PASS_TYPE)->first();

        $user = auth()->user();
        $store = $user->stores->first();
        $userProvider = auth()->user()->providers->where('provider', 'shopify')->first();
        try {
            $shopify = \Shopify::retrieve($store->domain, $userProvider->provider_token);
        }
        catch (\Exception $e) {
            \Log::error('An error was encountered while communicating with Shopify for the given domain and provider_token:' . $e->getMessage());
            Auth::logout();

            return redirect()->route('login.shopify');
        }

        $merged_customers = [];
        // $customer_emails = [];
        if (($handle = fopen(public_path("/TF_Member_Upload120718.csv"), "r")) !== FALSE) {
            $i = 0;
            while (($cols = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $customer = [];
                foreach( $cols as $key => $val ) {
                    // dd($val);
                    if ($i == 0) {
                        continue;
                    }
                    if ($key == 0)
                        $customer['first_name'] = $val;
                    if ($key == 1)
                        $customer['last_name'] = $val;
                    if ($key == 2)
                        $customer['address1'] = $val;
                    if ($key == 3)
                        $customer['city'] = $val;
                    if ($key == 4)
                        $customer['state'] = $val;
                    if ($key == 5)
                        $customer['zip'] = $val;
                    if ($key == 6)
                        $customer['email'] = $val;
                    if ($key == 7)
                        $customer['phone'] = $val;
                    if ($key == 8)
                        $customer['order_dt'] = $val;
                    if ($key == 9)
                        $customer['qty'] = $val;
                    if ($key == 10)
                        $customer['expire_dt'] = $val;
                    if ($key == 11 && $val == 'X') 
                        $customer['wet_used'] = $val;
                    
                }
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $i++;
                $merged_customers[] = $customer;
            }
            fclose($handle);
        }
        // dd($merged_customers);
        // if (($handle = fopen(public_path("/wp_usermeta2.csv"), "r")) !== FALSE) {
        //     $i = 0;
        //     $user_id = 0;
        //     $addresses = [];
        //     while (($cols = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //         //new row level
        //         $address = []; 
        //         $meta_name = '';           
        //         foreach( $cols as $key => $val ) {
        //             //new column level
        //             if ($key == 0) {
        //                 if ($val != $user_id) {
        //                     $i++;
        //                     $addresses[$i]['old_user_id'] = $val;
        //                     $user_id = $val;
        //                 }
        //             }
        //             if ($key == 1) 
        //                 $meta_name = $this->setNextMetaName($val);                    
        //             if ($key == 2 && !(is_null($meta_name)))
        //                 $addresses[$i][$meta_name] = $val;
        //         }
        //     }
        //     fclose($handle);
        // }
        // dd($addresses);

        // $customer_passes = [];
        // if (($handle = fopen(public_path("/wp-orders-test.csv"), "r")) !== FALSE) {
        //     // $i = 0;
        //     while (($cols = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //         $customer_pass = [];
        //         // $customer_pass['members'] = [];
        //         foreach( $cols as $key => $val ) {
        //             // dd($val);
        //             if ($key == 0)
        //                 $customer_pass['order_id'] = $val;
        //             if ($key == 1)
        //                 $customer_pass['email'] = $val;
        //             if ($key == 2)
        //                 $customer_pass['order_dt'] = $val;
        //             if ($key == 3)
        //                 $customer_pass['qty'] = $val;
        //         }
        //         // $i++;
        //         // if (count($customer_pass['members']) > 0)
        //         $customer_passes[] = $customer_pass;
        //     }
        //     fclose($handle);
        // }
        // dd($customer_passes);

        // $customer_pass_uses = [];
        // if (($handle = fopen(public_path("/wp-pass-use.csv"), "r")) !== FALSE) {
        //     while (($cols = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //         $customer_pass_use = [];
        //         foreach( $cols as $key => $val ) {
        //             if ($key == 0)
        //                 $customer_pass_use['email'] = $val;
        //             if ($key == 1)
        //                 $customer_pass_use['vendor_name'] = $val;
        //             if ($key == 2)
        //                 $customer_pass_use['order_id'] = $val;
        //         }
        //         $customer_pass_uses[] = $customer_pass_use;
        //     }
        //     fclose($handle);
        // }
        //dd($customer_pass_uses);

        // $merged_customers = [];
        // foreach ($customers as $customer) {
            // foreach ($addresses as $address) {
            //     if ($customer['old_user_id'] == $address['old_user_id']) {
            //         if (isset($address['billing_email']) && !empty($address['billing_email']))
            //             $customer['billing_email'] = $address['billing_email'];
            //         if (isset($address['_order_count']) && !empty($address['_order_count']))
            //             $customer['_order_count'] = $address['_order_count'];
            //         if (isset($address['_money_spent']) && !empty($address['_money_spent']))
            //             $customer['_money_spent'] = $address['_money_spent'];
            //         if (isset($address['billing_phone']) && !empty($address['billing_phone']))
            //             $customer['phone'] = $address['billing_phone'];
            //         if (isset($address['billing_address_1']) && !empty($address['billing_address_1']))
            //             $customer['address1'] = $address['billing_address_1'];
            //         if (isset($address['billing_address_2']) && !empty($address['billing_address_2']))
            //             $customer['address2'] = $address['billing_address_2'];
            //         if (isset($address['billing_city']) && !empty($address['billing_city']))
            //             $customer['city'] = $address['billing_city'];
            //         if (isset($address['billing_state']) && !empty($address['billing_state']))
            //             $customer['state'] = $address['billing_state'];
            //         if (isset($address['billing_postcode']) && !empty($address['billing_postcode']))
            //             $customer['zip'] = $address['billing_postcode'];
            //         // if (isset($address['billing_country']) && !empty($address['billing_country']))
            //             // $customer['country'] = $address['billing_country'];
            //     }
            // }

            // $passes = [];
            // $billing_email = (isset($customer['billing_email'])) ? $customer['billing_email'] : '';
        //     foreach ($customer_passes as $customer_pass) {
        //         if ($customer['email'] == $customer_pass['email'] || $billing_email == $customer_pass['email']) {
        //             $pass['order_id'] = (int)$customer_pass['order_id'];
        //             $pass['qty'] = (int)$customer_pass['qty'];
        //             $pass['order_dt'] = $customer_pass['order_dt'];
        //             $passes[] = $pass;
        //         }
        //     }

        //     $pass_usages = [];
        //     foreach ($customer_pass_uses as $customer_pass_use) {
        //         if ($customer['email'] == $customer_pass_use['email'] || $billing_email == $customer_pass_use['email']) {
        //             $pass_use['vendor_name'] = $customer_pass_use['vendor_name'];
        //             $pass_use['order_id'] = (int)$customer_pass_use['order_id'];
        //             $pass_usages[] = $pass_use;
        //         }
        //     }
        //     $customer['passes'] = $passes;
        //     $customer['pass_usages'] = $pass_usages;

        //     $merged_customers[] = $customer;
        // }
        // dd($merged_customers);

        // $phone_numbers = [];
        foreach($merged_customers as $merged_customer) {
            //if a duplicate phone number already exists, remove this phone so Shopify validation can pass and continue with import
            // if (isset($merged_customer['phone'])) {
            //     if (in_array($merged_customer['phone'], $phone_numbers))
            //         $merged_customer['phone'] = '';
            //     else
            //         $phone_numbers[] = $merged_customer['phone'];
            // }

            // $phone = (strlen($merged_customer['phone']) > 6) ? $merged_customer['phone'] : '';
            $customer_data = [
                'customer' => [
                    'first_name'        => $merged_customer['first_name'],
                    'last_name'         => $merged_customer['last_name'],
                    'email'           => $merged_customer['email'],
                    // 'email'             => 'ted@buypct.com',
                    'phone'             => $merged_customer['phone'],
                    'verified_email'    => true,
                    'addresses' => [
                        ['first_name'    => $merged_customer['first_name'],
                        'last_name'     => $merged_customer['last_name'],
                        'address1'      => $merged_customer['address1'],
                        'city'          => $merged_customer['city'],
                        'province'      => $merged_customer['state'],
                        'phone'         => $merged_customer['phone'],
                        'zip'           => $merged_customer['zip'],
                        'country'       => 'US']
                    ],
                    'send_email_welcome' => false
                ]
            ];
            // try {
                /* CREATE SHOPIFY CUSTOMER */
                $shopify_customer = $shopify->createCustomers($customer_data);
                $shopify_customer = array_get($shopify_customer, 'customer');
                $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ');
                $password = str_random($random, 0, 32);
                // $password = 'testimport';
                /* CREATE USER */                
                \Log::info('Creating user from shopify customer');
                \Log::info('id:' . $shopify_customer['id'] . ' first:' . $shopify_customer['first_name'] . ' last:' . $shopify_customer['last_name'] . ' email:' . $shopify_customer['email']);                
                $user = User::create([
                    'customer_id'   => $shopify_customer['id'],
                    'name'          => $shopify_customer['first_name'] .  ' ' . $shopify_customer['last_name'],
                    'email'         => $shopify_customer['email'],
                    'password'      => \Hash::make($password)
                ]); 
                /* GIVE USER CUSTOMER ROLE */
                $user->attachRole('customer');
                /* PASS PURCHASES FROM OLD PASS DATA */
                for ($i = 1; $i <= $merged_customer['qty']; $i++) {
                    PassPurchase::create([
                        'user_id' => $user->id,
                        'pass_type_id' => $expPassType->id,
                        'order_number' => 0,
                        // 'order_number_old' => $pass['order_id'],
                        'created_at' => Carbon::createFromFormat("m/d/Y", $merged_customer['order_dt']) 
                    ]);              
                }
                /* PASS USAGES FROM OLD PASS USE DATA */
                if (isset($merged_customer['wet_used'])) {
                    for ($j = 1; $j <= $merged_customer['qty']; $j++) {
                        $this->setPassUsage('The Wet Palette', $user);
                    }
                }
                // if (count($merged_customer['passes']) > 0) {
                    // foreach ($merged_customer['pass_usages'] as $pass_usage) 
                    //     $this->setPassUsage($pass_usage, $user);
                // }
                //send email login link here
                //$this->sendNewUserLoginLink($user->email);
                //dd($shopify_customer);
                //break; //test first one
            // }
            // catch (\Exception $e) {
            //     \Log::error('Error while creating customer from import data. ' . $e->getMessage() . ' Customer data:');
            //     \Log::error($customer_data);
            // }
        }

        return 'import complete';
    }

    function setPassUsage($vendor_name, $user) {
        $vendor = Vendor::where('name_old', $vendor_name)->first();
        $location = $vendor->locations->first();
        // if (is_null($vendor))
        //     return;

        $availablePass = $vendor->getAvailablePass($user->passes);
        if ($availablePass) {
            $passUsage = PassUsage::create([
                'pass_purchase_id'      => $availablePass->id, 
                'vendor_id'             => $vendor->id, 
                'vendor_location_id'    => $location->id,
                'redeemed_at'           => $availablePass->created_at
            ]);

            $availablePass->setRedemptionStatus();
        }
    }

    function setNextMetaName($value) {
        switch($value) {
            case 'billing_email':
            case 'billing_address_1':
            case 'billing_address_2':
            case 'billing_phone':
            case 'billing_city':
            case 'billing_state':
            case 'billing_postcode':
            case 'billing_country':
            case '_order_count':                
            case '_money_spent':
                return $value;
                break;
            default:
                return null;
        }
    }

}
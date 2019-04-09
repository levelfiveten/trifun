<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
\URL::forceScheme('https');

Auth::routes(['verify' => true]);
Route::get('/logout', 'Auth\LoginController@logout');

/* Shopify login routes */
Route::get('login/shopify/tri-fun', ['uses' => 'Auth\LoginShopifyController@index'])->name('login.trifun');
Route::get('login/shopify', ['uses' => 'Auth\LoginShopifyController@redirectToProvider'])->name('login.shopify');
Route::get('login/shopify/callback', ['uses' => 'Auth\LoginShopifyController@handleProviderCallback']);

Route::get('/store/regions', 'RegionController@getRegionsAsync')->name('regions.get');
Route::get('/store/vendors', 'VendorController@getVendorsAsync')->name('vendors.get');
Route::get('/store/vendor/properties', 'VendorController@getVendorProperties')->name('vendor.properties');
Route::get('/store/vendor/location/properties', 'VendorController@getVendorLocationProperties')->name('vendor.location.properties');

/* Store admin routes */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/store', ['uses' => 'StoreController@index'])->name('store');
    Route::get('/store/{storeId}', 'StoreController@shopifyStore')->name('shopify.store'); //dashboard

    Route::get('/store/import/customers', 'ImportController@importExistingCustomers')->name('import.customers');
    Route::get('/store/import/send_welcome_email', 'ImportController@sendWelcomeEmail')->name('import.send_welcome_email');

    /* CRUD for regions, pass_types, and vendors --requires auth with admin role */
    /* Regions */    
    Route::get('/store/region/view', 'RegionController@view')->name('regions.view');
    Route::post('/store/region/create', 'RegionController@create')->name('region.create');
    Route::patch('/store/region/update', 'RegionController@update')->name('region.update');
    Route::delete('/store/region/delete', 'RegionController@delete')->name('region.delete');    

    /* Pass Types */
    Route::get('/store/region/pass_types/view', 'PassTypeController@view')->name('region.passTypes.view');
    Route::post('/store/region/pass_type/create', 'PassTypeController@create')->name('region.passType.create');
    Route::patch('/store/region/pass_type/update', 'PassTypeController@update')->name('region.passType.update');
    Route::delete('/store/region/pass_type/delete', 'PassTypeController@delete')->name('region.passType.delete'); 
    Route::get('/store/region/pass_types/getTotalPassUses', 'PassTypeController@getTotalPassUses')->name('region.passTypes.totalPassUses');

    /* Vendors */
    Route::get('/store/vendor/view', 'VendorController@view')->name('vendors.view');
    Route::post('/store/vendor/create', 'VendorController@create')->name('vendor.create');
    Route::patch('/store/vendor/update', 'VendorController@update')->name('vendor.update');
    Route::patch('/store/vendor/withdraw', 'VendorController@withdraw')->name('vendor.withdraw');
    Route::patch('/store/vendor/enroll', 'VendorController@enroll')->name('vendor.enroll');

    /* Vendor Locations */
    Route::post('/store/vendor/location/create', 'VendorController@addLocation')->name('vendor.location.create');
    Route::patch('/store/vendor/location/update', 'VendorController@updateLocation')->name('vendor.location.update');
    Route::delete('/store/vendor/location/delete', 'VendorController@deleteLocation')->name('vendor.location.delete');  
    
    /* Demo Accounts */
    Route::post('/store/demo/account', 'StoreController@demoAccount')->name('demo.account');

    /* Customer redemption history */
    Route::get('/store/pass_history/customer', 'StoreController@getPassRedemptionHistory')->name('customer.passHistory');
    Route::get('/store/pass_history/customers', 'StoreController@getAllCustomersWithPassHistory')->name('customer.getPassUsers');
    Route::get('/store/pass_history/vendor', 'StoreController@getVenueRedemptionHistory')->name('vendor.passHistory');
    Route::get('/store/pass_history/vendor_monthly', 'StoreController@getMonthlyRedemptionHistory')->name('vendor.passHistoryMonthly');        
});

/* tri-fun account routes --requires auth with customer role */
Route::get('/', 'HomeController@index')->name('home');
Route::get('dining/{regionId}', 'HomeController@dining')->name('dining');
Route::get('experience/{regionId}', 'HomeController@experience')->name('experience');
Route::get('bonus/{regionId}', 'HomeController@bonus')->name('bonus');
Route::get('mini/{regionId}', 'HomeController@mini')->name('mini');
Route::post('pass/{passTypeId}/redeem', 'HomeController@redeemPass')->name('pass.redeem');
//Route::get('vendor/{vendorId}/properties', 'HomeController@getVendorProperties')->name('vendor.properties');


/* Store webhook routes --no auth, but digital signature is calculated and verified in the webhook middleware */
Route::post('webhook/shopify/uninstall', 'StoreController@uninstall')->middleware('webhook');
Route::post('webhook/shopify/order_paid', 'StoreController@orderPaid')->middleware('webhook');
Route::post('webhook/shopify/customer_update', 'StoreController@customerUpdate')->middleware('webhook');
// Route::get('webhook/shopify/uninstall', 'StoreController@uninstall')->middleware('auth');
Route::post('webhook/shopify/gdpr/customer-redact', 'StoreController@customerRedact')->middleware('webhook'); //make sure that they are not an active client
Route::post('webhook/shopify/gdpr/shop-redact', 'StoreController@storeRedact')->middleware('webhook'); //make sure that they are not an active client
Route::post('webhook/shopify/gdpr/customer-data', 'StoreController@customerData')->middleware('webhook');


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PassUsage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'pass_purchase_id', 'vendor_id', 'vendor_location_id', 'redeemed_at', 'conf_code'
    ];

    public $timestamps = false;

    public function passPurchase()
    {
        return $this->belongsTo(
            'App\PassPurchase'
        );
    }

    public function vendor()
    {
        return $this->belongsTo(
            'App\Vendor'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            'App\VendorLocation', 'vendor_location_id'
        );
    }
}
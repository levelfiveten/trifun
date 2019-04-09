<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorLocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id', 'name', 'address1', 'address2', 'city', 'state', 'zipcode', 'country'
    ];

    public function vendor()
    {
        return $this->belongsTo(
            'App\Vendor'
        );
    }

    public function passUsages()
    {
        return $this->hasMany(
            'App\PassUsage'
        );
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PassType extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'region_id', 'type', 'name', 'days_valid', 'use_per_vendor', 'usage_limit'
    ];

    public $timestamps = false;

    /**
     * Pass Type Id to use for comparison purposes
     */
    const EXPERIENCE_PASS_TYPE = 'experience';
    const DINING_PASS_TYPE = 'dining';
    const SPECIALS_PASS_TYPE = 'special';

    public function passPurchases()
    {
        return $this->hasMany(
            'App\PassPurchase'
        );
    }

    public function region()
    {
        return $this->belongsTo(
            'App\Region'
        );
    }

    public function vendors()
    {
        return $this->hasMany(
            'App\Vendor'
        );
    }
}
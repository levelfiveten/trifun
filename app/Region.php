<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code'
    ];

    public $timestamps = false;

    public function vendors()
    {
        return $this->hasMany('App\Vendor');
    }

    public function passes()
    {
        return $this->hasMany('App\PassPurchase');
    }

    public function passTypes()
    {
        return $this->hasMany('App\PassType');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region_id', 'pass_type_id', 'name', 'email', 'redeem_txt', 'offer_desc', 'pass_code', 'max_pass_use'
    ];

    function getAvailablePass($passes)
    {
        foreach ($passes as $pass)
        {
            if ($pass->getRemainingUses() > 0) 
            {
                if (!$this->passExceedsVendorUseLimit($pass))
                    return $pass;
            }
        }

        return false;
    }

    function passExceedsVendorUseLimit($pass, $passQty = null)
    {
        $passUsage = PassUsage::where('pass_purchase_id', $pass->id)->where('vendor_id', $this->id)->get();
        $passTypeUsePerVendor = $pass->passType->use_per_vendor;
        $vendorMaxPassUse = $this->max_pass_use;

        $maxUsePerVendor = ($this->passType->type == PassType::EXPERIENCE_PASS_TYPE) ? $pass->passType->use_per_vendor : $this->max_pass_use;
        if (is_null($maxUsePerVendor)) //generally when dining pass vendor max pass use is not specified
            $maxUsePerVendor = $pass->passType->use_per_vendor;

        if (is_null($passQty)) {
            if ($passUsage->count() >= $maxUsePerVendor)
                return true;
        }
        else {
            if ($passQty >= $maxUsePerVendor)
                return true;
        }

        return false;
    }

    function getMaxPassQtyForUser($passes) 
    {
        $maxPassQty = null;
        foreach ($passes as $pass)
        {
            $passQtyAvailable = null;
            $remainingUses = $pass->getRemainingUses();
            $passUsedQty = PassUsage::where('pass_purchase_id', $pass->id)->where('vendor_id', $this->id)->count();
            while ($remainingUses > 0) {
                if (!$this->passExceedsVendorUseLimit($pass, $passUsedQty))
                    $passQtyAvailable += 1;

                $passUsedQty++;
                $remainingUses--;
            }
            $maxPassQty += $passQtyAvailable;
        }

        return $maxPassQty;
    }

    /* RELATIONS */
    public function locations()
    {
        return $this->hasMany('App\VendorLocation');
    }

    public function passType()
    {
        return $this->belongsTo('App\PassType');
    }

    public function passUsages()
    {
        return $this->hasMany('App\PassUsage');
    }
}
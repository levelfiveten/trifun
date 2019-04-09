<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PassPurchase extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'order_number', 'order_number_old', 'pass_type_id', 'region_id', 'created_at', 'is_redeemed'
    ];

    /**
     * Get the user that belongs to this pass.
     */
    public function user()
    {
        return $this->belongsTo(
            'App\User', 'user_id'
        );
    }

    /**
     * Get the pass type that belongs to this pass.
     */
    public function passType()
    {
        return $this->belongsTo(
            'App\PassType', 'pass_type_id'
        );
    }

    /**
     * Get all the usages this pass has.
     */
    public function passUsages()
    {
        return $this->hasMany(
            'App\PassUsage'
        );
    }
    

    /**
     * Set pass to redeemed is no more uses remain
     */
    public function setRedemptionStatus()
    {
        if ($this->getRemainingUses() < 1)
        {
            $this->is_redeemed = true;
            $this->save();
        }
    }

    /**
     * Find the remaining uses of a pass based on various factors
     */
    public function getRemainingUses()
    {
        $usesRemaining = null;
        switch ($this->passType->type)
        {
            case PassType::EXPERIENCE_PASS_TYPE:
                $expVendorIdsUnused = $this->getExpVendorIdsRemaining();
                $usesRemaining = count($expVendorIdsUnused);
                break;
            case PassType::DINING_PASS_TYPE:
                $usesRemaining = $this->getDiningPassUsesRemaining();
                break;
            case PassType::SPECIALS_PASS_TYPE:
                break;
        }

        return $usesRemaining;
    }

    public function getVendorCount()
    {
        // return $this->passType->vendors()->count();

        return $this->passType->vendors()
        ->where('vendors.is_withdrawn', false)
        ->orWhere('vendors.withdrawal_dt', '>', Carbon::now())
        ->count();
    }

    /**
     * Find the number of uses remaining for the given dining pass
     *
     * @return Array
     */
    public function getDiningPassUsesRemaining()
    {
        $diningPassUsage = $this->passUsages()
            ->whereHas('vendor', function ($q) {
                $q->where('pass_type_id', $this->pass_type_id);
            })->get();
        $usedCount = $diningPassUsage->count();
        $useLimit = $this->passType->usage_limit;

        return $useLimit - $usedCount;
    }

    /**
     * Find the ids of each experience vendor where the pass has not been used
     *
     * @return Array
     */
    public function getExpVendorIdsRemaining()
    {
        $expPassUsage = $this->passUsages()
            ->whereHas('vendor', function ($q) {
                $q->where('pass_type_id', $this->pass_type_id);
            })->get();
        //**double check if there will every be experience vendors that allow more than one use per pass
        //$usedCount = $expPassUsage->count();
        // $usePerVendor = $this->pass_type->use_per_vendor; 
        $vendorIdsUsed = [];
        foreach ($expPassUsage as $expPassUsed)
        {
            if (!in_array($expPassUsed->vendor_id, $vendorIdsUsed))
                $vendorIdsUsed[] = $expPassUsed->vendor_id;
        }

        $vendorIds = \DB::table('vendors')
            ->where('pass_type_id', $this->pass_type_id)
            ->distinct()
            ->pluck('id');

        $vendorIdsUnused = [];
        foreach ($vendorIds as $vendorId)
        {
            if (!in_array($vendorId, $vendorIdsUsed))
                $vendorIdsUnused[] = $vendorId;
        }

        return $vendorIdsUnused;
    }

    public function getWithdrawnVendorCount()
    {
        $expPassUsage = $this->passType->vendors()->where('pass_type_id', $this->pass_type_id)->where('is_withdrawn', true)->where('withdrawal_dt', '<=', Carbon::now())->get();

        return $expPassUsage->count();
    }

}
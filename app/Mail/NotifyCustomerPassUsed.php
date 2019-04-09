<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\PassUsage;
use App\PassPurchase;
use App\Vendor;
use App\VendorLocation;

class NotifyCustomerPassUsed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The PassUsage instance.
     *
     * @var PassUsage
     */
    public $passUsage;

    public $qty; //Quantity of passes used
    public $confCode; //confirmation code for email

    /**
     * The Vendor instance.
     *
     * @var Vendor
     */
    public $vendor;

    /**
     * The VendorLocation instance.
     *
     * @var VendorLocation
     */
     public $location;

    /**
     * The PassPurchase instance.
     *
     * @var PassPurchase
     */
    public $passPurchase;

    public $customerName;
    public $customerEmail;
    public $regionCode;
    public $regionLogo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PassUsage $passUsage, $qty, $confCode)
    {
        $this->passUsage = $passUsage;
        $this->qty = $qty;
        $this->confCode = $confCode;
        $this->passPurchase = $passUsage->passPurchase;
        $this->vendor = $passUsage->vendor;
        $this->location = $passUsage->location;
        $this->customerName = $passUsage->passPurchase->user->name;
        $this->customerEmail = $passUsage->passPurchase->user->email;
        $this->regionCode = $passUsage->passPurchase->passType->region->code;
        $this->regionLogo = $passUsage->passPurchase->passType->region->logo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.customers.pass_used')->subject('Tri-fun pass used');
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //exclude webhooks
        'webhook/shopify/order_paid',
        'webhook/shopify/uninstall',
        'webhook/shopify/customer_update',
        'webhook/shopify/gdpr/customer-redact',
        'webhook/shopify/gdpr/shop-redact',
        'webhook/shopify/gdpr/customer-data',
        'sms/process_callback',
        'sms/process_reply',
        'sms/signup/confirm',
        'sms/signup'
    ];
}

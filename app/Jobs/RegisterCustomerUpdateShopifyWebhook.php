<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisterCustomerUpdateShopifyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
    * @var string
    */
    public $domain;

    /**
    * @var string
    */
    public $token;

    /**
    * @var \App\Store
    */
    public $store;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct($domain, $token, \App\Store $store)
    {
        $this->domain = $domain;
        $this->token = $token;
        $this->store = $store;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {

        $shopify = \Shopify::retrieve($this->domain, $this->token);

        // Get the current orders paid webhooks
        $customerUpdateWebhook = array_get($shopify->get('webhooks', [
            'topic' => 'customers/update',
            'limit' => 250,
            'fields' => 'id,address'
        ]), 'webhooks', []);

        // Check if the orders paid webhook has already been registered
        if(collect($customerUpdateWebhook)->isEmpty()) {
            $shopify->create('webhooks', [
                'webhook' => [
                    'topic' => 'customers/update',
                    'address' => env('APP_URL') . "webhook/shopify/customer_update",
                    'format' => 'json'
                ]
            ]);
        }

    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassUsages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pass_purchase_id');
            $table->unsignedInteger('vendor_id');
            $table->integer('vendor_location_id');
            $table->timestamp('redeemed_at')->useCurrent();
            $table->foreign('pass_purchase_id')->references('id')->on('pass_purchases');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pass_usages');
    }
}

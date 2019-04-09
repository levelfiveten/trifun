<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id');
            $table->string('name');
            $table->string('address1', 40);
            $table->string('address2', 40)->nullable();
            $table->string('city', 35);
            $table->string('state', 20);
            $table->string('zipcode', 20);
            $table->string('country', 50)->default('US');
            $table->timestamps();
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
        Schema::dropIfExists('vendor_locations');
    }
}

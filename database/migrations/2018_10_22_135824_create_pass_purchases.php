<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('pass_type_id');
            $table->bigInteger('order_number');
            $table->boolean('is_redeemed')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pass_type_id')->references('id')->on('pass_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pass_purchases');
    }
}

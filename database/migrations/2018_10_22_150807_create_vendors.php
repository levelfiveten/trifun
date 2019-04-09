<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pass_type_id');            
            $table->string('name');
            $table->string('name_old')->nullable();
            $table->string('email')->nullable();
            $table->text('redeem_txt');
            $table->text('offer_desc')->nullable();
            $table->string('pass_code');  
            $table->integer('max_pass_use')->nullable();
            $table->boolean('is_withdrawn')->default(0);
            $table->datetime('withdrawal_dt')->nullable();
            $table->timestamps();
            $table->foreign('pass_type_id')->references('id')->on('pass_types');
            $table->unique(['pass_type_id', 'name']);       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('region_id');
            $table->enum('type', ['experience','dining','special']);
            $table->string('name');
            $table->integer('days_valid')->nullable();
            $table->integer('use_per_vendor')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->string('logo')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pass_types');
    }
}

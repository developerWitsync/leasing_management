<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpectedLifeOfAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expected_useful_life_of_asset', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('years');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expected_useful_life_of_asset');
    }
}

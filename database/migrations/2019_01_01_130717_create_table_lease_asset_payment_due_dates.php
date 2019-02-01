<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaseAssetPaymentDueDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_asset_payment_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->unsignedInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('lease_assets_payments')->onDelete('cascade');

            $table->date('date');

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
        Schema::dropIfExists('lease_asset_payment_dates');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistoricalPresentValueOfLeaseLiability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_carrying_amount_annexure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->date('date');
            $table->unsignedInteger('days_diff')->comment('days diff from the previous payment date.');
            $table->unsignedInteger('year');
            $table->decimal('payment_amount', 30, 2);
            $table->decimal('present_value_of_lease_payment', 30, 2);
            $table->decimal('historical_present_value_of_lease_payment', 30, 2);
            $table->decimal('historical_depreciation', 30, 2);
            $table->decimal('historical_accumulated_depreciation', 30,2);
            $table->decimal('carrying_value_of_lease_asset', 30, 4);

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
        Schema::dropIfExists('historical_carrying_amount_annexure');
    }
}

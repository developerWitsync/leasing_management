<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnHistoricalPresentValueOfLeaseLiabilityToLeaseAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->text('historical_present_value_of_lease_liability')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->dropColumn('historical_present_value_of_lease_liability');
        });
    }
}

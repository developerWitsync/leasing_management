<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->unsignedDecimal('lease_liablity_value', 12, 2)->nullable()->after('total_payments');
            $table->unsignedDecimal('value_of_lease_asset', 12, 2)->nullable()->after('lease_liablity_value');
            $table->unsignedDecimal('impairment_test_value', 12, 2)->nullable()->after('value_of_lease_asset');
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
            //
        });
    }
}

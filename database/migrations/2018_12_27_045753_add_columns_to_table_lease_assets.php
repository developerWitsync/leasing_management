<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTableLeaseAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->string('other_details')->after('name')->nullable();
            $table->unsignedInteger('country_id')->nullable()->after('other_details');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('location')->nullable()->after('country_id');

            $table->unsignedInteger('specific_use')->nullable()->after('location');
            $table->foreign('specific_use')->references('id')->on('lease_asset_use_master')->onDelete('cascade');

            $table->string('use_of_asset')->nullable()->after('specific_use');

            $table->unsignedInteger('expected_life')->nullable()->after('use_of_asset');
            $table->foreign('expected_life')->references('id')->on('expected_useful_life_of_asset')->onDelete('cascade');

            $table->date('lease_start_date')->nullable()->after('expected_life');

            $table->unsignedInteger('lease_free_period')->comment('holds the number of days only.')->after('lease_start_date');

            $table->date('accural_period')->nullable()->comment('should be lease_start_date + lease_free_period')->after('lease_free_period');
            $table->date('lease_end_date')->nullable()->after('accural_period');
            $table->string('lease_term')->nullable()->after('lease_end_date');

            $table->unsignedInteger('accounting_treatment')->nullable()->after('lease_term');
            $table->foreign('accounting_treatment')->references('id')->on('lease_accounting_treatment')->onDelete('cascade');
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

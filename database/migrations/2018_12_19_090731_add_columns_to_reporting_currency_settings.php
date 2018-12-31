<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToReportingCurrencySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reporting_currency_settings', function (Blueprint $table) {
            $table->enum('internal_same_as_statutory_reporting', ['yes', 'no'])->nullable()->after('currency_for_lease_reports');
            $table->enum('lease_report_same_as_statutory_reporting', ['1', '2', '3'])->nullable()->after('currency_for_lease_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reporting_currency_settings', function (Blueprint $table) {
            $table->dropColumn('internal_same_as_statutory_reporting');
            $table->dropColumn('lease_report_same_as_statutory_reporting');
        });
    }
}

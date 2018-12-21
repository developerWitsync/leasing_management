<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsForeignTransactionInvolvedToReportingCurrencySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reporting_currency_settings', function (Blueprint $table) {
            $table->enum('is_foreign_transaction_involved', ['yes', 'no'])->default('no')->after('internal_same_as_statutory_reporting')->comment('Whether the foreign currency transaction involved');
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
            $table->dropColumn('is_foreign_transaction_involved');
        });
    }
}

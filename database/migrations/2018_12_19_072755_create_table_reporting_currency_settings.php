<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReportingCurrencySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporting_currency_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id', 'reporting_currency_bu_ac_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('statutory_financial_reporting_currency');
            $table->string('internal_company_financial_reporting_currency');
            $table->string('currency_for_lease_reports');
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
        Schema::dropIfExists('reporting_currency_settings');
    }
}

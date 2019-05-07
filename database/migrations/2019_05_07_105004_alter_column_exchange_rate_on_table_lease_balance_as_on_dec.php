<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnExchangeRateOnTableLeaseBalanceAsOnDec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `lease_balance_as_on_dec` CHANGE `exchange_rate` `exchange_rate` TEXT NOT NULL COMMENT \'Exchange Rates\'; ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

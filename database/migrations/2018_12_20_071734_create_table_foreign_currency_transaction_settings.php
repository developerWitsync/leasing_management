<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForeignCurrencyTransactionSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreign_currency_transaction_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id', 'foreign_currency_transaction_ac_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('foreign_exchange_currency');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->decimal('exchange_rate');
            $table->string('base_currency');
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
        Schema::dropIfExists('foreign_currency_transaction_settings');
    }
}

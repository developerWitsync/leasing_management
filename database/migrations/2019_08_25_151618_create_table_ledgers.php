<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLedgers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('ledger_level');
            $table->integer('category_id');
            $table->enum('type', [
              'lease_asset',
              'lease_liability',
              'lease_interest',
              'lease_depreciation',
              'lease_accumulated_depreciation',
              'lease_expense'
            ]);
            $table->string('account_name');
            $table->string('account_code');
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
        Schema::dropIfExists('ledgers');
    }
}

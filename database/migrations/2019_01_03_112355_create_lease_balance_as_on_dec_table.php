<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseBalanceAsOnDecTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_balance_as_on_dec', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('reporting_currency', ['1', '2', '3'])->comment('1 => Statutory Currency, 2 => Internal Reporting Currency, 3 => Lease Reporting Currency')->nullable();
            $table->decimal('exchange_rate', 12, 12)->comment('Exchange Rates');
            $table->string('reporting_currency_selected')->comment('Value of the reporting currency that has been selected.')->nullable();
            $table->unsignedInteger('carrying_amount')->nullable();
            $table->unsignedInteger('liability_balance')->nullable();
            $table->unsignedInteger('prepaid_lease_payment_balance')->nullable();
            $table->unsignedInteger('accrued_lease_payment_balance')->nullable();
            $table->unsignedInteger('outstanding_lease_payment_balance')->nullable();
            $table->unsignedInteger('any_provision_for_onerous_lease')->nullable();
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
        Schema::dropIfExists('lease_balance_as_on_dec');
    }
}

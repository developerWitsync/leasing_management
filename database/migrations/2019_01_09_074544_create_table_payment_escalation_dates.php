<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentEscalationDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_escalation_dates', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('payment_id');

            $table->foreign('payment_id')->references('id')->on('lease_assets_payments')->onDelete('cascade');

            $table->unsignedInteger('escalation_year');

            $table->unsignedInteger('escalation_month');

            $table->enum('percentage_or_amount_based', ['percentage', 'amount']);

            $table->decimal('value_escalated', 12, 2);

            $table->decimal('total_amount_payable', 12, 2);

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
        Schema::dropIfExists('payment_escalation_dates');
    }
}

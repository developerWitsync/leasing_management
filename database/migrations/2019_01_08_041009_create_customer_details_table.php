<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_incentive_id');
            $table->foreign('lease_incentive_id')->references('id')->on('lease_incentives')->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->string('description')->nullable();
            $table->date('incentive_date')->nullable();
            $table->string('currency_id')->nullable();
             $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('exchange_rate', 12, 2)->nullable();
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
        Schema::dropIfExists('customer_details');
    }
}

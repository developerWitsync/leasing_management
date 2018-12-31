<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaseAssetsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_assets_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->string('name');

            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('lease_payments_components')->onDelete('cascade');

            $table->unsignedInteger('nature');
            $table->foreign('nature')->references('id')->on('lease_asset_payments_nature')->onDelete('cascade');

            $table->string('variable_basis');

            $table->enum('variable_amount_determinable', ['yes', 'no'])->default('no');

            $table->text('description');

            $table->unsignedInteger('payment_interval');
            $table->foreign('payment_interval')->references('id')->on('lease_payments_frequency')->onDelete('cascade');

            $table->unsignedInteger('payout_time');
            $table->foreign('payout_time')->references('id')->on('lease_payment_interval')->onDelete('cascade');

            $table->date('first_payment_start_date');

            $table->date('last_payment_end_date');

            $table->string('payment_currency');

            $table->enum('using_lease_payment', ['1', '2'])->comment('1 => Current Lease Payment as on Jan 01, 2019, 2=> Initial Lease Payment as on First Lease Start');

            $table->decimal('payment_per_interval_per_unit',12,2);

            $table->decimal('total_amount_per_interval', 12, 2);

            $table->text('attachment')->nullable();

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
        Schema::dropIfExists('lease_assets_payments');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentEscalation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_escalation', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');

            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->unsignedInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('lease_assets_payments')->onDelete('cascade');

            $table->enum('is_escalation_applicable', ['yes', 'no']);

            $table->date('effective_from')->nullable();

            $table->unsignedInteger('escalation_basis')->nullable();
            $table->foreign('escalation_basis','esca_basis_fo_id')->references('id')->on('contract_escalation_basis')->onDelete('cascade');

            $table->unsignedInteger('escalation_rate_type')->nullable();
            $table->foreign('escalation_rate_type','esca_rate_type_fo_id')->references('id')->on('rate_types')->onDelete('cascade');

            $table->enum('is_escalation_applied_annually_consistently', ['yes', 'no'])->nullable();

            $table->decimal('fixed_rate', 12,2)->nullable();

            $table->decimal('current_variable_rate', 12,2)->nullable();

            $table->decimal('total_escalation_rate', 12,2)->nullable();

            $table->string('amount_based_currency')->nullable();

            $table->decimal('escalated_amount', 12,2)->nullable();

            $table->string('escalation_currency')->nullable();

            $table->decimal('total_undiscounted_lease_payment_amount', 12,2)->nullable();

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
        Schema::dropIfExists('payment_escalation');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidualValueGuranteeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_residual_value_gurantee', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->string('any_residual_value_gurantee');
            $table->unsignedInteger('lease_payemnt_nature_id');
            $table->foreign('lease_payemnt_nature_id')->references('id')->on('lease_asset_payments_nature')->onDelete('cascade');
            $table->string('amount_determinable');
            $table->string('foreign_currency_id');
            $table->foreign('foreign_currency_id')->references('id')->on('foreign_currency_transaction_settings')->onDelete('cascade');
            $table->string('no_of_unit_lease_asset');
            $table->string('residual_gurantee_value');
            $table->string('total_residual_gurantee_value');
            $table->string('other_desc');
            $table->string('residual_file');
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
        Schema::dropIfExists('lease_residual_value_gurantee');
    }
}

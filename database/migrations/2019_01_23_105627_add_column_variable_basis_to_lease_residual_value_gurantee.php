<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVariableBasisToLeaseResidualValueGurantee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_residual_value_gurantee', function (Blueprint $table) {
            $table->unsignedInteger('variable_basis_id')->after('lease_payemnt_nature_id')->nullable();
            $table->foreign('variable_basis_id', 'variable_basis_fo_index')->references('id')->on('lease_payments_basis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_residual_value_gurantee', function (Blueprint $table) {
            $table->dropColumn('variable_basis_id');
        });
    }
}

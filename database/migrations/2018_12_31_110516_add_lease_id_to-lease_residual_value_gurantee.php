<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaseIdToLeaseResidualValueGurantee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('lease_residual_value_gurantee', function (Blueprint $table) {
            $table->unsignedInteger('lease_id')->after('id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
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
            $table->dropColumn('lease_id');
            
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPvCalculusToLeaseHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_history', function (Blueprint $table) {
            $table->json('pv_calculus')->after('payment_anxure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_history', function (Blueprint $table) {
            $table->dropColumn('pv_calculus');
        });
    }
}

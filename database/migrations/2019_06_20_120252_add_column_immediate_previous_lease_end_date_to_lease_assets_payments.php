<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImmediatePreviousLeaseEndDateToLeaseAssetsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->date('last_lease_payment_interval_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->dropColumn('last_lease_payment_interval_end_date');
        });
    }
}

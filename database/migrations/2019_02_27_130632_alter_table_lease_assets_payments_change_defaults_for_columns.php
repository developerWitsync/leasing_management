<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeaseAssetsPaymentsChangeDefaultsForColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->dropColumn('payment_per_interval_per_unit');
            $table->dropColumn('total_amount_per_interval');
        });

        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->decimal('payment_per_interval_per_unit', 12,2)->nullable()->after('payment_currency');
            $table->decimal('total_amount_per_interval', 12,2)->nullable()->after('payment_currency');
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
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTotalPaymentAmountToLeaseAssetPaymentDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_asset_payment_dates', function (Blueprint $table) {
            $table->decimal('total_payment_amount', 12, 2)->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_asset_payment_dates', function (Blueprint $table) {
            $table->dropColumn('total_payment_amount');
        });
    }
}

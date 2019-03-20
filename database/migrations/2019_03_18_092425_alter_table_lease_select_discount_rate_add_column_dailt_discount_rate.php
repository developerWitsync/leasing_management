<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeaseSelectDiscountRateAddColumnDailtDiscountRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_select_discount_rate', function (Blueprint $table) {
            $table->decimal('daily_discount_rate', 12, 8)->after('discount_rate_to_use');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_select_discount_rate', function (Blueprint $table) {
            $table->dropColumn('daily_discount_rate');
        });
    }
}

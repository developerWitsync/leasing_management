<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUndiscountedValueToLeaseAssetPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->text('undiscounted_value')->nullable();
            $table->text('present_value')->nullable();
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
            $table->dropColumn('undiscounted_value');
            $table->dropColumn('present_value');
        });
    }
}

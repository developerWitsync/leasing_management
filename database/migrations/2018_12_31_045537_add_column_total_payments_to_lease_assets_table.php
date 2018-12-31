<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTotalPaymentsToLeaseAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->unsignedInteger('total_payments')->nullable()->default(0)->after('is_details_completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->dropColumn('total_payments');
        });
    }
}

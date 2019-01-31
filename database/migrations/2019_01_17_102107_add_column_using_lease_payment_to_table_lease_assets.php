<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUsingLeasePaymentToTableLeaseAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->enum('using_lease_payment', ['1', '2'])
                ->comment('1 => Current Lease Payment as on Jan 01, 2019, 2=> Initial Lease Payment as on First Lease Start')
                ->nullable();
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
            $table->dropColumn('using_lease_payment');
        });
    }
}

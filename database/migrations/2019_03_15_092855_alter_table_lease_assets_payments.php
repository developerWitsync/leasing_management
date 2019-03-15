<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeaseAssetsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            DB::statement('ALTER TABLE `lease_assets_payments` CHANGE `payment_interval` `payment_interval` INT(10) UNSIGNED NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `lease_assets_payments` CHANGE `payout_time` `payout_time` INT(10) UNSIGNED NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `lease_assets_payments` CHANGE `first_payment_start_date` `first_payment_start_date` DATE NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `lease_assets_payments` CHANGE `last_payment_end_date` `last_payment_end_date` DATE NULL DEFAULT NULL');
            DB::statement('ALTER TABLE `lease_assets_payments` CHANGE `payment_currency` `payment_currency` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChargeToPlToInterestAndDeprecition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interest_and_depreciation', function (Blueprint $table) {
            $table->text('charge_to_pl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interest_and_depreciation', function (Blueprint $table) {
            $table->dropColumn('charge_to_pl');
        });
    }
}

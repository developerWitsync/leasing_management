<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPurposeToInterestAndDepreciation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interest_and_depreciation', function (Blueprint $table) {
            $table->unsignedInteger('modify_id')->after('asset_id')->nullable();
            $table->foreign('modify_id')->references('id')->on('modify_lease_application')->onDelete('cascade');

            $table->text('discount_rate')->nullable();
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
            $table->dropColumn('modify_id');
            $table->dropColumn('discount_rate');
        });
    }
}

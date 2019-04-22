<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChangeToInterestAndDepreciation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interest_and_depreciation', function (Blueprint $table) {
            $table->unsignedDecimal('change', 12, 2)->nullable();
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
            $table->dropColumn('change');
        });
    }
}

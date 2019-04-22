<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForDepreciationCalculations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interest_and_depreciation', function (Blueprint $table) {
            $table->decimal('value_of_lease_asset', 12, 2);
            $table->decimal('depreciation', 12, 2);
            $table->decimal('accumulated_depreciation', 12, 2);
            $table->decimal('carrying_value_of_lease_asset', 12, 2);
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
            $table->dropColumn('value_of_lease_asset');
            $table->dropColumn('depreciation');
            $table->dropColumn('accumulated_depreciation');
            $table->dropColumn('carrying_value_of_lease_asset');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReasonToTableLeaseSelectLowValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_select_low_value', function (Blueprint $table) {
             $table->text('reason')->nullable()->after('is_classify_under_low_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_select_low_value', function (Blueprint $table) {
             $table->dropColumn('reason');
        });
    }
}

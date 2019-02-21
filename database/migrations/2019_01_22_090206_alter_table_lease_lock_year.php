<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeaseLockYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_lock_year', function (Blueprint $table) {
            $table->dropColumn('audit_year1_ended_on');
            $table->dropColumn('audit_year2_ended_on');
            $table->date('start_date');
            $table->date('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_lock_year', function (Blueprint $table) {
            //
        });
    }
}

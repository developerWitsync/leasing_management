<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsCompletedToLeaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease', function (Blueprint $table) {
            $table->tinyInteger('is_completed')->after('escalation_clause_applicable')->default(0)->comment(' 0 => lease has not been completed yet, 1 => lease has been completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease', function (Blueprint $table) {
            $table->dropColumn('is_completed');
        });
    }
}

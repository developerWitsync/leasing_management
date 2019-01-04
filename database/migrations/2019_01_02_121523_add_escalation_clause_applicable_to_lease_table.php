<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEscalationClauseApplicableToLeaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease', function (Blueprint $table) {
            $table->enum('escalation_clause_applicable', ['yes', 'no'])->default('no')->after('total_assets');
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
            $table->dropColumn('escalation_clause_applicable');
        });
    }
}

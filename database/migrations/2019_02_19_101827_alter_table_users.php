<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('industry_type');
            $table->dropColumn('legal_status');
            $table->dropColumn('country');
            $table->dropColumn('gender');
            $table->dropColumn('annual_reporting_period');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->enum('gender', ['1', '2', '3'])->comment("1 => Male, 2 => Female, 3 => Don't want to disclose");
            $table->string('certificates');
            $table->string('account_id')->after('id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('state');
            $table->dropColumn('certificates');
            $table->dropColumn('account_id');
        });
    }
}

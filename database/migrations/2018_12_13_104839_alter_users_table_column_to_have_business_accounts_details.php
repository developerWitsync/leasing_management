<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableColumnToHaveBusinessAccountsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function(Blueprint $table)
        {
//            $table->dropColumn('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('authorised_person_name')->nullable();
            $table->integer('country')->nullable()->after('authorised_person_name');
            $table->integer('legal_status')->nullable()->after('authorised_person_name');
            $table->string('applicable_gaap')->nullable()->after('authorised_person_name');
            $table->integer('industry_type')->nullable()->after('authorised_person_name');
            $table->string('legal_entity_name')->nullable()->after('authorised_person_name');
            $table->date('authorised_person_dob')->nullable()->after('authorised_person_name');
            $table->enum('gender', ['1', '2'])->comment("1 => Male, 2 => Female")->before('created_at');
            $table->string('authorised_person_designation')->nullable()->after('authorised_person_name');
            $table->string('username')->nullable()->after('email');
            $table->string('phone')->nullable()->after('email');
            $table->string('annual_reporting_period')->nullable()->before('created_at');
            $table->integer('currency')->after('country');
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
            //
        });
    }
}

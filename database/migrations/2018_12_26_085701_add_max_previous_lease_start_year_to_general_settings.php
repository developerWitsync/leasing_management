<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxPreviousLeaseStartYearToGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
<<<<<<< HEAD
           $table->string('max_previous_lease_start_year',50)
           ->after('date_of_initial_application_earlier_date')
           ->nullable();
=======
           $table->string('max_previous_lease_start_year',50)->after('date_of_initial_application_earlier_date')->nullable();
           
>>>>>>> ef3ea1fa26771f8b1cd42908c92bfa1192179543
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function($table) {
            $table->dropColumn('max_previous_lease_start_year');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTableLeaseRenewalOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_renewal_option', function (Blueprint $table) {
            $table->text('renewal_option_not_available_reason')->nullable()->after('is_renewal_option_under_contract');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_renewal_option', function (Blueprint $table) {
            $table->dropColumn('renewal_option_not_available_reason');
        });
    }
}

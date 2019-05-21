<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBusinessAccountIdToLeaseModificationReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_modification_reasons', function (Blueprint $table) {
            $table->unsignedInteger('business_account_id')->after('id')->nullable();
            $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_modification_reasons', function (Blueprint $table) {
            $table->dropColumn('business_account_id');
        });
    }
}

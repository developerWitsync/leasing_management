<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnModificationIdToLeaseHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_history', function (Blueprint $table) {
            $table->unsignedInteger('modify_id')->after('lease_id')->nullable();
            $table->foreign('modify_id')->references('id')->on('modify_lease_application')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_history', function (Blueprint $table) {
            $table->dropColumn('modify_id');
        });
    }
}

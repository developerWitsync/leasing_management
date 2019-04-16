<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubsequentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->enum('subsequent_status', ['0', '1'])->default('0')->comment('0 => subsequent is not completed, 1 => subsequent has been done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets_payments', function (Blueprint $table) {
            $table->dropColumn('subsequent_status');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIncreaseDecreaseToLeaseAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->text('increase_decrease')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets', function (Blueprint $table) {
            $table->dropColumn('increase_decrease');
        });
    }
}

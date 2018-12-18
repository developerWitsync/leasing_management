<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeasesExcludedFromTransitionalValuation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases_excluded_from_transitional_valuation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->enum('value_for', ['lease_asset_level', 'lease_payment'])->default('lease_asset_level');
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leases_excluded_from_transitional_valuation');
    }
}

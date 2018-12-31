<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFairMarketValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fair_market_value', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('is_market_value_present', ['yes', 'no'])->nullable();
            $table->unsignedInteger('unit')->nullable();
             $table->unsignedInteger('total_units')->nullable();
            $table->string('source')->nullable();
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
        Schema::dropIfExists('fair_market_value');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInitialDirectCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_direct_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('initial_direct_cost_involved', ['yes', 'no'])->nullable();
            $table->string('currency')->nullable();
            $table->decimal('total_initial_direct_cost',12,2)->nullable();
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
         Schema::dropIfExists('initial_direct_cost');
    }
}

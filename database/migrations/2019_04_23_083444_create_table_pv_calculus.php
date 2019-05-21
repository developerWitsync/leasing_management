<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePvCalculus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv_calculus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->json('calculus');
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
        Schema::dropIfExists('pv_calculus');
    }
}

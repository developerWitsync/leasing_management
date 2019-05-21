<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEscalationConsistencyGap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalation_consistency_gap', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id', 'es_per_bu_id_es')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('years');
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
        Schema::dropIfExists('escalation_consistency_gap');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEscalationPercentageSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalation_percentage_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id', 'es_per_bu_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('number');
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
        Schema::dropIfExists('escalation_percentage_settings');
    }
}

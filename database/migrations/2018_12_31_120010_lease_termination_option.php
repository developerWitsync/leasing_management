<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeaseTerminationOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('lease_termination_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('lease_termination_option_available', ['yes', 'no'])->nullable();
            $table->enum('exercise_termination_option_available', ['yes', 'no'])->nullable();
             $table->enum('termination_penalty_applicable', ['yes', 'no'])->nullable();
            $table->date('lease_end_date')->nullable();
             $table->string('currency')->nullable();
             $table->decimal('termination_penalty', 12, 2)->nullable();
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
        Schema::dropIfExists('lease_termination_option');
    }
}

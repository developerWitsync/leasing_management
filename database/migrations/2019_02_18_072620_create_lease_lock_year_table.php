<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseLockYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_lock_year', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_account_id');
            $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('start_date')->nullable();
              $table->enum('status', ['0', '1'])->default('0')->nullable();
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
        Schema::dropIfExists('lease_lock_year');
    }
}

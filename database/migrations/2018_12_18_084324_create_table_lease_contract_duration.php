<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaseContractDuration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_contract_duration', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lower_limit')->comment('Number of Months Only')->default(0);
            $table->unsignedInteger('upper_limit')->comment('Number of Months Only, IF NULL Means can go from lower_limit and can be above that')->nullable();
            $table->string('month_range_description')->comment('Ex : 1 Month or Less');
            $table->string('title');
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
        Schema::dropIfExists('lease_contract_duration');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseRenewalOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_renewal_option', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('is_renewal_option_under_contract', ['yes', 'no'])->nullable();
            $table->enum('is_reasonable_certainity_option', ['yes', 'no'])->nullable();
            $table->date('expected_lease_end_Date');
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
        Schema::dropIfExists('lease_renewal_option');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_incentives', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->enum('is_any_lease_incentives_receivable', ['yes', 'no'])->nullable();
            $table->string('currency')->nullable();
            $table->unsignedInteger('total_lease_incentives')->nullable();
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
        Schema::dropIfExists('lease_incentives');
    }
}

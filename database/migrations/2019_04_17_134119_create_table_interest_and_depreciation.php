<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInterestAndDepreciation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_and_depreciation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->date('date');
            $table->unsignedInteger('number_of_days')->comment('days diff from the previous payment date.');
            $table->decimal('opening_lease_liability', 12, 2);
            $table->decimal('interest_expense', 12, 2);
            $table->decimal('lease_payment', 12, 2);
            $table->decimal('closing_lease_liability', 12, 2);
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
        Schema::dropIfExists('interest_and_depreciation');
    }
}

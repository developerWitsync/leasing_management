<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidualValueGuranteeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_residual_value_gurantee', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');

            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->string('any_residual_value_gurantee');
            $table->unsignedInteger('lease_payemnt_nature_id');
            $table->foreign('lease_payemnt_nature_id')->references('id')->on('lease_asset_payments_nature')->onDelete('cascade');
            $table->string('amount_determinable')->nullable();
            $table->string('currency');

            $table->unsignedInteger('similar_asset_items');

            $table->string('residual_gurantee_value')->nullable();
            
            $table->string('total_residual_gurantee_value')->nullable();
            $table->string('other_desc')->nullable();
            $table->string('attachment');
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
        Schema::dropIfExists('lease_residual_value_gurantee');
    }
}

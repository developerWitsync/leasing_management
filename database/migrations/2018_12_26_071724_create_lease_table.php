<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease', function (Blueprint $table) {
           $table->increments('id');
        $table->unsignedInteger('business_account_id');
        $table->foreign('business_account_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('lessor_name');
        $table->string('lease_type_id');
        $table->string('lease_contract_id');
        $table->string('lease_code');
        $table->string('file');
        $table->enum('status', ['0', '1','2'])->comment("0 => Incomplete, 1 => Completed But Not Filtered, 2 => Complete & Submit");
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
        Schema::table('lease', function (Blueprint $table) {
            //
        });
    }
}

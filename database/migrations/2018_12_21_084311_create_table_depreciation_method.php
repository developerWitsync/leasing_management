<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDepreciationMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciation_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->enum('is_default', ['0', '1'])->default('0')->comment('1 => Means the method is the default and will always presented to the users.');
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
        Schema::dropIfExists('depreciation_method');
    }
}

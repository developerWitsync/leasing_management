<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubscriptionPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('price')->nullable();
            $table->integer('available_leases')->nullable();
            $table->integer('available_users')->nullable();
            $table->enum('hosting_type', ['cloud', 'on-premise', 'both'])->nullable();
            $table->integer('validity')->comment('Validity in Days, in case of unlimited should be null')->nullable();
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
        Schema::dropIfExists('subscription_plans');
    }
}

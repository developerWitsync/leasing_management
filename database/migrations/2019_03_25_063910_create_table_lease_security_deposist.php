<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaseSecurityDeposist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_deposits', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('lease_id');
            $table->foreign('lease_id')->references('id')->on('lease')->onDelete('cascade');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');

            $table->enum('any_security_applicable', ['yes', 'no']);
            $table->enum('refundable_or_adjustable', ['1', '2'])->comment('1 => Refundable, 2 => Adjustable')->nullable();
            $table->date('payment_date_of_security_deposit')->nullable();
            $table->string('type_of_security_deposit')->nullable();
            $table->string('doc')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();

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
        Schema::dropIfExists('security_deposits');
    }
}

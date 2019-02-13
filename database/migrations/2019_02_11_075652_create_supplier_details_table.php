<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('initial_direct_cost_id');
            $table->foreign('initial_direct_cost_id', 'initial_fo_id')->references('id')->on('initial_direct_cost')->onDelete('cascade');

            $table->string('supplier_name');
            $table->text('direct_cost_description');
            $table->date('expense_date');
            $table->string('supplier_currency');
            $table->decimal('amount', 12, 4);
            $table->decimal('rate', 12, 4);
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
        Schema::dropIfExists('supplier_details');
    }
}

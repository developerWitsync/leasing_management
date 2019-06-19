<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaseExpenseAnnexureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_expense_annexure', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('lease_assets')->onDelete('cascade');
            $table->date('date');
            $table->decimal('opening_prepaid_payable_balance', 20, 2);
            $table->decimal('total_computed_lease_expense', 20, 2);
            $table->decimal('closing_prepaid_payable_balance', 20, 2);
            $table->json('payments_details');

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
        Schema::dropIfExists('lease_expense_annexure');
    }
}

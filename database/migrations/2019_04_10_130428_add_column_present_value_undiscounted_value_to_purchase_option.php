<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPresentValueUndiscountedValueToPurchaseOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_option', function (Blueprint $table) {
            $table->text('undiscounted_value')->nullable();
            $table->text('present_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_option', function (Blueprint $table) {
            $table->dropColumn('undiscounted_value');
            $table->dropColumn('present_value');
        });
    }
}

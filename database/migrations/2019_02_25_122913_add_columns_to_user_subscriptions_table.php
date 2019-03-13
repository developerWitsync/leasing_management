<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->unsignedDecimal('adjusted_amount', 12, 2)->comment('amount that has been adjusted from the previous subscription')->nullable();
            $table->decimal('credits_used', 12, 2)->comment('Credit amount that has been used.')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->dropColumn('adjusted_amount');
            $table->dropColumn('credits_used');
        });
    }
}

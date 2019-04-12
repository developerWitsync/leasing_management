<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInvoiceNumberToUserSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('user_id');
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
            $table->dropColumn('invoice_number');
        });
    }
}

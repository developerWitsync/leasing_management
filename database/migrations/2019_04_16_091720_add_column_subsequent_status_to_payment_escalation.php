<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubsequentStatusToPaymentEscalation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_escalation', function (Blueprint $table) {
            $table->enum('subsequent_status', ['0', '1'])->default('0')->comment('0 => not done, 1 => subsequent done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_escalation', function (Blueprint $table) {
            $table->dropColumn('subsequent_status');
        });
    }
}

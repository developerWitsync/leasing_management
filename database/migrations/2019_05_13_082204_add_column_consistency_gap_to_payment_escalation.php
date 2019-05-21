<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnConsistencyGapToPaymentEscalation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_escalation', function (Blueprint $table) {
            $table->unsignedInteger('consistency_gap')->nullable()->after('subsequent_status');
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
            $table->dropColumn('consistency_gap');
        });
    }
}

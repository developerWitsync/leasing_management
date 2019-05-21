<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class AlterTableEscalationPercentageSettingsChangeNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `escalation_percentage_settings` CHANGE `number` `number` FLOAT(10) UNSIGNED NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `escalation_percentage_settings` CHANGE `number` `number` FLOAT(10) UNSIGNED NOT NULL;');
    }
}

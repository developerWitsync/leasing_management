<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfterDeleteOnSupplierDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `supplier_details_AFTER_DELETE` AFTER DELETE ON `supplier_details`
             FOR EACH ROW BEGIN
                UPDATE initial_direct_cost s
                    SET s.total_initial_direct_cost = (select sum(`amount`) from `supplier_details` where `initial_direct_cost_id` = old.`initial_direct_cost_id`)
                WHERE s.id = old.initial_direct_cost_id;   
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `supplier_details_AFTER_DELETE`');
    }
}

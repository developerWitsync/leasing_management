<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewDiscountRateChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = 'database/sqls/discount_rate_chart_view.sql';
        try{
            DB::unprepared(file_get_contents($path));
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

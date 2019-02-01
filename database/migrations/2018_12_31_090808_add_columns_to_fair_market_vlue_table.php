<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToFairMarketVlueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fair_market_value', function (Blueprint $table) {
            $table->string('currency')->nullable()->after('is_market_value_present');
            $table->unsignedInteger('similar_asset_items')->nullable()->after('currency');
            $table->string('attachment')->nullable()->after('similar_asset_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fair_market_value', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('similar_asset_items');
            $table->dropColumn('attachment');
        });
    }
}

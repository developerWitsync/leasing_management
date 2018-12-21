<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDepreciationMethodToTableLeaseAssetsSubCategoriesSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_sub_categories_settings', function (Blueprint $table) {
            $table->unsignedInteger('depreciation_method_id')->after('category_id');
            $table->foreign('depreciation_method_id', 'lease_asset_dep_met_id')->references('id')->on('depreciation_method')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_assets_sub_categories_settings', function (Blueprint $table) {
            $table->dropColumn('depreciation_method_id');
        });
    }
}

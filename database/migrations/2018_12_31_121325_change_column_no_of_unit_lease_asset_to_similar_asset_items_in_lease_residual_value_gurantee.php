<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnNoOfUnitLeaseAssetToSimilarAssetItemsInLeaseResidualValueGurantee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('lease_residual_value_gurantee', function (Blueprint $table) {
        $table->renameColumn('no_of_unit_lease_asset','similar_asset_items');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lease_residual_value_gurantee', function (Blueprint $table){
              $table->dropColumn('similar_asset_items');
       });
    }
}

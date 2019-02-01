<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloumnToLeaseAssetsCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lease_assets_categories', function (Blueprint $table) {

           $table->enum('is_capitalized', ['0', '1'])->default('1')->after('title');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('lease_assets_categories', function (Blueprint $table) {
            //
        });
    }
}

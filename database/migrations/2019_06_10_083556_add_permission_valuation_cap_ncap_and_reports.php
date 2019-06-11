<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionValuationCapNcapAndReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            'name' => 'valuation_cap',
            'display_name' => 'Valuation CAP',
            'description' => 'User can access the Valuation CAP.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);


        DB::table('permissions')->insert([
            'name' => 'valuation_ncap',
            'display_name' => 'Valuation NCAP',
            'description' => 'User can access the Valuation NCAP.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('permissions')->insert([
            'name' => 'reports',
            'display_name' => 'Reports',
            'description' => 'User can access the Reports Section.',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->where('name = "valuation_cap"')->delete();
        DB::table('permissions')->where('name = "valuation_ncap"')->delete();
        DB::table('permissions')->where('name = "reports"')->delete();
    }
}

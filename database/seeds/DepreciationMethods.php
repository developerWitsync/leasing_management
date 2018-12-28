<?php

use Illuminate\Database\Seeder;

class DepreciationMethods extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depreciation_method')->insert([
            'title' => 'Straight Line Method',
            'is_default' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}

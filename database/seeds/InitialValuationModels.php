<?php

use Illuminate\Database\Seeder;

class InitialValuationModels extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('initial_valuation_model')->insert([
            'title' => 'Cost Model',
            'is_default' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}

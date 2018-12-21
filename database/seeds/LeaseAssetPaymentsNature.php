<?php

use Illuminate\Database\Seeder;

class LeaseAssetPaymentsNature extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $values = "Fixed Lease Payment, Variable Lease Payment";
    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('lease_asset_payments_nature')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

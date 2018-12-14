<?php

use Illuminate\Database\Seeder;

class UseOfLeaseAsset extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = "Own Use,Sub-Lease";

    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('lease_asset_use_master')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

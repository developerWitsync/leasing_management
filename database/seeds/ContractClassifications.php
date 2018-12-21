<?php

use Illuminate\Database\Seeder;

class ContractClassifications extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = "Single Lease Contract, Multiple Leases Contract, Single Lease & Non-Lease Contract,Multiple Leases & Non-Lease Contract";

    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('contract_classifications')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

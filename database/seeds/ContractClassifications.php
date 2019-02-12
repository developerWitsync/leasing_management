<?php

use Illuminate\Database\Seeder;

class ContractClassifications extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = [
        [
            'title' => 'Single Lease Contract',
            'status'    => '1'
        ],
        [
            'title' => 'Multiple Leases Contract',
            'status'    => '0'
        ],
        [
            'title' => 'Single Lease & Non-Lease Contract',
            'status'    => '1'
        ],
        [
            'title' => 'Multiple Leases & Non-Lease Contract',
            'status'    => '0'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->values as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('contract_classifications')->insert($value);
        }
    }


}

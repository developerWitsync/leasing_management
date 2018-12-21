<?php

use Illuminate\Database\Seeder;

class LeasesExcludedFromTransitionalValuation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $values = [
        [
            'value_for'   => 'lease_asset_level',
            'title' => 'Investment Property Using Fair Value Model',
            'status'    => '1'
        ],
        [
            'value_for'   => 'lease_asset_level',
            'title' => 'Low Value Asset Lease',
            'status'    => '1'
        ],
        [
            'value_for'   => 'lease_asset_level',
            'title' => 'Short Term Lease Contract',
            'status'    => '1'
        ],
        [
            'value_for'   => 'lease_payment',
            'title' => 'Variable Lease Payments',
            'status'    => '1'
        ],
        [
            'value_for'   => 'lease_payment',
            'title' => 'Ended on December 31, 2018',
            'status'    => '1'
        ]
    ];

    public function run()
    {
        foreach ($this->values as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('leases_excluded_from_transitional_valuation')->insert($value);
        }
    }
}

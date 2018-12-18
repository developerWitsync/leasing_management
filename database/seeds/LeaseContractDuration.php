<?php

use Illuminate\Database\Seeder;

class LeaseContractDuration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = [
        [
            'lower_limit' => 0,
            'upper_limit' => 1,
            'month_range_description'   => '1 Month or Less',
            'title' => 'Very Short Term Lease',
            'status'    => '1'
        ],
        [
            'lower_limit' => 1,
            'upper_limit' => 12,
            'month_range_description'   => 'Above 1 to 12 Months',
            'title' => 'Short Term Lease',
            'status'    => '1'
        ],
        [
            'lower_limit' => 12,
            'upper_limit' => null,
            'month_range_description'   => 'Above 12 Months',
            'title' => 'Long-Term Lease',
            'status'    => '1'
        ]
    ];
    public function run()
    {
        foreach ($this->values as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('lease_contract_duration')->insert($value);
        }
    }
}

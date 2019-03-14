<?php

use Illuminate\Database\Seeder;

class LeaseAccountingTreatment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $values = [
        [
            'upto_year'   => '2018',
            'title' => 'Operating Lease Accounting',
            'status'    => '1'
        ],
        [
            'upto_year'   => '2018',
            'title' => 'Finance Lease Accounting',
            'status'    => '1'
        ],
        [
            'upto_year'   => '2018',
            'title' => 'Previously Not Identified as Lease',
            'status'    => '1'
        ]
    ];

    public function run()
    {
        foreach ($this->values as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('reporting_periods')->insert($value);
        }
    }
}

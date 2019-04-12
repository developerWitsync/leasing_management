<?php

use Illuminate\Database\Seeder;

class ReportingPeriods extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = [
        [
            'title' => '01 January to 31 December'
        ],
        [
            'title' => '01 April to 31 March'
        ],
        [
            'title' => '01 July to 30 June'
        ],
        [
            'title' => '01 October to 30 September'
        ],
        [
            'title' => '01 February to 31 January'
        ],
        [
            'title' => '01 May to 31 April'
        ],
        [
            'title' => '01 August to 31 July'
        ],
        [
            'title' => '01 November to 31 October'
        ],
        [
            'title' => '01 March to 28 / 29 February'
        ],
        [
            'title' => '01 June to 31 May'
        ],
        [
            'title' => '01 September to 31 August'
        ],
        [
            'title' => '01 December to 30 November'
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

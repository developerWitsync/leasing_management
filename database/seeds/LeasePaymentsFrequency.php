<?php

use Illuminate\Database\Seeder;

class LeasePaymentsFrequency extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = 'One-Time, Monthly, Quarterly, Semi-Annualy, Annually';
    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('lease_payments_frequency')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class LeasePaymentsInterval extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = 'At Lease Interval Start, At Lease Interval End';
    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('lease_payment_interval')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}

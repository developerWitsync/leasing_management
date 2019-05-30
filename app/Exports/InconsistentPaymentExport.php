<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 30/5/19
 * Time: 1:10 PM
 */

namespace App\Exports;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class InconsistentPaymentExport implements FromCollection
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        array_unshift($this->data, ['Payment Dates', 'Payment Amount']);
        return new Collection($this->data);
    }

}
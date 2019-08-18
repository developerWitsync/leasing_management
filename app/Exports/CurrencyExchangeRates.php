<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 8/10/2019
 * Time: 5:05 PM
 */

namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CurrencyExchangeRates implements FromCollection
{
  public $data;
  public function __construct($data)
  {
    $this->data = $data;
  }

  public function collection()
  {
    array_unshift($this->data, ['Dates', 'Statutory Currency', 'Foreign Currency', 'Exchange Rate']);
    return new Collection($this->data);
  }
}
<?php
/**
 * Created by PhpStorm.
 * User: Deepali
 * Date: 8/11/2019
 * Time: 10:54 AM
 */

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;


class ExchangeRatesImport implements ToCollection
{
  use Importable;

  public function collection(Collection $rows)
  {
    Validator::make($rows->toArray(), [
      '0' => 'required|date',
      '1' => 'required',
      '2' => 'required',
      '3' => 'numeric'
    ])->validate();

    return $rows;
  }
}
<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 30/5/19
 * Time: 2:14 PM
 */

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;

class InconsistentPaymentDatesImports implements ToCollection
{
    use Importable;

    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '0' => 'required|date',
            '1' => 'numeric|nullable'
        ])->validate();

        return $rows;
    }
}
<?php

namespace App\Exports;

use App\InterestAndDepreciation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InterestAndDepreciationExport implements FromView
{
    private $asset_id;

    public function __construct($id)
    {
        $this->asset_id = $id;
    }

    public function view(): View
    {
        $interest_depreciation = InterestAndDepreciation::query()->where('asset_id', '=', $this->asset_id)->get();
        $interest_depreciation = collect($interest_depreciation)->groupBy('modify_id');

        return view('leasevaluation.partials._interest_and_depreciation', compact('interest_depreciation'));
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return InterestAndDepreciation::query()->where('asset_id', '=', $this->asset_id)->get();
    }
}

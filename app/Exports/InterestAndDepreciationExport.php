<?php

namespace App\Exports;

use App\ExchangeRates;
use App\InterestAndDepreciation;
use App\Lease;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InterestAndDepreciationExport implements FromView
{
  private $asset_id;

  private $currency;

  private $lease;

  public function __construct($id, $currency, Lease $lease)
  {
    $this->asset_id = $id;
    $this->currency = $currency;
    $this->lease = $lease;
  }

  /**
   * generates the excel for the interest and depreciation...
   * @return View
   */
  public function view(): View
  {
    $interest_depreciation = InterestAndDepreciation::query()->where('asset_id', '=', $this->asset_id)->get();
    $is_statutory = false;
    if ($this->currency == 'statutory_currency') {
      $is_statutory = true;
      $interest_depreciation = ExchangeRates::convertInterestDepreciation($interest_depreciation, $this->lease);
    }
    $last_item = collect($interest_depreciation)->last();
    $interest_depreciation = collect($interest_depreciation)->groupBy('modify_id');
    return view('leasevaluation.partials._interest_and_depreciation', compact(
        'interest_depreciation',
        'last_item',
        'is_statutory'
      )
    );
  }
}

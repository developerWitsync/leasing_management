<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExchangeRates extends Model
{
  protected $table = 'exchange_rates';

  protected $fillable = ['foreign_currency_id', 'date', 'rate', 'created_at', 'updated_at'];

  /**
   * modifies the interest and depreciation data for the statutory currency based upon the exchange rates...
   * @param $interest_depreciation
   * @param $lease
   * @return mixed
   */
  public static function convertInterestDepreciation($interest_depreciation, $lease)
  {
    if (!empty($interest_depreciation)) {
      $first_date = collect($interest_depreciation)->first();
      $last_date = collect($interest_depreciation)->last();
      $months = calculateMonthsDifference($first_date->date, $last_date->date);
      $currencies = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
      $statutory_currency = $currencies->statutory_financial_reporting_currency;
      $lease_contract_currency = $lease->lease_contract_id;
      //get all the exchange rates in between first date and last date and base currency should be lease_contract_currency
      $take_reciprocal = false;
      $foreign_currency_setting = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())
        ->where('base_currency', '=', $lease_contract_currency)
        ->where('foreign_exchange_currency', '=', $statutory_currency)
        ->first();
      if (is_null($foreign_currency_setting)) {
        $take_reciprocal = true;
        $foreign_currency_setting = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())
          ->where('base_currency', '=', $statutory_currency)
          ->where('foreign_exchange_currency', '=', $lease_contract_currency)
          ->first();
      }

      if (!is_null($foreign_currency_setting)) {
        $rates = ExchangeRates::query()->select('*');
        if ($take_reciprocal) {
          $rates = $rates->selectRaw('1/`rate` as `rate`');
        }
        $rates = $rates->where('foreign_currency_id', '=', $foreign_currency_setting->id)
          ->whereBetween('date', [$first_date->date, $last_date->date])->get()->toArray();

        $i = 1;
        $previous_closing_liability = 0;
        $previous_rate = 1;
        $depreciation = $previous_accumulated_depreciation =  $previous_carrying_value_of_lease_asset = 0;
        foreach ($interest_depreciation as $item => $value) {
          $rate = collect($rates)->where('date', '=', $value->date)->first();
          $rate = (!is_null($rate)) ? (float)$rate['rate'] : 1;
          $value->exchange_rate = $rate;
          if ($i == 1) {
            $value->opening_lease_liability = $rate * $value->opening_lease_liability;
            $value->realized_forex = 0;
          } else {
            $value->realized_forex = ($previous_rate - $rate) * $value->lease_payment;
            $value->realized_forex = round($value->realized_forex, 2);
            $value->opening_lease_liability = $previous_closing_liability;
          }
          $value->closing_lease_liability = $rate * $value->closing_lease_liability;
          $value->interest_expense = $rate * $value->interest_expense;
          $value->lease_payment = $rate * $value->lease_payment;
          $value->unrealized_forex = (float)$value->opening_lease_liability + (float)$value->interest_expense - (float)$value->lease_payment - (float)$value->closing_lease_liability - (float)$value->realized_forex;
          $value->unrealized_forex = round($value->unrealized_forex, 2);
          $value->unrealized_forex = ($value->unrealized_forex == 0)?0:$value->unrealized_forex;
          $value->value_of_lease_asset = $rate * $value->value_of_lease_asset;
          $value->change = $rate * $value->change;

          //depreciation calculations need to be done here....
          if($i == 1) {
            $depreciation = round($value->opening_lease_liability / $months, 2);
            $previous_carrying_value_of_lease_asset  = $value->value_of_lease_asset;
          }

          if(Carbon::parse($value->date)->isLastOfMonth()) {
            $value->depreciation = $depreciation;
            $value->accumulated_depreciation = $depreciation + $previous_accumulated_depreciation;
            $value->carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $depreciation;
            $previous_accumulated_depreciation = $value->accumulated_depreciation;
            $previous_carrying_value_of_lease_asset = $value->carrying_value_of_lease_asset;
          }

          $i = $i + 1;
          $previous_closing_liability = $value->closing_lease_liability;
          $previous_rate = $rate;
        }
      }
      return $interest_depreciation;
    }
  }
}

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
   * @throws \Exception
   */
  public static function convertInterestDepreciation($interest_depreciation, Lease $lease)
  {
    if (!empty($interest_depreciation)) {
      $asset = $lease->assets()->first();
      $first_date = collect($interest_depreciation)->first();
      $last_date = collect($interest_depreciation)->last();
      $months = calculateMonthsDifference($first_date->date, $asset->getLeaseEndDate($asset));
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
        $previous_modify_id = null;
        $initial_value_of_lease_asset = null;
        $add_change = false;
        foreach ($interest_depreciation as $item => $value) {
          $new_subsequent = false;
          if($previous_modify_id != $value->modify_id) {
            $new_subsequent = true;
          }

          $rate = collect($rates)->where('date', '=', $value->date)->first();
          $rate = (!is_null($rate)) ? (float)$rate['rate'] : 1;
          $value->exchange_rate = $rate;
          if ($i == 1) {
            $value->opening_lease_liability = round($rate * $value->opening_lease_liability, 2);
            $value->realized_forex = 0;
          } else {
            $value->realized_forex = ($previous_rate - $rate) * $value->lease_payment;
            $value->realized_forex = round($value->realized_forex, 2);
            $value->opening_lease_liability = round($previous_closing_liability, 2);
          }
          $value->closing_lease_liability = round($rate * $value->closing_lease_liability, 2);
          $value->interest_expense = round($rate * $value->interest_expense, 2);
          $value->lease_payment = round($rate * $value->lease_payment, 2);
          $value->unrealized_forex = (float)$value->opening_lease_liability + (float)$value->interest_expense - (float)$value->lease_payment - (float)$value->closing_lease_liability - (float)$value->realized_forex;
          $value->unrealized_forex = round($value->unrealized_forex, 2);
          $value->unrealized_forex = ($value->unrealized_forex == 0)?0:$value->unrealized_forex;
          $value->change = $rate * $value->change;
          if($new_subsequent) {
            $value->value_of_lease_asset = round($initial_value_of_lease_asset + $value->change, 2);
            //have to calculate the depreciation here again for the subsequent modification....
            $new_value_of_lease_asset = $value->value_of_lease_asset - $previous_accumulated_depreciation;
            $months = calculateMonthsDifference($value->date, $asset->getLeaseEndDate($asset));
            $depreciation = round($new_value_of_lease_asset / $months, 2);
            $add_change = true;
            $initial_value_of_lease_asset = round($value->value_of_lease_asset, 2);
          } else {
            $value->value_of_lease_asset = round($rate * $value->value_of_lease_asset, 2);
          }

          //depreciation calculations need to be done here....
          if($i == 1) {
            $depreciation = round($value->value_of_lease_asset / $months, 2);
            $previous_carrying_value_of_lease_asset  = round($value->value_of_lease_asset, 2);
            $initial_value_of_lease_asset = round($value->value_of_lease_asset, 2);
          }

          if(Carbon::parse($value->date)->isLastOfMonth()) {
            $value->depreciation = $depreciation;
            $value->accumulated_depreciation = round($depreciation + $previous_accumulated_depreciation, 2);
            if($add_change){
              $value->carrying_value_of_lease_asset = round($previous_carrying_value_of_lease_asset + $value->change - $depreciation, 2);
              $add_change = false;
            } else {
              $value->carrying_value_of_lease_asset = round($previous_carrying_value_of_lease_asset - $depreciation, 2);
            }

            $previous_accumulated_depreciation = round($value->accumulated_depreciation, 2);
            $previous_carrying_value_of_lease_asset = round($value->carrying_value_of_lease_asset, 2);
          }

          $i = $i + 1;
          $previous_closing_liability = round($value->closing_lease_liability, 2);
          $previous_rate = $rate;
          $previous_modify_id =  $value->modify_id;
        }
      }
      return $interest_depreciation;
    }
  }
}

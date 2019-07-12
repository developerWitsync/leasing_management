<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 07/01/19
 * Time: 10:30 AM
 */

namespace App\Http\Controllers\Lease;

use App\DismantlingCosts;
use App\GeneralSettings;
use App\HistoricalCarryingAmountAnnexure;
use App\Http\Controllers\Controller;
use App\InterestAndDepreciation;
use App\Lease;
use App\ModifyLeaseApplication;
use App\PaymentEscalationDates;
use App\PvCalculus;
use App\ContractClassifications;
use App\LeaseAssets;
use App\LeaseAssetPayments;
use App\FairMarketValue;
use App\LeaseResidualValue;
use App\LeaseTerminationOption;
use App\LeaseRenewableOption;
use App\PurchaseOption;
use App\LeaseDurationClassified;
use App\LeaseAssetPaymenetDueDate;
use App\PaymentEscalationDetails;
use App\LeaseSelectLowValue;
use App\LeaseSelectDiscountRate;
use App\LeaseBalanceAsOnDec;
use App\InitialDirectCost;
use App\LeaseIncentives;
use App\LeasePaymentInvoice;
use App\LeaseHistory;
use App\SupplierDetails;
use App\CustomerDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;
use Validator;

class ReviewSubmitController extends Controller
{
    private $current_step = 20;

    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.reviewsubmit.index', ['id' => $id]),
                    'title' => 'Review & Submit'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if ($lease) {

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->get();
                $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();

                //to get current step for steps form
                $current_step = $this->current_step;

                return view('lease.review-submit.index', compact(
                    'lease',
                    'assets',
                    'breadcrumbs',
                    'reporting_currency_settings',
                    'contract_classifications',
                    'reporting_foreign_currency_transaction_settings',
                    'current_step',
                    'subsequent_modify_required'
                ));

            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * generate and inserts the data for the interest and depreciation tab for the lease
     * @param Lease $lease
     * @param $modify_id
     * @return bool
     * @throws \Exception
     */
    private function generateDataForInterestAndDepreciationTab(Lease $lease, $modify_id)
    {
        if ($lease) {

            $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

            $asset = $lease->assets()->first();
            //$start_date = Carbon::parse($asset->accural_period);
            $start_date = Carbon::parse($asset->lease_start_date);

            //check for the subsequent modification here....
            $subsequent_modify_required = $lease->isSubsequentModification();
            if($subsequent_modify_required) {
                $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
            } else {
                if($settings->date_of_initial_application == 2){
                    $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date)->subYear(1);
                } else {
                    $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                }

                $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
            }

            $end_date = Carbon::parse($asset->getLeaseEndDate($asset));

            $start_year = $first_year = $base_date->format('Y');
            $start_month = $base_date->format('m');
            $end_year = $end_date->format('Y');

            $discount_rate = $asset->leaseSelectDiscountRate->daily_discount_rate;

            $months = [];
            for ($m = 1; $m <= 12; ++$m) {
                $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
            }

            $dates = [];

            $lease_payments = $asset->fetchAllPaymentsForAnnexure($asset, $base_date->format('Y-m-d'), $end_date->format('Y-m-d'));

            $is_payment_on_base_date = false;
            $base_date_payment = array_where($lease_payments, function($value) use ($base_date){
                if(Carbon::parse($base_date)->equalTo($value->date)){
                    return true;
                } else {
                    return false;
                }
            }) ;
            if(count($base_date_payment) > 0){
                $is_payment_on_base_date = true;
            }

            $check_date = $increase_or_decrease =  null;

            $previous_all_increase_or_decrease = 0;

            $set_to_zero = false;

            $previous_liability = $asset->lease_liablity_value;

            $charge_to_pl = $asset->charge_to_pl;

            if($subsequent_modify_required) {

                //fetch the dates for all the subsequents that happened in the same month and same year...
                $current_effective_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                $subsequents_effective_dates_in_same_month_and_year = ModifyLeaseApplication::query()
                    ->where('lease_id', '=', $lease->id)
                    ->whereRaw("MONTH(`effective_from`) = {$current_effective_date->format('m')} AND YEAR(`effective_from`) = {$current_effective_date->format('Y')}")
                    ->whereDate('effective_from', '<>', $current_effective_date)
                    ->pluck('effective_from')->toArray();

                $previous_increase_decrease = 0;
                if(count($subsequents_effective_dates_in_same_month_and_year)) {
                    $increase_or_decrease_from_current_month_and_current_year = InterestAndDepreciation::query()
                        ->where('asset_id', '=', $asset->id)
                        ->whereIn('date', $subsequents_effective_dates_in_same_month_and_year)
                        ->groupBy('date')
                        ->pluck('change')->toArray();

                    if(count($increase_or_decrease_from_current_month_and_current_year) > 0) {
                        $previous_increase_decrease = array_sum($increase_or_decrease_from_current_month_and_current_year);
                    }
                }

                $previous_depreciation_data = InterestAndDepreciation::query()
                    ->where('date', '<', $base_date->format('Y-m-d'))
                    ->where('asset_id', '=', $asset->id)
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                $one_day_before = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->subDay(1);
                //check a row exists on one day before in interest and depreciation
                $day_before_subsequent = InterestAndDepreciation::query()
                    ->where('date', '=', Carbon::parse($one_day_before))
                    ->where('asset_id', '=', $asset->id)
                    ->first();

                $value_of_lease_asset = $asset->value_of_lease_asset;

                //should be the sum of all the previous subsequents increase and decrease from
                //the date of the previous carrying value of lease asset till the current effective date
                $increase_or_decrease =  $asset->increase_decrease;

                $previous_all_increase_or_decrease = $previous_increase_decrease + $increase_or_decrease;

                if($day_before_subsequent) {
                    $dates[] = [
                        'asset_id' => $day_before_subsequent->asset_id,
                        'modify_id' => $day_before_subsequent->modify_id,
                        'date' => $day_before_subsequent->date,
                        'number_of_days' => $day_before_subsequent->number_of_days,
                        'discount_rate' => $day_before_subsequent->discount_rate,
                        'opening_lease_liability' => $day_before_subsequent->opening_lease_liability,
                        'interest_expense' => $day_before_subsequent->interest_expense,
                        'lease_payment' => $day_before_subsequent->lease_payment,
                        'closing_lease_liability' => $day_before_subsequent->closing_lease_liability,
                        'created_at' => $day_before_subsequent->created_at,
                        'updated_at' => $day_before_subsequent->updated_at,
                        'value_of_lease_asset' => $day_before_subsequent->value_of_lease_asset,
                        'depreciation' => $day_before_subsequent->depreciation,
                        'change' => $day_before_subsequent->change,
                        'accumulated_depreciation' => $day_before_subsequent->accumulated_depreciation,
                        'carrying_value_of_lease_asset' => $day_before_subsequent->carrying_value_of_lease_asset,
                        'charge_to_pl' => $day_before_subsequent->charge_to_pl
                    ];
                    $previous_carrying_value_of_lease_asset = $day_before_subsequent->carrying_value_of_lease_asset;
                    $previous_accumulated_depreciation = $day_before_subsequent->accumulated_depreciation;
                } else {
                    $days_diff = Carbon::parse($one_day_before)->diffInDays($previous_depreciation_data->date);
                    $interest_expense = calculateInterestExpense($previous_depreciation_data->closing_lease_liability, $previous_depreciation_data->discount_rate, $days_diff);
                    $dates[] = [
                        'asset_id' => $asset->id,
                        'modify_id' => $previous_depreciation_data->modify_id,
                        'date' => $one_day_before->format('Y-m-d'),
                        'number_of_days' => $days_diff,
                        'discount_rate' => $previous_depreciation_data->discount_rate,
                        'opening_lease_liability' => $previous_depreciation_data->closing_lease_liability,
                        'interest_expense' => $interest_expense,
                        'lease_payment' => 0,
                        'closing_lease_liability' => ($interest_expense + $previous_depreciation_data->closing_lease_liability - 0),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'value_of_lease_asset' => $previous_depreciation_data->value_of_lease_asset,
                        'depreciation' => $previous_depreciation_data->depreciation,
                        'change' => $previous_depreciation_data->change,
                        'accumulated_depreciation' => $previous_depreciation_data->accumulated_depreciation,
                        'carrying_value_of_lease_asset' => $previous_depreciation_data->carrying_value_of_lease_asset,
                        'charge_to_pl' => $previous_depreciation_data->charge_to_pl
                    ];
                    $previous_carrying_value_of_lease_asset = $previous_depreciation_data->carrying_value_of_lease_asset;
                    $previous_accumulated_depreciation = $previous_depreciation_data->accumulated_depreciation;
                }

                //need to set the values here that will be used on the next iteration
                $number_of_months = calculateMonthsDifference($base_date->format('Y-m-d'), $asset->getLeaseEndDate($asset));
                if($previous_carrying_value_of_lease_asset + $previous_all_increase_or_decrease > 0){
                    $previous_depreciation = ($previous_carrying_value_of_lease_asset + $previous_all_increase_or_decrease) / $number_of_months;
                } else {
                    $previous_depreciation = 0;
                    $previous_carrying_value_of_lease_asset = 0;
                    $set_to_zero = true;
                }

            } else {
                $previous_carrying_value_of_lease_asset = $asset->value_of_lease_asset;
                $previous_depreciation = calculateDepreciation($subsequent_modify_required, $asset, $previous_carrying_value_of_lease_asset);
                $previous_accumulated_depreciation = 0;
                //$previous_carrying_value_of_lease_asset = $previous_liability;
                $value_of_lease_asset = $previous_carrying_value_of_lease_asset;
            }

            while ($start_year <= $end_year) {
                foreach ($months as $key => $month) {

                    if($start_year == $first_year && $key < $start_month){
                        continue;
                    }

                    //apply condition for the lease start date
                    //condition to check the below condition should be when the start date is on or after the base date
                    if($start_date->greaterThanOrEqualTo($base_date)){
                        $current_month_and_year_last_day = Carbon::create($start_year, $key, '1')->lastOfMonth();
                        if($start_date->greaterThan($current_month_and_year_last_day)){
                            continue;
                        }
                    }

                    //filter the above array with same month as for the current date and same year as for the current year
                    $payment_dates = array_where($lease_payments, function($value, $index) use ($key, $start_year ,$check_date){
                        $date_month = Carbon::parse($value->date)->format('m');
                        $date_year = Carbon::parse($value->date)->format('Y');
                        return ($date_month == $key && $start_year == $date_year);
                    });

                    if($start_year == $first_year && $start_month == $key && !$is_payment_on_base_date && !Carbon::parse($base_date)->isLastOfMonth()){
                        $days_diff = Carbon::parse($base_date)->diffInDays($base_date);
                        $interest_expense = calculateInterestExpense($previous_liability, $discount_rate, $days_diff);
                        $dates[] = [
                            'asset_id' => $asset->id,
                            'modify_id' => $modify_id,
                            'date' => $base_date->format('Y-m-d'),
                            'number_of_days' => $days_diff,
                            'discount_rate' => $discount_rate,
                            'opening_lease_liability' => $previous_liability,
                            'interest_expense' => $interest_expense,
                            'lease_payment' => 0,
                            'closing_lease_liability' => ($interest_expense + $previous_liability - 0),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'value_of_lease_asset' => $value_of_lease_asset,
                            'depreciation' => $previous_depreciation,
                            'change' => $increase_or_decrease,
                            'accumulated_depreciation' => ($base_date->isLastOfMonth())? $previous_depreciation + $previous_accumulated_depreciation : $previous_accumulated_depreciation,
                            'carrying_value_of_lease_asset' => ($base_date->isLastOfMonth())? $previous_carrying_value_of_lease_asset + ($set_to_zero ? 0 : $previous_all_increase_or_decrease) : $previous_carrying_value_of_lease_asset,
                            'charge_to_pl' => $charge_to_pl
                        ];
                        $base_date = Carbon::parse($base_date);
                        $previous_liability = $interest_expense + $previous_liability - 0;
                        if(Carbon::parse($base_date)->isLastOfMonth()){
                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $previous_depreciation;
                            $increase_or_decrease = 0;
                            $previous_all_increase_or_decrease = 0;
                            $charge_to_pl = 0;
                        }

                        if($previous_carrying_value_of_lease_asset == 0){
                            $increase_or_decrease = 0;
                            $previous_all_increase_or_decrease = 0;
                            $charge_to_pl = 0;
                        }
                    }

                    foreach ($payment_dates as $payment_date) {
                        $days_diff = Carbon::parse($payment_date->date)->diffInDays($base_date);
                        $interest_expense = calculateInterestExpense($previous_liability, $discount_rate, $days_diff);

                        $dates[] = [
                            'asset_id' => $asset->id,
                            'modify_id' => $modify_id,
                            'date' => $payment_date->date,
                            'number_of_days' => $days_diff,
                            'discount_rate' => $discount_rate,
                            'opening_lease_liability' => $previous_liability,
                            'interest_expense' => $interest_expense,
                            'lease_payment' => (float)$payment_date->total_amount_payable,
                            'closing_lease_liability' => ($interest_expense + $previous_liability - (float)$payment_date->total_amount_payable),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'value_of_lease_asset' => $value_of_lease_asset,
                            'depreciation' => $previous_depreciation,
                            'change' => $increase_or_decrease,
                            'accumulated_depreciation' => (Carbon::parse($payment_date->date)->isLastOfMonth()) ? $previous_accumulated_depreciation + $previous_depreciation : $previous_accumulated_depreciation,
                            'carrying_value_of_lease_asset' => (Carbon::parse($payment_date->date)->isLastOfMonth()) ? $previous_carrying_value_of_lease_asset + ($set_to_zero ? 0 : $previous_all_increase_or_decrease) - $previous_depreciation : $previous_carrying_value_of_lease_asset,
                            'charge_to_pl' => $charge_to_pl
                        ];
                        $base_date = Carbon::parse($payment_date->date);
                        $previous_liability = $interest_expense + $previous_liability - (float)$payment_date->total_amount_payable;

                        if(Carbon::parse($payment_date->date)->isLastOfMonth()){
                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset + ($set_to_zero ? 0 : $increase_or_decrease) - $previous_depreciation;
                            $increase_or_decrease  = 0;
                            $previous_all_increase_or_decrease = 0;
                            $charge_to_pl = 0;
                        }

                        if($previous_carrying_value_of_lease_asset == 0){
                            $increase_or_decrease = 0;
                            $previous_all_increase_or_decrease = 0;
                            $charge_to_pl = 0;
                        }
                    }

                    if($end_date->format('Y') > $start_year){

                    } else if($end_date->format('Y') == $start_year && $end_date->format('m') >= $key) {

                    } else {
                        continue;
                    }

                    $current_date = Carbon::create($start_year, $key, '1')->lastOfMonth();
                    if(is_null($check_date) || !is_null($check_date) && Carbon::parse($check_date)->lessThan($current_date)){

                        //find from array if any payment exists on this date
                        $payment_on_date = collect($lease_payments)->where('date', '=', $current_date->format('Y-m-d'))->values();
                        if(count($payment_on_date) == 0){
                            $amount_payable = (count($payment_on_date) > 0) ? $payment_on_date[0]->total_amount_payable : 0;
                            $current_date = (count($payment_on_date) > 0) ? $payment_on_date[0]->date : $current_date->format('Y-m-d');
                            //need to append the last day of month as well...
                            $days_diff = Carbon::parse($current_date)->diffInDays($base_date);
                            $interest_expense = calculateInterestExpense($previous_liability, $discount_rate, $days_diff);

                            $dates[] = [
                                'asset_id' => $asset->id,
                                'modify_id' => $modify_id,
                                'date' => $current_date,
                                'number_of_days' => $days_diff,
                                'discount_rate' => $discount_rate,
                                'opening_lease_liability' => $previous_liability,
                                'interest_expense' => $interest_expense,
                                'lease_payment' => $amount_payable,
                                'closing_lease_liability' => ($interest_expense + $previous_liability - (float)$amount_payable),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'value_of_lease_asset' => $value_of_lease_asset,
                                'depreciation' => $previous_depreciation,
                                'change' => $increase_or_decrease,
                                'accumulated_depreciation' => $previous_depreciation + $previous_accumulated_depreciation,
                                'carrying_value_of_lease_asset' => round($previous_carrying_value_of_lease_asset + ($set_to_zero ? 0 : $previous_all_increase_or_decrease) - $previous_depreciation, 2),
                                'charge_to_pl' => $charge_to_pl
                            ];

                            $base_date = Carbon::parse($current_date);
                            $previous_liability = $interest_expense + $previous_liability - (float)$amount_payable;

                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset + ($set_to_zero ? 0 : $previous_all_increase_or_decrease) - $previous_depreciation;

                            $increase_or_decrease = 0;
                            $previous_all_increase_or_decrease = 0;
                            $charge_to_pl = 0;

                        }
                    }
                }
                $start_year = $start_year + 1;
            }

            //echo "<pre>"; print_r($dates); die();

            DB::transaction(function () use ($dates, $modify_id, $asset) {
                //insert the dates data into the interest and depreciation table for the lease id
                if(is_null($modify_id)){
                    //InterestAndDepreciation::query()->where('asset_id', '=', $asset->id)->delete();
                    DB::table('interest_and_depreciation')->where('asset_id', '=', $asset->id)->delete();
                } else {
                    //need to delete the interest and depreciations after the first date in $dates array..
                    if(!empty($dates)){
                        //InterestAndDepreciation::query()->where('date', '>=', $dates[0]['date'])->delete();
                        DB::table('interest_and_depreciation')->where('date', '>=', $dates[0]['date'])->delete();
                    }
                }

                DB::table('interest_and_depreciation')->insert($dates);
            });

            return true;
        } else {
            return false;
        }
    }

    /**
     * generate the lease expense annexure for the initial valuation only
     * Lease expense annexure will always be generated for the initial valuation only
     * @param $lease
     * @param $modify_id
     * @return bool
     */
        private function generateLeaseExpense($lease, $modify_id){
        try{
            if($lease){
                $asset = $lease->assets()->first();

                $start_date = Carbon::parse($asset->lease_start_date);

                //settings to be fetched for checking the Full Retrospective Approach
                $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

                //check for the subsequent modification here....
                $subsequent_modify_required = $lease->isSubsequentModification();
                if($subsequent_modify_required) {
                    $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                } else {
                    if($settings->date_of_initial_application == 2){
                        //this is the Full retrospective approach and we need to subtract 1 year from the base date in this case
                        $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date)->subYear(1);
                    } else {
                        //this is Modified Retrospective approach and the base date as it is will be used as the base date
                        $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                    }

                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                }

                $end_date = Carbon::parse($asset->getLeaseEndDate($asset));

                //create an array for all the months in a year so that those can be mapped with the annexure
                //not sure if we have to use this array
                $months = [];
                for ($m = 1; $m <= 12; ++$m) {
                    $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
                }

                //fetch all the lease asset payments with the payment date including the escalated amount if exists
                //including the residual options and Termination options as well
                //no need to include the purchase options for this annexure and hence that will not be fetched
                $lease_payments_initial = $asset->fetchAllPaymentsForLeaseExpenseAnnexure($asset, $base_date->format('Y-m-d'), $end_date->format('Y-m-d'));

                $lease_payments = collect($lease_payments_initial)->groupBy('date')->sortBy('date')->toArray();

                //fetch the lease asset payments from the database as well
                $asset_payments = $asset->payments;

                //create an array with all the dates that we will have at each row...
                $rows = [];
                $rows[] = $base_date->format('Y-m-d');
                foreach ($lease_payments as $date=>$lease_payment) {
                    $rows[] = $date;
                    //since we need to have a row at each month last date as well so check if the current date is the
                    //last of the month and if not then append one row with the date at the last of the month
                    if(!Carbon::parse($date)->isLastOfMonth()) {
                        $date = Carbon::parse($date)->lastOfMonth()->format('Y-m-d');
                        $rows[] = $date;
                    }
                }


                //prepare an array containing the Immediate Previous Lease End Date for all the current payments
                //prepare an array for the dates that will be used for the days diff from current date to the previous date
                //prepare an array for the last payment interval end date
                //we will also add the last interval end date to the rows so that we can calculate the expense on that date as well
                $immediate_previous_lease_end_date = [];
                $last_lease_payment_interval_end_date = [];
                $previous_date_array = [];
                $payments_last_interval_end_date = [];
                foreach ($asset_payments as $asset_payment) {
                    //last payment date for the current payment
                    $last_payment_date = collect($lease_payments_initial)->where('id', '=', $asset_payment->id)->sortByDesc('date')->first();

                    if($asset_payment->payout_time === 1){
                        $last_lease_payment_interval_end_date[$asset_payment->id] = $asset_payment->last_lease_payment_interval_end_date;
                        //will have to sum the payment interval to the last payment date here...
                        //here will also have to add the current payment interval to the last payment date as well..
                        $payments_last_interval_end_date[$asset_payment->id] = Carbon::parse($asset_payment->last_lease_payment_interval_end_date);
                        $rows[] = $asset_payment->last_lease_payment_interval_end_date;
                        //$payments_last_interval_end_date[$asset_payment->id] =
                    } else if($asset_payment->payout_time === 2){
                        //substracted the 1 day so that we will not exclude the date in the database...
                        $immediate_previous_lease_end_date[$asset_payment->id] = Carbon::parse($asset_payment->immediate_previous_lease_end_date)->subDay(1)->format('Y-m-d');
                        $payments_last_interval_end_date[$asset_payment->id] = Carbon::parse($last_payment_date->date);
                        $rows[] = Carbon::parse($asset_payment->lastPaymentIntevalEndDate($last_payment_date->date, $end_date))->format('Y-m-d');
                    }
                    $previous_date_array[$asset_payment->id] = $base_date->format('Y-m-d');
                }

                $rows = array_unique(collect($rows)->sort()->toArray());
                //calculate the opening prepaid/payable balance here from the step 13 i.e lease balances as on dec...
                $lease_balances = $asset->leaseBalanceAsOnDec;
                $opening_or_payable = 0;
                if($lease_balances) {
                    $opening_or_payable = ($lease_balances->prepaid_lease_payment_balance * $lease_balances->exchange_rate) - ($lease_balances->accrued_lease_payment_balance * $lease_balances->exchange_rate);
                }

                //now need to create the logic for each payment at each row and create the annexure...
                //at each row we loop through all the existing payments that we have for the lease asset
                //for each payment we fetch the payment details from the array that we have for the current payment and at current row date...
                //and prepare the annexure based upon the conditions from the sheet shared...
                $annexure = [];
                foreach ($rows as $key=>$row) {
                    $date_for_next_iteration = $row;
                    $internal_array = [];
                    foreach ($asset_payments as $asset_payment) {
                        if(isset($lease_payments[$row])) {
                            //at interval start
                            //need to find the days diff from the current date - previous payment date
                            $current_payment_date = Carbon::parse($row);

                            //previous payment date needs to be calculated here as well..
                            $previous_payment_date = array_values(collect($lease_payments_initial)
                                ->where('date', '<', $row)
                                ->where('id', '=', $asset_payment->id)
                                ->sortByDesc('date')
                                ->take(1)->toArray());

                            //payment at this date do exists..
                            $payment_details_at_this_date = collect($lease_payments[$row])->where('id', '=', $asset_payment->id)->first();
                            if($payment_details_at_this_date) {

                                if($asset_payment->payout_time === 1){
                                    //if the above returns an empty collection this means that we will use the current date...
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = $current_payment_date;
                                    }

                                    $days_diff = $current_payment_date->diffInDays($previous_payment_date);
                                    //now we need to take out the payment amount at the previous date in this case for the payment at the lease interval start...
                                    //that will be used in the calculations for the computed lease expense
                                    $amount_at_previous_date = 0;
                                    if(isset($lease_payments[$previous_payment_date->format('Y-m-d')])){
                                        $amount_at_previous_date = array_values(collect($lease_payments[$previous_payment_date->format('Y-m-d')])
                                            ->where('id', '=', $asset_payment->id)
                                            ->toArray());
                                        if(count($amount_at_previous_date) > 0) {
                                            $amount_at_previous_date = $amount_at_previous_date[0]->total_amount_payable;
                                        } else {
                                            //use from the current payment
                                            $amount_at_previous_date = $payment_details_at_this_date->total_amount_payable;
                                        }
                                    } else {
                                        //use the current payment amount
                                        $amount_at_previous_date = $payment_details_at_this_date->total_amount_payable;
                                    }


                                } elseif($asset_payment->payout_time === 2) {
                                    //at interval end need to find the days diff from the
                                    //if the above returns an empty collection this means that we will use the immediate previous lease end date...
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = $immediate_previous_lease_end_date[$asset_payment->id];
                                    }

                                    $days_diff = $current_payment_date->diffInDays($previous_payment_date);

                                    //now we need to take out the payment amount at the current date in the case of the payment at the lease interval end ....
                                    //that will be used in the calculations for the computed lease expense
                                    $amount_at_previous_date = $payment_details_at_this_date->total_amount_payable;
                                }

                                $days_diff_from_previous_date = $current_payment_date->diffInDays(Carbon::parse($previous_date_array[$asset_payment->id]));
                                if($asset_payment->payout_time === 2 && Carbon::parse($previous_date_array[$asset_payment->id])->equalTo($base_date)){
                                    $days_diff_from_previous_date = $days_diff_from_previous_date + 1;
                                }

                                if($days_diff == 0 || Carbon::parse($row)->greaterThan($payments_last_interval_end_date[$asset_payment->id])){
                                    $computed_lease_payment_expense = 0;
                                } else {
                                    $computed_lease_payment_expense = round(($amount_at_previous_date/$days_diff) * $days_diff_from_previous_date, 2);
                                }


                                $internal_array[$asset_payment->id] = [
                                    'payment_name' => $asset_payment->name,
                                    'date' => $row,
                                    'payment_amount' => $payment_details_at_this_date->total_amount_payable,
                                    'computed_lease_payment_expense' => $computed_lease_payment_expense
                                ];
                            } else {

                                $previous_payment_date = array_values(collect($lease_payments_initial)
                                    ->where('date', '<', $row)
                                    ->where('id', '=', $asset_payment->id)
                                    ->sortByDesc('date')
                                    ->take(1)->toArray());


                                $next_payment_date = array_values(collect($lease_payments_initial)
                                    ->where('date', '>', $row)
                                    ->where('id', '=', $asset_payment->id)
                                    ->sortBy('date')
                                    ->take(1)->toArray());

                                if($asset_payment->payout_time === 1){

                                    //need to take out the payment at the date which is previous to $row date
                                    //need to take out the next payment date as well..
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = Carbon::parse($row);
                                    }

                                    //in case there is no next payment date for the current payment we use the current date as the next payment date.
                                    if(collect($next_payment_date)->count()) {
                                        $next_payment_date = Carbon::parse($next_payment_date[0]->date);
                                    } else {
                                        $next_payment_date = Carbon::parse($last_lease_payment_interval_end_date[$asset_payment->id]);
                                    }

                                    //days diff from the next payment date to the previous payment date
                                    $days_diff = $next_payment_date->diffInDays($previous_payment_date);

                                    $amount_at_previous_date = 0;
                                    if(isset($lease_payments[$previous_payment_date->format('Y-m-d')])){
                                        $amount_at_previous_date = array_values(collect($lease_payments[$previous_payment_date->format('Y-m-d')])
                                            ->where('id', '=', $asset_payment->id)
                                            ->toArray());
                                        if(count($amount_at_previous_date) > 0) {
                                            $amount_at_previous_date = $amount_at_previous_date[0]->total_amount_payable;
                                        } else {
                                            //use from the 0 as the payment in this case
                                            $amount_at_previous_date = 0;
                                        }
                                    } else {
                                        //use the current payment amount
                                        $amount_at_previous_date = 0;
                                    }

                                } elseif ($asset_payment->payout_time === 2){

                                    //need to take out the payment at the date which is previous to $row date
                                    //need to take out the next payment date as well..
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = Carbon::parse($immediate_previous_lease_end_date[$asset_payment->id]);
                                    }

                                    //in case there is no next payment date for the current payment we use the current date as the next payment date.
                                    if(collect($next_payment_date)->count()) {
                                        $next_payment_date = Carbon::parse($next_payment_date[0]->date);
                                    } else {
                                        $next_payment_date = Carbon::parse($row);;
                                    }

                                    //days diff from the next payment date to the previous payment date
                                    $days_diff = $next_payment_date->diffInDays($previous_payment_date);

                                    //fetch the payment at the next payment date, in case the payment doesn't exists then in that case the we will use 0 at that place.
                                    $amount_at_previous_date = 0;
                                    if($previous_payment_date->greaterThanOrEqualTo(Carbon::parse($row))){
                                        //condition to check so that the computation will only be done when the previous payment date is greater than the current row date..
                                        $amount_at_previous_date = 0;
                                        $date_for_next_iteration = $previous_payment_date;
                                    } elseif(isset($lease_payments[$next_payment_date->format('Y-m-d')])){
                                        $amount_at_previous_date = array_values(collect($lease_payments[$next_payment_date->format('Y-m-d')])
                                            ->where('id', '=', $asset_payment->id)
                                            ->toArray());
                                        if(count($amount_at_previous_date) > 0) {
                                            $amount_at_previous_date = $amount_at_previous_date[0]->total_amount_payable;
                                        } else {
                                            //use from the 0 as the payment in this case
                                            $amount_at_previous_date = 0;
                                        }
                                    } else {
                                        //use the current payment amount
                                        $amount_at_previous_date = 0;
                                    }

                                }

                                $days_diff_from_previous_date = Carbon::parse($row)->diffInDays(Carbon::parse($previous_date_array[$asset_payment->id]));

                                if($asset_payment->payout_time === 2 && Carbon::parse($previous_date_array[$asset_payment->id])->equalTo($base_date)){
                                    $days_diff_from_previous_date = $days_diff_from_previous_date + 1;
                                }

                                if($days_diff == 0 || Carbon::parse($row)->greaterThan($payments_last_interval_end_date[$asset_payment->id])){
                                    $computed_lease_payment_expense = 0;
                                } else {
                                    $computed_lease_payment_expense = round(($amount_at_previous_date/$days_diff) * $days_diff_from_previous_date, 2);
                                }

                                //$annexure[$row][$asset_payment->id]
                                $internal_array[$asset_payment->id] = [
                                    'payment_name' => $asset_payment->name,
                                    'date' => $row,
                                    'payment_amount' => 0,
                                    'computed_lease_payment_expense' => $computed_lease_payment_expense
                                ];
                            }
                        } else {
                            //the payment on this date doesn't exists
                            //or payment at interval end

                            if(Carbon::parse($row)->isLastOfMonth()){

                                $previous_payment_date = array_values(collect($lease_payments_initial)
                                    ->where('date', '<', $row)
                                    ->where('id', '=', $asset_payment->id)
                                    ->sortByDesc('date')
                                    ->take(1)->toArray());


                                $next_payment_date = array_values(collect($lease_payments_initial)
                                    ->where('date', '>', $row)
                                    ->where('id', '=', $asset_payment->id)
                                    ->sortBy('date')
                                    ->take(1)->toArray());


                                if($asset_payment->payout_time === 1) {

                                    //need to take out the payment at the date which is previous to $row date
                                    //need to take out the next payment date as well..
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = Carbon::parse($row);
                                    }

                                    //in case there is no next payment date for the current payment we use the current date as the next payment date.
                                    if(collect($next_payment_date)->count()) {
                                        $next_payment_date = Carbon::parse($next_payment_date[0]->date);
                                    } else {
                                        $next_payment_date = Carbon::parse($last_lease_payment_interval_end_date[$asset_payment->id]);
                                    }

                                    //days diff from the next payment date to the previous payment date
                                    $days_diff = $next_payment_date->diffInDays($previous_payment_date);

                                    $amount_at_previous_date = 0;
                                    if(isset($lease_payments[$previous_payment_date->format('Y-m-d')])){
                                        $amount_at_previous_date = array_values(collect($lease_payments[$previous_payment_date->format('Y-m-d')])
                                            ->where('id', '=', $asset_payment->id)
                                            ->toArray());
                                        if(count($amount_at_previous_date) > 0) {
                                            $amount_at_previous_date = $amount_at_previous_date[0]->total_amount_payable;
                                        } else {
                                            //use from the 0 as the payment in this case
                                            $amount_at_previous_date = 0;
                                        }
                                    } else {
                                        //use the current payment amount
                                        $amount_at_previous_date = 0;
                                    }

                                } elseif ($asset_payment->payout_time === 2) {

                                    //need to take out the payment at the date which is previous to $row date
                                    //need to take out the next payment date as well..
                                    if(collect($previous_payment_date)->count()) {
                                        $previous_payment_date = Carbon::parse($previous_payment_date[0]->date);
                                    } else {
                                        $previous_payment_date = Carbon::parse($immediate_previous_lease_end_date[$asset_payment->id]);
                                    }

                                    //in case there is no next payment date for the current payment we use the current date as the next payment date.
                                    if(collect($next_payment_date)->count()) {
                                        $next_payment_date = Carbon::parse($next_payment_date[0]->date);
                                    } else {
                                        $next_payment_date = Carbon::parse($row);
                                    }

                                    //days diff from the next payment date to the previous payment date
                                    $days_diff = $next_payment_date->diffInDays($previous_payment_date);

                                    //fetch the payment at the next payment date, in case the payment doesn't exists then in that case the we will use 0 at that place.
                                    $amount_at_previous_date = 0;
                                    if($previous_payment_date->greaterThanOrEqualTo(Carbon::parse($row))){
                                        //condition to check so that the computation will only be done when the previous payment date is greater than the current row date..
                                        $amount_at_previous_date = 0;
                                        $date_for_next_iteration = $previous_payment_date;
                                    } elseif(isset($lease_payments[$next_payment_date->format('Y-m-d')])){
                                        $amount_at_previous_date = array_values(collect($lease_payments[$next_payment_date->format('Y-m-d')])
                                            ->where('id', '=', $asset_payment->id)
                                            ->toArray());
                                        if(count($amount_at_previous_date) > 0) {
                                            $amount_at_previous_date = $amount_at_previous_date[0]->total_amount_payable;
                                        } else {
                                            //use from the 0 as the payment in this case
                                            $amount_at_previous_date = 0;
                                        }
                                    } else {
                                        //use the current payment amount
                                        $amount_at_previous_date = 0;
                                    }
                                }

                                $days_diff_from_previous_date = Carbon::parse($row)->diffInDays(Carbon::parse($previous_date_array[$asset_payment->id]));

                                if($asset_payment->payout_time === 2 && Carbon::parse($previous_date_array[$asset_payment->id])->equalTo($base_date)){
                                    $days_diff_from_previous_date = $days_diff_from_previous_date + 1;
                                }

                                if($days_diff == 0 || Carbon::parse($row)->greaterThan($payments_last_interval_end_date[$asset_payment->id])){
                                    $computed_lease_payment_expense = 0;
                                } else {
                                    $computed_lease_payment_expense = round(($amount_at_previous_date/$days_diff) * $days_diff_from_previous_date, 2);
                                }


                                $internal_array[$asset_payment->id] = [
                                    'payment_name' => $asset_payment->name,
                                    'date' => $row,
                                    'payment_amount' => 0,
                                    'computed_lease_payment_expense' => $computed_lease_payment_expense
                                ];

                            } else {


                                if($asset_payment->payout_time === 1) {
                                    $date_for_next_iteration = $row;
                                } elseif ($asset_payment->payout_time === 2) {
                                    $date_for_next_iteration = $immediate_previous_lease_end_date[$asset_payment->id];
                                }


                                //$annexure[$row][$asset_payment->id]
                                $internal_array[$asset_payment->id] = [
                                    'payment_name' => $asset_payment->name,
                                    'date' => $row,
                                    'payment_amount' => 0,
                                    'computed_lease_payment_expense' => 0
                                ];

                            }

                        }

                        $previous_date_array[$asset_payment->id] = $date_for_next_iteration;
                    }

                    //will have to check here for the termination and Residual Option as well
                    if(isset($lease_payments[$row])) {
                        //fetch the termination option payments here..
                        $termination_option  = collect($lease_payments[$row])->where('payment_type', '=', 'termination_penalty')->first();
                        if($termination_option){
                            $internal_array['termination_penalty'] = [
                                'payment_name' => $termination_option->name,
                                'date' => $row,
                                'payment_amount' => $termination_option->total_amount_payable,
                                'computed_lease_payment_expense' => $termination_option->total_amount_payable //no computation is required just need to use the same amount
                            ];
                        }

                        //fetch the residual option payments here..
                        $residual_option  = collect($lease_payments[$row])->where('payment_type', '=', 'residual_value')->first();
                        if($residual_option){
                            $internal_array['residual_value'] = [
                                'payment_name' => $residual_option->name,
                                'date' => $row,
                                'payment_amount' => $residual_option->total_amount_payable,
                                'computed_lease_payment_expense' => $residual_option->total_amount_payable //no computation is required just need to use the same amount
                            ];
                        }
                    }

                    $total_computed_lease_expense = collect($internal_array)->sum('computed_lease_payment_expense');

                    $annexure[$row]['date'] = $row;
                    $annexure[$row]['asset_id'] = $asset->id;
                    $annexure[$row]['opening_prepaid_payable_balance'] = $opening_or_payable;
                    $annexure[$row]['total_computed_lease_expense'] = $total_computed_lease_expense;
                    $annexure[$row]['closing_prepaid_payable_balance'] = $opening_or_payable - $total_computed_lease_expense;
                    $annexure[$row]['payments_details'] = json_encode($internal_array);
                    $opening_or_payable = $opening_or_payable - $total_computed_lease_expense;
                }

//                echo "<pre>"; print_r($annexure); die();

                DB::transaction(function () use ($annexure, $modify_id, $asset) {
                    //insert the dates data into the interest and depreciation table for the lease id
                    if(is_null($modify_id)){
                        //InterestAndDepreciation::query()->where('asset_id', '=', $asset->id)->delete();
                        DB::table('lease_expense_annexure')->where('asset_id', '=', $asset->id)->delete();
                    }

                    DB::table('lease_expense_annexure')->insert($annexure);
                });

                return true;

            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * submit the lease here.
     * generate the lease history data and save the details lease_history table...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function submit($id, Request $request)
    {
        if ($request->isMethod('post')) {

            //need to generate the data for the Interest and Depreciation Tab for the current valuation..
            $model = Lease::query()->where('id', '=', $id)->first();

            $model->status = "1";

            $model->is_completed = 1;

            $model->save();

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first()->toArray();

            $underlyning_asset = LeaseAssets::query()->where('lease_id', '=', $id)->first()->toArray();

            $asset_id = $underlyning_asset['id'];

            $assets = LeaseAssets::query()->findOrFail($asset_id);

            $payment_id = $assets->paymentsduedate->pluck('payment_id')->toArray();

            $lease_payments = LeaseAssetPayments::query()->where('asset_id', '=', $asset_id)->get()->toArray();

            $fair_market_value = FairMarketValue::query()->where('lease_id', '=', $id)->first();

            if ($fair_market_value) {
                $fair_market_value = $fair_market_value->toArray();
            }

            $residual_value = LeaseResidualValue::query()->where('lease_id', '=', $id)->first()->toArray();

            $termination_option = LeaseTerminationOption::query()->where('lease_id', '=', $id)->first()->toArray();

            $renewal_option = LeaseRenewableOption::query()->where('lease_id', '=', $id)->first();
            if ($renewal_option) {
                $renewal_option = $renewal_option->toArray();
            }

            $purchase_option = PurchaseOption::query()->where('lease_id', '=', $id)->first();
            if ($purchase_option) {
                $purchase_option = $purchase_option->toArray();
            }

            $duration_classified = LeaseDurationClassified::query()->where('lease_id', '=', $id)->first();
            if ($duration_classified) {
                $duration_classified = $duration_classified->toArray();
            }

            $payment_esclation_details = PaymentEscalationDetails::query()->where('lease_id', '=', $id)->first();
            if ($payment_esclation_details) {
                $payment_esclation_details = $payment_esclation_details->toArray();
            }

            // esclation Payments with due date
            $payment_due_dates = LeaseAssetPaymenetDueDate::query()->whereIn('payment_id', $payment_id)->get()->toArray();

            $escalation_dates = PaymentEscalationDates::query()->whereIn('payment_id', $payment_id)->get()->toArray();

            $low_value = LeaseSelectLowValue::query()->where('lease_id', '=', $id)->first();
            if ($low_value) {
                $low_value = $low_value->toArray();
            }

            $discount_rate = LeaseSelectDiscountRate::query()->where('lease_id', '=', $id)->first();
            if ($discount_rate) {
                $discount_rate = $discount_rate->toArray();
            }

            $lease_balance = LeaseBalanceAsOnDec::query()->where('lease_id', '=', $id)->first();
            if ($lease_balance) {
                $lease_balance = $lease_balance->toArray();
            }

            $initial_direct_cost = InitialDirectCost::query()->where('lease_id', '=', $id)->first();

            //get supplier details
            if ($initial_direct_cost) {
                $initial_direct_cost_id = $assets->initialDirectCost->pluck('id')->toArray();
                $supplier_details = SupplierDetails::query()->whereIn('initial_direct_cost_id', $initial_direct_cost_id)->where('type', '=', 'initial_direct_cost')->get()->toArray();
            } else {
                $supplier_details = [];
            }

            $lease_incentives = LeaseIncentives::query()->where('lease_id', '=', $id)->first();

            if ($lease_incentives) {
                //get customer details
                $lease_incentive_id = $assets->leaseIncentiveCost->pluck('id')->toArray();

                $customer_details = CustomerDetails::query()->whereIn('lease_incentive_id', $lease_incentive_id)->get()->toArray();
            } else {
                $customer_details = [];
            }

            $dismantling_cost = DismantlingCosts::query()->where('lease_id', '=', $id)->first();
            if ($dismantling_cost) {
                //get customer details
                $dismantling_cost_id = $assets->dismantlingCost->pluck('id')->toArray();

                $supplier_details_dismantling = SupplierDetails::query()->whereIn('initial_direct_cost_id', $dismantling_cost_id)->where('type', '=', 'dismantling_cost')->get()->toArray();
            } else {
                $supplier_details_dismantling = [];
            }

            $lease_invoice = LeasePaymentInvoice::query()->where('lease_id', '=', $id)->first();
            if ($lease_invoice) {
                $lease_invoice = $lease_invoice->toArray();
            } else {
                $lease_invoice = [];
            }

            //lessor-details step 1
            $record['lessor_details'] = $lease;

            //Underlying Assets step 2
            $record['underlying_asset'] = $underlyning_asset;

            //Lease Asset Payments step 3
            $record['lease_payments'] = $lease_payments;

            // Fair market value step 4
            $record['fair_market'] = $fair_market_value;

            //Residual Gurantee Value step 5
            $record['residual_value'] = $residual_value;

            //Lease Termination Option step 6
            $record['termination_option'] = $termination_option;

            //Renewable Option step 7
            $record['renewal_option'] = $renewal_option;

            //purchase option step 8
            $record['purchase_option'] = $purchase_option;

            //Duartion Classified step 9
            $record['duration_classified'] = $duration_classified;

            //payment due date with asset id
            $payments['payment_due_dates'] = $payment_due_dates;

            //payment esclation step10
            $record['payment_esclation'] = $payment_esclation_details;

            //Select Low Value step 11
            $record['low_value'] = $low_value;

            //Select Discount Rate step 12
            $record['discount_rate'] = $discount_rate;

            //Lease Balance As on Dec Step 13
            $record['lease_balance'] = $lease_balance;

            //inital direct Cost step 14
            $record['initial_direct_cost'] = ($initial_direct_cost) ? $initial_direct_cost->toArray() : [];

            $record['initial_direct_cost']['supplier_details'] = $supplier_details;

            //lease incentives step 15
            $record['lease_incentives'] = ($lease_incentives) ? $lease_incentives->toArray() : [];

            $record['lease_incentives']['customer_details'] = $customer_details;

            //dismantling costs
            $record['dismantling_cost'] = ($dismantling_cost) ? $dismantling_cost->toArray() : [];

            $record['dismantling_cost']['supplier_details'] = $supplier_details_dismantling;

            //lease valaution step 16 is only for caculate present value lease liability

            // lessor invoice step 17
            $record['lessor_invoice'] = $lease_invoice;

            //save the record in lease history
            $data = $request->except('_token');
            $data['lease_id'] = $id;

            $data['json_data_steps'] = json_encode($record, JSON_PRESERVE_ZERO_FRACTION);

            $data['esclation_payments'] = json_encode($escalation_dates, JSON_PRESERVE_ZERO_FRACTION);

            $data['payment_anxure'] = json_encode($payments, JSON_PRESERVE_ZERO_FRACTION);

            $pvCalculus = PvCalculus::query()->select('calculus')->where('asset_id', '=', $asset_id)->first();
            if($pvCalculus){
                $data['pv_calculus'] = $pvCalculus->calculus;
            } else {
                $data['pv_calculus'] = json_encode([]);
            }

            $historical_annexure = HistoricalCarryingAmountAnnexure::query()->where('asset_id', '=', $asset_id)->get();
            if($pvCalculus){
                $data['historical_annexure'] = json_encode($historical_annexure, JSON_PRESERVE_ZERO_FRACTION);
            } else {
                $data['historical_annexure'] = json_encode([]);
            }

            if (count($model->modifyLeaseApplication) > 0) {
                $data['modify_id'] = $model->modifyLeaseApplication->last()->id;
            }

            if (count($model->modifyLeaseApplication) > 0 && $model->modifyLeaseApplication->last()->valuation == "Modify Initial Valuation") {
                //fetch the current history and update the same..
                $lease_history = LeaseHistory::query()->where('lease_id', '=', $id)->first();
                if ($lease_history) {
                    unset($data['modify_id']);
                    $lease_history->setRawAttributes($data);
                    $lease_history->save();
                } else {
                   $lease_history = LeaseHistory::create($data);
                }
            } else {
                $lease_history = LeaseHistory::create($data);
            }

            //need to put condition so that this will be done only for the leases for which lease valuation has been done...
            $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->get();

            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

            $asset = LeaseAssets::query()->where('lease_id', '=', $id)
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id', $category_excluded_id)->first();

            if($asset) {
                //the Lease is a CAP lease and we need to create the Lease Interest and Depreciation Annexure for the lease here..
                $this->generateDataForInterestAndDepreciationTab($model, isset($data['modify_id'])?$data['modify_id']:null);
            } else if(is_null($asset)) {
                if(isset($data['modify_id'])) {
                    // will not be generated in case of modification...
                } else {
                    // the lease is a NCAP lease and we need to create the Lease Expense Annexure here for the lease here ...
                    $this->generateLeaseExpense($model, isset($data['modify_id'])?$data['modify_id']:null);
                }
            }

            if ($lease_history) {
                confirmSteps($id, $this->current_step);
            }

            if($assets->uuid!=''){
                $ulacode = $assets->uuid;
            } else {
                $ulacode = createUlaCode();
                $uid['uuid'] = $ulacode;
                $assets->setRawAttributes($uid);
                $assets->save();
            }

            //check here for the redirect based upon the category and other conditions as well
            if($asset){
                return redirect(route('leasevaluation.cap.index'))->with('status', $ulacode);
            } else {
                return redirect(route('leasevaluation.ncap.index'))->with('status', $ulacode);
            }
        }
    }

}


<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 07/01/19
 * Time: 10:30 AM
 */

namespace App\Http\Controllers\Lease;

use App\DismantlingCosts;
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
use phpDocumentor\Reflection\Types\Object_;
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
     * calculate the interest expense value for the interest and Depreciation Tab
     * @param float $previous_liability
     * @param float $discount_rate
     * @param int $days
     * @return float|int
     */
    private function calculateInterestExpense(float $previous_liability, float $discount_rate, int $days)
    {
        $discount_rate = $discount_rate * (1 / 100);
        return ($previous_liability * pow(1 + $discount_rate, $days)) - $previous_liability;
    }

    /**
     * calculate and returns the total number of months...
     * @param $start_date
     * @param $end_date
     * @return int
     * @throws \Exception
     */
    private function calculateMonthsDifference($start_date, $end_date){
        $start    = (new \DateTime($start_date))->modify('first day of this month');
        $end      = (new \DateTime($end_date))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $months_array = [];
        foreach ($period as $dt) {
            $months_array[] = $dt->format("Y-m");
        }
        return count($months_array);
    }

    /**
     * calculate the deprecition for the lease asset based upon the start date and end date and in case of subsequent from the effective date till the lease end date...
     * @param $subsequent_modify_required
     * @param $asset
     * @param $value_of_lease_asset
     * @return float|int
     * @throws \Exception
     */
    private function calculateDepreciation($subsequent_modify_required, $asset, $value_of_lease_asset){
        $start_date = Carbon::parse($asset->accural_period);
        if(!$subsequent_modify_required) {
            $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
            $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
            $months = $this->calculateMonthsDifference($base_date->format('Y-m-d'), $asset->getLeaseEndDate($asset));
            $depreciation = (float)$value_of_lease_asset/$months;
            return $depreciation;
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

            $asset = $lease->assets()->first();
            $start_date = Carbon::parse($asset->accural_period);

            //check for the subsequent modification here....
            $subsequent_modify_required = $lease->isSubsequentModification();
            if($subsequent_modify_required) {
                $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
            } else {
                $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
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

            $sql = "SELECT 
                        `lease_asset_payment_dates`.`date`,
                        SUM(IF(ISNULL(`payment_escalation_dates`.`id`), `lease_asset_payment_dates`.`total_payment_amount` , `payment_escalation_dates`.`total_amount_payable`)) as `total_amount_payable`
                    FROM
                        `lease_assets_payments`
                            LEFT JOIN
                        `lease_asset_payment_dates` ON `lease_assets_payments`.`id` = `lease_asset_payment_dates`.`payment_id`
                            LEFT JOIN
                        `payment_escalation_dates` ON (
                            `lease_assets_payments`.`id` = `payment_escalation_dates`.`payment_id`
                             AND year(`lease_asset_payment_dates`.`date`) = `payment_escalation_dates`.`escalation_year` 
                             AND month(`lease_asset_payment_dates`.`date`) = `payment_escalation_dates`.`escalation_month`
                          )
                    WHERE
                        `lease_assets_payments`.`asset_id` = {$asset->id}
                          and `lease_asset_payment_dates`.`date` >= '{$base_date->format('Y-m-d')}'
                          and `lease_asset_payment_dates`.`date` <= '{$end_date->format('Y-m-d')}'
                          and `lease_assets_payments`.`type` <> '2'
                    GROUP BY `lease_asset_payment_dates`.`date`";


            $lease_payments = DB::select($sql);

            //add the residual value guarantee as well..
            if ($asset->residualGuranteeValue->any_residual_value_gurantee == "yes") {

                $payment_dates = array_where($lease_payments, function($value) use ($end_date){
                    return $end_date->equalTo(Carbon::parse($value->date));
                });

                $new_array['date'] = $end_date->format('Y-m-d');
                $new_array['total_amount_payable'] = $asset->residualGuranteeValue->residual_gurantee_value;
                if(!empty($payment_dates)){
                    $payment_dates = array_values($payment_dates);
                    $new_array['total_amount_payable'] = $asset->residualGuranteeValue->residual_gurantee_value + $payment_dates[0]->total_amount_payable;
                }
                array_push($lease_payments, (object)$new_array);

            }

            //add the termination, residual and purchase to the last key if exists
            if ($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes" && $asset->terminationOption->termination_penalty_applicable == "yes") {

                $termination_date = $asset->terminationOption->lease_end_date;
                $payment_dates = array_where($lease_payments, function($value) use ($termination_date){
                    return Carbon::parse($termination_date)->equalTo(Carbon::parse($value->date));
                });

                $new_array['date'] = $asset->terminationOption->lease_end_date;
                $new_array['total_amount_payable'] = $asset->terminationOption->termination_penalty;
                if(!empty($payment_dates)){
                    $payment_dates = array_values($payment_dates);
                    $new_array['total_amount_payable'] = $asset->terminationOption->termination_penalty + $payment_dates[0]->total_amount_payable;
                }
                array_push($lease_payments, (object)$new_array);
            }

            //add the purchase option as well as well..
            if ($asset->purchaseOption && $asset->purchaseOption->purchase_option_clause == "yes" && $asset->purchaseOption->purchase_option_exerecisable == "yes") {
                $purchase_date = $asset->purchaseOption->expected_purchase_date;
                $payment_dates = array_where($lease_payments, function($value) use ($purchase_date){
                    return Carbon::parse($purchase_date)->equalTo(Carbon::parse($value->date));
                });
                $new_array['date'] = $asset->purchaseOption->expected_purchase_date;
                $new_array['total_amount_payable'] = $asset->purchaseOption->purchase_price;
                if(!empty($payment_dates)){
                    $payment_dates = array_values($payment_dates);
                    $new_array['total_amount_payable'] = $asset->purchaseOption->purchase_price + $payment_dates[0]->total_amount_payable;
                }
                array_push($lease_payments, (object)$new_array);
            }



            $check_date = $increase_or_decrease = null;

            $previous_liability = $asset->lease_liablity_value;

            if($subsequent_modify_required) {
                $all_subsequent_effective_dates = LeaseHistory::query()
                    ->where('lease_id', '=', $lease->id);

                if($all_subsequent_effective_dates->count() === 2) {
                    //need to fetch the last row and need to take out the closing liability from the same...
                    $previous_depreciation_data = InterestAndDepreciation::query()
                        ->where('date', '<', $base_date->format('Y-m-d'))
                        ->where('asset_id', '=', $asset->id)
                        ->orderBy('date', 'desc')
                        ->first();

                } else if($all_subsequent_effective_dates->count() > 2) {

                    $effective_date = ModifyLeaseApplication::query()
                        ->where('lease_id', '=', $lease->id)
                        ->where('valuation', '=', 'Subsequent Valuation')
                        ->where('effective_from', '<', $lease->modifyLeaseApplication->last()->effective_from)
                        ->orderBy('effective_from', 'desc')
                        ->limit(1)
                        ->first();

                    $previous_depreciation_data = InterestAndDepreciation::query()
                        ->where('modify_id', '=', $effective_date->id)
                        ->where('asset_id', '=', $asset->id)
                        ->orderBy('date','asc')
                        ->first();

                }

                $one_day_before = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->subDay(1);
                //check a row exists on one day before in interest and depreciation
                $day_before_subsequent = InterestAndDepreciation::query()
                    ->where('date', '=', Carbon::parse($one_day_before))
                    ->where('asset_id', '=', $asset->id)
                    ->first();

                $value_of_lease_asset = $asset->value_of_lease_asset;

                $increase_or_decrease =  $asset->increase_decrease;

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
                        'carrying_value_of_lease_asset' => $day_before_subsequent->carrying_value_of_lease_asset
                    ];
                    $previous_carrying_value_of_lease_asset = $day_before_subsequent->carrying_value_of_lease_asset;
                    $previous_accumulated_depreciation = $day_before_subsequent->accumulated_depreciation;
                } else {
                    $days_diff = Carbon::parse($one_day_before)->diffInDays($previous_depreciation_data->date);
                    $interest_expense = $this->calculateInterestExpense($previous_liability, $previous_depreciation_data->discount_rate, $days_diff);
                    $dates[] = [
                        'asset_id' => $asset->id,
                        'modify_id' => $previous_depreciation_data->modify_id,
                        'date' => $one_day_before->format('Y-m-d'),
                        'number_of_days' => $days_diff,
                        'discount_rate' => $previous_depreciation_data->discount_rate,
                        'opening_lease_liability' => $previous_liability,
                        'interest_expense' => $interest_expense,
                        'lease_payment' => 0,
                        'closing_lease_liability' => ($interest_expense + $previous_liability - 0),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'value_of_lease_asset' => $previous_depreciation_data->value_of_lease_asset,
                        'depreciation' => $previous_depreciation_data->depreciation,
                        'change' => $previous_depreciation_data->change,
                        'accumulated_depreciation' => $previous_depreciation_data->accumulated_depreciation,
                        'carrying_value_of_lease_asset' => $previous_depreciation_data->carrying_value_of_lease_asset
                    ];
                    $previous_carrying_value_of_lease_asset = $previous_depreciation_data->carrying_value_of_lease_asset;
                    $previous_accumulated_depreciation = $previous_depreciation_data->accumulated_depreciation;
                }

                //need to set the values here that will be used on the next iteration

                $number_of_months = $this->calculateMonthsDifference($base_date->format('Y-m-d'), $asset->getLeaseEndDate($asset));
                $previous_depreciation = $previous_carrying_value_of_lease_asset / $number_of_months;
                if(Carbon::parse($one_day_before)->isLastOfMonth()){
                    $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                    $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $previous_depreciation;
                }

            } else {
                $previous_depreciation = $this->calculateDepreciation($subsequent_modify_required, $asset, $previous_liability);
                $previous_accumulated_depreciation = 0;
                $previous_carrying_value_of_lease_asset = $previous_liability;
                $value_of_lease_asset = $previous_carrying_value_of_lease_asset;
            }

            //dd($previous_accumulated_depreciation, $previous_carrying_value_of_lease_asset, $previous_depreciation);

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

                    if($start_year == $first_year && $start_month == $key && empty($payment_dates) && !Carbon::parse($base_date)->isLastOfMonth()){
                        $days_diff = Carbon::parse($base_date)->diffInDays($base_date);
                        $interest_expense = $this->calculateInterestExpense($previous_liability, $discount_rate, $days_diff);
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
                            'accumulated_depreciation' => $previous_accumulated_depreciation,
                            'carrying_value_of_lease_asset' => $previous_carrying_value_of_lease_asset + $increase_or_decrease

                        ];
                        $base_date = Carbon::parse($base_date);
                        $previous_liability = $interest_expense + $previous_liability - 0;
                        $increase_or_decrease = 0;
                        if(Carbon::parse($base_date)->isLastOfMonth()){
                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $previous_depreciation;

                        }
                    }

                    foreach ($payment_dates as $payment_date) {
                        $days_diff = Carbon::parse($payment_date->date)->diffInDays($base_date);
                        $interest_expense = $this->calculateInterestExpense($previous_liability, $discount_rate, $days_diff);
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
                            'accumulated_depreciation' => (Carbon::parse($payment_date->date)->isLastOfMonth()) ?  $previous_depreciation + $previous_accumulated_depreciation: $previous_accumulated_depreciation,
                            'carrying_value_of_lease_asset' => (Carbon::parse($payment_date->date)->isLastOfMonth()) ? $previous_carrying_value_of_lease_asset + $increase_or_decrease - $previous_depreciation : $previous_carrying_value_of_lease_asset
                        ];
                        $base_date = Carbon::parse($payment_date->date);
                        $previous_liability = $interest_expense + $previous_liability - (float)$payment_date->total_amount_payable;
                        $increase_or_decrease = 0;

                        if(Carbon::parse($payment_date->date)->isLastOfMonth()){
                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $previous_depreciation;

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
                            $interest_expense = $this->calculateInterestExpense($previous_liability, $discount_rate, $days_diff);

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
                                'carrying_value_of_lease_asset' => round($previous_carrying_value_of_lease_asset + $increase_or_decrease - $previous_depreciation, 2)
                            ];

                            $base_date = Carbon::parse($current_date);
                            $previous_liability = $interest_expense + $previous_liability - (float)$amount_payable;

                            $previous_accumulated_depreciation = $previous_depreciation + $previous_accumulated_depreciation;
                            $previous_carrying_value_of_lease_asset = $previous_carrying_value_of_lease_asset - $previous_depreciation;
                            $increase_or_decrease = 0;
                        }
                    }
                }
                $start_year = $start_year + 1;
            }

            //echo "<pre>"; print_r($dates); die();

            //insert the dates data into the interest and depreciation table for the lease id
            if(is_null($modify_id)){
                InterestAndDepreciation::query()->where('asset_id', '=', $asset->id)->delete();
            } else {
                //need to delete the interest and depreciations after the first date in $dates array..
                if(!empty($dates)){
                    InterestAndDepreciation::query()->where('date', '>=', $dates[0]['date'])->delete();
                }
            }

            DB::transaction(function () use ($dates) {
                DB::table('interest_and_depreciation')->insert($dates);
            });

            return true;
        } else {
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
                $this->generateDataForInterestAndDepreciationTab($model, isset($data['modify_id'])?$data['modify_id']:null);
            }

            if ($lease_history) {
                confirmSteps($id, $this->current_step);
            }

            if(!$model->isSubsequentModification()){
                $ulacode = createUlaCode();
                $uid['uuid'] = $ulacode;
                $assets->setRawAttributes($uid);
                $assets->save();
            } else {
                $ulacode = $assets->uuid;
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


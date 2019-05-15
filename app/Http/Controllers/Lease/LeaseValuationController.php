<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 14/01/19
 * Time: 09:35 AM
 */

namespace App\Http\Controllers\Lease;

use App\HistoricalCarryingAmountAnnexure;
use App\Http\Controllers\Controller;
use App\InterestAndDepreciation;
use App\Lease;
use App\LeaseAssetPayments;
use App\LeaseAssets;
use App\PvCalculus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use DB;

class LeaseValuationController extends Controller
{
    private $current_step = 17;
    protected function validationRules()
    {
        return [
            'interest_rate' => 'required',
            'annual_average_esclation_rate' => 'required',
            'discount_rate_to_use' => 'required|numeric|min:2'
        ];
    }

    /**
     * renders the table to list all the lease assets.
     * $existing_lease_liability_balance need to add the interest rate as well to this variable.
     * @param $id Primary key for the lease
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.leasevaluation.index', ['id' => $id]),
                'title' => 'Lease Valuation'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if ($lease) {
            $asset_id = $lease->assets()->first()->id;
            //check if the Subsequent Valuation is applied for the lease modification
            $existing_lease_liability_balance = $existing_value_of_lease_asset = $existing_carrying_value_of_lease_asset = null;
            $subsequent_modify_required = $lease->isSubsequentModification();
            if($subsequent_modify_required){

                $previous_depreciation_data = InterestAndDepreciation::query()
                    ->where('date', '<', $lease->modifyLeaseApplication->last()->effective_from)
                    ->where('asset_id', '=', $asset_id)
                    ->orderBy('date','desc')
                    ->first();

                $one_day_before = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->subDay(1);

                //check a row exists on one day before in interest and depreciation
                $day_before_subsequent = InterestAndDepreciation::query()
                    ->where('date', '=', Carbon::parse($one_day_before))
                    ->where('asset_id', '=', $asset_id)
                    ->first();

                if($day_before_subsequent) {
                    $existing_lease_liability_balance = (float)$day_before_subsequent->closing_lease_liability;

                    $existing_value_of_lease_asset = $day_before_subsequent->value_of_lease_asset;
                    //Existing Carrying Value of Lease Asset
                    $existing_carrying_value_of_lease_asset = $day_before_subsequent->carrying_value_of_lease_asset;
                } else {
                    $existing_lease_liability_balance = (float)$previous_depreciation_data->closing_lease_liability;

                    //calculate the interest that needs to be added to $existing_lease_liability_balance value
                    $days_diff = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->subDay(1)->diffInDays($previous_depreciation_data->date);

                    $interest_expense = $this->calculateInterestExpense($existing_lease_liability_balance, $previous_depreciation_data->discount_rate, $days_diff);
                    $existing_lease_liability_balance = $existing_lease_liability_balance + $interest_expense;

                    $existing_value_of_lease_asset = $previous_depreciation_data->value_of_lease_asset;

                    //Existing Carrying Value of Lease Asset
                    $existing_carrying_value_of_lease_asset = $previous_depreciation_data->carrying_value_of_lease_asset;
                }

            }

            //Load the assets only which will  not in is_classify_under_low_value = Yes in NL10 (Lease Select Low Value)and will not in very short tem/short term lease in NL 8.1(lease_contract_duration table) and not in intengible under license arrangements and biological assets (lease asset categories)

            $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->get();

            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

            $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id', $category_excluded_id)->first();


            if($asset){
                // complete Step
                confirmSteps($lease->id, $this->current_step);

                $back_url = getBackUrl($this->current_step - 1, $id);
                //to get current step for steps form
                $current_step = $this->current_step;
                $payments = $asset->payments;

                //call function savePvCalculus to save the undiscounted and present value for all
                $this->saveUndiscountedValues($asset, $payments);

                //check if impairment is applicable or not
                $impairment_applicable = false;

                if(Carbon::parse($asset->accural_period)->lessThanOrEqualTo(getParentDetails()->accountingStandard->base_date) && !is_null($asset->accounting_treatment) && $asset->accounting_treatment !='2'){
                    $impairment_applicable = true;
                }

                //No Need to include the Non Lease Component Payments
                $payments = $asset->payments()->where('type', '<>', '2')->get();

                return view('lease.lease-valuation.index', compact(
                    'lease',
                    'asset',
                    'breadcrumbs',
                    'back_url',
                    'lessor_invoice',
                    'current_step',
                    'payments',
                    'impairment_applicable',
                    'subsequent_modify_required',
                    'existing_lease_liability_balance',
                    'existing_value_of_lease_asset',
                    'existing_carrying_value_of_lease_asset'
                ));
            } else {
                //redirect to the lease incentives step in case not applicable....
                return redirect(route('addlease.leasepaymentinvoice.index', ['id' => $lease->id]));
            }
        } else {
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
     * saves the undiscounted values to the respective tables for the lease asset...
     * @param LeaseAssets $asset
     * @param LeaseAssetPayments $payments
     */
    private function saveUndiscountedValues($asset, $payments){
        //loop through payments and save the values
        foreach ($payments as $payment) {
            $payment->setAttribute('undiscounted_value', $payment->undiscounted_liability_value);
            $payment->save();
        }

        //save the values for the termination
        if($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes" && $asset->terminationOption->termination_penalty_applicable == "yes"){
            $asset->terminationOption->setAttribute('undiscounted_value', $asset->terminationOption->termination_penalty);
            $asset->terminationOption->save();
        }

        //save the values for residual value guarantee
        if($asset->residualGuranteeValue->any_residual_value_gurantee == "yes"){
            $asset->residualGuranteeValue->setAttribute('undiscounted_value', $asset->residualGuranteeValue->residual_gurantee_value);
            $asset->residualGuranteeValue->save();
        }

        //save the values for the purchase options
        if($asset->purchaseOption && $asset->purchaseOption->purchase_option_clause == "yes" && $asset->purchaseOption->purchase_option_exerecisable == "yes"){
            $asset->purchaseOption->setAttribute('undiscounted_value', $asset->purchaseOption->purchase_price);
            $asset->purchaseOption->save();
        }
    }


    /**
     * find and returns the Present Value of Lease Liability
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function presentValueOfLeaseLiability($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);
                $payment_id = $request->has('payment')?$request->payment:null;
                $value = $asset->presentValueOfLeaseLiability(true, $payment_id);
                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * calculates and returns the historical present value of lease liability when the second method is applicable...
     * save the historical present value of lease liability as well to the lease_assets table...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historicalPresentValue($id, Request $request){
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);
                $value = $asset->presentValueOfLeaseLiability(true, null, true);

                $asset->historical_present_value_of_lease_liability =  $value;
                $asset->save();

                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * renders the pop up to show the present lease value calculation calculus here
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPresentValueOfLeaseLiabilityCalculus($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);
                $data = $asset->presentValueOfLeaseLiability(false);
                $years = $data['years'];
                $months = $data['months'];
                $liability_caclulus_data = $data['present_value_data'];
                //no need to consider the Non Lease Component Payments...
                $payments = $asset->payments()->where('type', '<>', '2')->get(); //need to take out the payments only where the due dates exists...
                return view('lease.lease-valuation._present_value_calculus', compact(
                    'years',
                    'months',
                    'liability_caclulus_data',
                    'payments'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * create or update the pv calculus for the lease asset....
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePresentValueOfLeaseLiabilityCalculus($id, Request $request){
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);
                $data = $asset->presentValueOfLeaseLiability(false);
                $data = json_encode($data);
                $model = PvCalculus::query()->where('asset_id', '=', $id)->first();
                if(is_null($model)){
                    $model = new PvCalculus();
                }
                $model->setRawAttributes([
                    'asset_id' => $id,
                    'calculus' => $data
                ]);
                $model->save();
                return response()->json([
                    'status' => true
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetch the present value for the termination option
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function terminationPresentValue($id, Request $request){
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);
                $lease = $asset->lease;
                $start_date =   Carbon::parse($asset->accural_period);
                $subsequent_modify_required = $lease->isSubsequentModification();
                if($subsequent_modify_required) {
                    $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                } else {
                    $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                }

                $value = $asset->getLeaseLiabilityForTermination($base_date);
                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;
                $asset->terminationOption->setAttribute('present_value', $value);
                $asset->terminationOption->save();

                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetches the present value for the residual value guarantee as well..
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function residualPresentValue($id, Request $request){
        try {
            if ($request->ajax()) {

                $asset = LeaseAssets::query()->findOrFail($id);

                $lease = $asset->lease;

                $start_date =   Carbon::parse($asset->accural_period);

                $subsequent_modify_required = $lease->isSubsequentModification();
                if($subsequent_modify_required) {
                    $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                } else {
                    $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                }

                $end_date = Carbon::parse($asset->getLeaseEndDate($asset));

                $value = $asset->getPresentValueOfResidualValueGuarantee($base_date, null, null, $end_date);

                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;

                $asset->residualGuranteeValue->setAttribute('present_value', $value);
                $asset->residualGuranteeValue->save();

                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * present value of the purchase options....
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchasePresentValue($id, Request $request){
        try {
            if ($request->ajax()) {

                $asset = LeaseAssets::query()->findOrFail($id);

                $lease = $asset->lease;

                $start_date =   Carbon::parse($asset->accural_period);

                $subsequent_modify_required = $lease->isSubsequentModification();
                if($subsequent_modify_required) {
                    $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                } else {
                    $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                    $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
                }

                $value = $asset->getPresentValueOfPurchaseOption($base_date, null, null);

                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;

                $asset->purchaseOption->setAttribute('present_value', $value);

                $asset->purchaseOption->save();

                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Returns the Lease Valuation value for the Lease on the Lease Valuation Sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function equivalentPresentValueOfLeaseLiability($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);

                if (!$request->has('lease_liability_value')) {
                    $present_value_of_lease_liability = $asset->presentValueOfLeaseLiability(true);
                } else {
                    $present_value_of_lease_liability = $request->lease_liability_value;
                }

                //save the present total value of lease liability
                $asset->setAttribute('lease_liablity_value', $present_value_of_lease_liability);
                $asset->save();

                $prepaid_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;

                $accured_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->accrued_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;

                $initial_direct_cost = isset($asset->initialDirectCost) ? ($asset->initialDirectCost->initial_direct_cost_involved == "yes" ? $asset->initialDirectCost->total_initial_direct_cost : 0) : 0;

                $lease_incentive_cost = isset($asset->leaseIncentives) ? ($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes" ? $asset->leaseIncentives->total_lease_incentives : 0) : 0;

                $dismantling_cost  = isset($asset->dismantlingCost) ? (($asset->dismantlingCost->cost_of_dismantling_incurred == "yes" && $asset->dismantlingCost->obligation_cost_of_dismantling_incurred == "yes") ? $asset->dismantlingCost->total_estimated_cost : 0) : 0;

                $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost + $dismantling_cost) - ($accured_lease_payment + $lease_incentive_cost);

                $asset->setAttribute('value_of_lease_asset', $value_of_lease_asset);
                $asset->setAttribute('adjustment_to_equity', null);
                $asset->save();

                //also delete the existing historical if any for the lease asset ...
                DB::table('historical_carrying_amount_annexure')->where('asset_id', '=', $asset->id)->delete();
                return response()->json([
                    'status' => true,
                    'value' => $value_of_lease_asset
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Returns the Lease Asset Impairment Value for the Lease on the Lease Valuation Sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaseAssetImpairment($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $asset = LeaseAssets::query()->findOrFail($id);

                if (!$request->has('lease_valuation_value')) {
                    $present_value_of_lease_liability = $asset->presentValueOfLeaseLiability(true);
                    $prepaid_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;
                    $accured_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->accrued_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;
                    $initial_direct_cost = isset($asset->initialDirectCost) ? ($asset->initialDirectCost->initial_direct_cost_involved == "yes" ? $asset->initialDirectCost->total_initial_direct_cost : 0) : 0;
                    $lease_incentive_cost = isset($asset->leaseIncentives) ? ($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes" ? $asset->leaseIncentives->total_lease_incentives0 : 0) : 0;

                    $dismantling_cost  = isset($asset->dismantlingCost) ? (($asset->dismantlingCost->cost_of_dismantling_incurred == "yes" && $asset->dismantlingCost->obligation_cost_of_dismantling_incurred == "yes") ? $asset->dismantlingCost->total_estimated_cost : 0) : 0;

                    $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost + $dismantling_cost) - ($accured_lease_payment + $lease_incentive_cost);

                } else {
                    $value_of_lease_asset = $request->lease_valuation_value;
                }

                $fair_market_value = isset($asset->fairMarketValue) ? ($asset->fairMarketValue->is_market_value_present == "yes" ? $asset->fairMarketValue->total_units : 0) : 0;

                $value = 0;

                if ($value_of_lease_asset > $fair_market_value && $fair_market_value > 0) {
                    $value = $value_of_lease_asset - $fair_market_value;
                }

                $asset->setAttribute('impairment_test_value', $value);
                $asset->save();

                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * updates the lease liability value and value of lease asset in case of subsequent modifications..
     * @param $asset_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAssetForSubsequent($asset_id, Request $request){
        try{
            if($asset_id) {
                $asset = LeaseAssets::query()->findOrFail($asset_id);
                $asset->setRawAttributes([
                    'lease_liablity_value' => $request->lease_liability_value,
                    'value_of_lease_asset'  => $request->value_of_lease_asset,
                    'increase_decrease' => $request->increase_decrease
                ]);
                $asset->save();
                return response()->json([
                    'status' => true
                ], 200);

            }
        } catch(\Exception $e){
            abort(404);
        }
    }

    /**
     * calculates the historical present value of lease payment per row on the annexure...
     * @param $days_diff
     * @param $payment
     * @param $discount_rate
     * @return float
     */
    private function calculateHistoricalPresentValueOfLeasePayment($days_diff, $payment, $discount_rate){
        return round($payment/ pow(1 + ($discount_rate * 1/100), $days_diff), 4);
    }

    /**
     * generate and save the historical annexure for the carrying amount details for the second method...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function carryingAmountDetails($id, Request $request){
        try{

            $validator = Validator::make($request->all(),[
                'prepaid_lease_payment' => 'required|numeric',
                'lease_liability_value' => 'required|numeric'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 200);
            }

            $asset = LeaseAssets::query()->findOrFail($id);
            $start_date = Carbon::parse($asset->lease_start_date);
            $end_date = Carbon::parse($asset->getLeaseEndDate($asset));
            $lease_payments = $asset->fetchAllPaymentsForAnnexure($asset, $start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
            $start_year  = $first_year = $start_date->format('Y');
            $end_year = $end_date->format('Y');
            $start_month = $start_date->format('m');
            $months = [];
            for ($m = 1; $m <= 12; ++$m) {
                $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
            }
            $discount_rate = $asset->leaseSelectDiscountRate->daily_discount_rate;
            $dates = [];
            $historical_accumulated_depreciation = 0;
            $carrying_value_of_lease_asset = $asset->historical_present_value_of_lease_liability;
            //calculate the depreciation here...
            $number_of_months = calculateMonthsDifference($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
            $historical_depreciation = round((float)$asset->historical_present_value_of_lease_liability/$number_of_months, 4);
            while ($start_year <= $end_year) {
                foreach ($months as $key => $month) {
                    if($start_year == $first_year && $key < $start_month){
                        continue;
                    }

                    //apply condition for the lease start date
                    //condition to check the below condition should be when the start date is on or after the base date
                    if($start_date->greaterThanOrEqualTo($start_date)){
                        $current_month_and_year_last_day = Carbon::create($start_year, $key, '1')->lastOfMonth();
                        if($start_date->greaterThan($current_month_and_year_last_day)){
                            continue;
                        }
                    }

                    //filter the above array with same month as for the current date and same year as for the current year
                    $payment_dates = array_where($lease_payments, function($value) use ($key, $start_year){
                        $date_month = Carbon::parse($value->date)->format('m');
                        $date_year = Carbon::parse($value->date)->format('Y');
                        return ($date_month == $key && $start_year == $date_year);
                    });

                    if($start_year == $first_year && $start_month == $key && empty($payment_dates) && !Carbon::parse($start_date)->isLastOfMonth()){
                        $days_diff = Carbon::parse($start_date)->diffInDays($start_date);
                        $sub_array = [
                            'asset_id' => $asset->id,
                            'year' => Carbon::parse($start_date)->format('Y'),
                            'date' => $start_date,
                            'days_diff' => $days_diff,
                            'payment_amount' => 0,
                            'present_value_of_lease_payment' => 0,
                            'historical_present_value_of_lease_payment' => $historical_accumulated_depreciation,
                            'historical_depreciation' => $historical_depreciation,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                        $sub_array['historical_accumulated_depreciation'] = $historical_accumulated_depreciation;
                        $sub_array['carrying_value_of_lease_asset'] = $carrying_value_of_lease_asset;
                        $dates[] = $sub_array;
                    }

                    foreach ($payment_dates as $payment_date) {
                        $days_diff = Carbon::parse($payment_date->date)->diffInDays($start_date);
                        $present_value_of_lease_payment = $this->calculateHistoricalPresentValueOfLeasePayment($days_diff, $payment_date->total_amount_payable, $discount_rate);
                        $sub_array = [
                            'asset_id' => $asset->id,
                            'year' => Carbon::parse($payment_date->date)->format('Y'),
                            'date' => $payment_date->date,
                            'days_diff' => $days_diff,
                            'payment_amount' => (float)$payment_date->total_amount_payable,
                            'present_value_of_lease_payment' => $present_value_of_lease_payment,
                            'historical_present_value_of_lease_payment' => $asset->historical_present_value_of_lease_liability,
                            'historical_depreciation' => $historical_depreciation,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];

                        if(Carbon::parse($payment_date->date)->isLastOfMonth()){
                            $sub_array['historical_accumulated_depreciation'] = $historical_accumulated_depreciation + $historical_depreciation;
                            $sub_array['carrying_value_of_lease_asset'] = $carrying_value_of_lease_asset -  $historical_depreciation;
                            $carrying_value_of_lease_asset = $carrying_value_of_lease_asset -  $historical_depreciation;
                            $historical_accumulated_depreciation = $historical_accumulated_depreciation + $historical_depreciation;
                        } else {
                            $sub_array['historical_accumulated_depreciation'] = $historical_accumulated_depreciation;
                            $sub_array['carrying_value_of_lease_asset'] = $carrying_value_of_lease_asset;
                        }

                        $dates[] = $sub_array;
                    }

                    if($end_date->format('Y') > $start_year){

                    } else if($end_date->format('Y') == $start_year && $end_date->format('m') >= $key) {

                    } else {
                        continue;
                    }

                    $current_date = Carbon::create($start_year, $key, '1')->lastOfMonth();
                    //find from array if any payment exists on this date
                    $payment_on_date = collect($lease_payments)->where('date', '=', $current_date->format('Y-m-d'))->values();
                    if(count($payment_on_date) == 0){
                        $amount_payable = 0;
                        $current_date = $current_date->format('Y-m-d');
                        //need to append the last day of month as well...
                        $days_diff = Carbon::parse($current_date)->diffInDays($start_date);

                        $sub_array = [
                            'asset_id' => $asset->id,
                            'year' => Carbon::parse($current_date)->format('Y'),
                            'date' => $current_date,
                            'days_diff' => $days_diff,
                            'payment_amount' => 0,
                            'present_value_of_lease_payment' => 0,
                            'historical_present_value_of_lease_payment' => $asset->historical_present_value_of_lease_liability,
                            'historical_depreciation' => $historical_depreciation,
                            'historical_accumulated_depreciation' => $historical_accumulated_depreciation + $historical_depreciation,
                            'carrying_value_of_lease_asset' => $carrying_value_of_lease_asset - $historical_depreciation,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];

                        $historical_accumulated_depreciation = $historical_accumulated_depreciation + $historical_depreciation;
                        $carrying_value_of_lease_asset = $carrying_value_of_lease_asset - $historical_depreciation;
                        $dates[] = $sub_array;

                    }

                }
                $start_year = $start_year + 1;
            }

            DB::transaction(function () use ($asset, $dates) {
                DB::table('historical_carrying_amount_annexure')->where('asset_id', '=', $asset->id)->delete();
                DB::table('historical_carrying_amount_annexure')->insert($dates);
            });

            //fetch the value on the one day before the base date and return the same to the response
            $one_day_before_base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date)->subDay(1);
            $data = HistoricalCarryingAmountAnnexure::query()
                ->where('asset_id', '=', $asset->id)
                ->where('date', '=', $one_day_before_base_date)
                ->first();

            //calculate the adjustment to equity as well.
            //save the value to the lease_assets database

            $prepaid_lease_payment = (float)$request->prepaid_lease_payment;
            $adjustment_to_equity   = (float)$data->carrying_value_of_lease_asset - (float)$request->lease_liability_value + (float)$prepaid_lease_payment;
            $asset->value_of_lease_asset = $data->carrying_value_of_lease_asset;
            $asset->lease_liablity_value = $request->lease_liability_value;
            $asset->adjustment_to_equity = $adjustment_to_equity;
            $asset->save();

            return response()->json([
                'status' => true,
                'value' => $data->carrying_value_of_lease_asset,
                'adjustment_to_equity' => $adjustment_to_equity
            ], 200);

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * returns the historical annexure for the lease asset
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHistoricalAnnexure($id){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $annexure = HistoricalCarryingAmountAnnexure::query()
                ->where('asset_id', '=', $asset->id)
                ->orderBy('date', 'asc')
                ->get();

            return view('lease.lease-valuation._historical_calculus_annexure', compact(
                'annexure',
                'asset'
            ));

        }catch (\Exception $e){
            abort(404);
        }
    }
}
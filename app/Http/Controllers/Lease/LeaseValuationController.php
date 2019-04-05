<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 14/01/19
 * Time: 09:35 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\LeaseDurationClassified;
use App\LeaseAssets;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Validator;

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

                //check if impairment is applicable or not
                $impairment_applicable = false;
                if(Carbon::parse($asset->accural_period)->lessThanOrEqualTo(getParentDetails()->accountingStandard->base_date) && !is_null($asset->accounting_treatment) && $asset->accounting_treatment !='2'){
                    $impairment_applicable = true;
                }

                return view('lease.lease-valuation.index', compact(
                    'lease',
                    'asset',
                    'breadcrumbs',
                    'back_url',
                    'lessor_invoice',
                    'current_step',
                    'payments',
                    'impairment_applicable'
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

                $asset->setAttribute('lease_liablity_value', $value);
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
                $payments = $asset->payments; //need to take out the payments only where the due dates exists...
                return view('lease.lease-valuation._present_value_calculus', compact(
                    'years',
                    'months',
                    'liability_caclulus_data','payments'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
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
                $start_date =   Carbon::parse($asset->accural_period);
                $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
                $base_date = ($start_date->lessThan($base_date))?$base_date:$start_date;
                $value = $asset->getLeaseLiabilityForTermination($base_date);
                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;
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

                $start_date =   Carbon::parse($asset->accural_period);

                $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);

                $base_date = ($start_date->lessThan($base_date))?$base_date:$start_date;

                $end_date = Carbon::parse($asset->getLeaseEndDate($asset));

                $value = $asset->getPresentValueOfResidualValueGuarantee($base_date, null, null, $end_date);

                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;

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

                $start_date =   Carbon::parse($asset->accural_period);

                $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);

                $base_date = ($start_date->lessThan($base_date))?$base_date:$start_date;

                $value = $asset->getPresentValueOfPurchaseOption($base_date, null, null);

                $value = isset($value['total_lease_liability'])?$value['total_lease_liability']:0;

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

                $prepaid_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;
                $accured_lease_payment = isset($asset->leaseBalanceAsOnDec) ? $asset->leaseBalanceAsOnDec->accrued_lease_payment_balance * $asset->leaseBalanceAsOnDec->exchange_rate : 0;
                $initial_direct_cost = isset($asset->initialDirectCost) ? ($asset->initialDirectCost->initial_direct_cost_involved == "yes" ? $asset->initialDirectCost->total_initial_direct_cost : 0) : 0;
                $lease_incentive_cost = isset($asset->leaseIncentives) ? ($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes" ? $asset->leaseIncentives->total_lease_incentives : 0) : 0;
                $dismantling_cost  = isset($asset->dismantlingCost) ? (($asset->dismantlingCost->cost_of_dismantling_incurred == "yes" && $asset->dismantlingCost->obligation_cost_of_dismantling_incurred == "yes") ? $asset->dismantlingCost->total_estimated_cost : 0) : 0;
                $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost + $dismantling_cost) - ($accured_lease_payment + $lease_incentive_cost);
                $asset->setAttribute('value_of_lease_asset', $value_of_lease_asset);
                $asset->save();
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
                    $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost) - ($accured_lease_payment + $lease_incentive_cost);
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
}
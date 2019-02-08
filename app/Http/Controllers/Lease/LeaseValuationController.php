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
use Illuminate\Http\Request;
use Validator;

class LeaseValuationController extends Controller
{
    protected function validationRules(){
        return [
            'interest_rate'   => 'required',
            'annual_average_esclation_rate' => 'required',
            'discount_rate_to_use' => 'required|numeric|min:2'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){
       $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.leasevaluation.index',['id' => $id]),
                'title' => 'Lease Valuation'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
            //Load the assets only which will  not in is_classify_under_low_value = Yes in NL10 (Lease Select Low Value)and will not in very short tem/short term lease in NL 8.1(lease_contract_duration table) and not in intengible under license arrangements and biological assets (lease asset categories)
            $own_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)
             ->where('specific_use',1)
             ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
            })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
            })->whereNotIn('category_id',[5,8])->get();

            $sublease_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)
            ->where('specific_use',2)
            ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
            })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
            })->whereNotIn('category_id',[5,8])->get();

             // complete Step
            confirmSteps($lease->id,'step16');

             $asset_on_lease_incentives = LeaseAssets::query()->where('lease_id', '=', $id)->where('lease_start_date','>=','2019-01-01')->count();
             if($asset_on_lease_incentives >0){
                $back_url =  route('addlease.leaseincentives.index', ['id' => $id]);  
             }
             else{
                $asset_on_inital = LeaseAssets::query()->where('lease_id', '=', $id)->where('lease_start_date', '>=', '2019-01-01')->count();
                if($asset_on_inital >0){
                    $back_url =  route('addlease.initialdirectcost.index', ['id' => $id]);
                }
                else{
                     $back_url =  route('addlease.balanceasondec.index', ['id' => $id]);
                }
            }



           
            return view('lease.lease-valuation.index', compact(
                'lease',
                'own_assets',
                'sublease_assets',
                'breadcrumbs',
                'back_url'
            ));
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
    public function presentValueOfLeaseLiability($id, Request $request){
        try{
            if($request->ajax()){
                $asset = LeaseAssets::query()->findOrFail($id);
                $value = $asset->presentValueOfLeaseLiability(true);

                $asset->setAttribute('lease_liablity_value', $value);
                $asset->save();
                return response()->json([
                    'status' => true,
                    'value' => $value
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e->getMessage());
            abort(404);
        }
    }

    /**
     * renders the pop up to show the present lease value calculation calculus here
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPresentValueOfLeaseLiabilityCalculus($id, Request $request){
        try{
            if($request->ajax()){
                $asset = LeaseAssets::query()->findOrFail($id);
                $data = $asset->presentValueOfLeaseLiability(false);
                $years = $data['years'];
                $months = $data['months'];
                $liability_caclulus_data = $data['present_value_data'];
                return view('lease.lease-valuation._present_value_calculus', compact(
                    'years',
                    'months',
                    'liability_caclulus_data'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Returns the Lease Valuation value for the Lease on the Lease Valuation Sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function equivalentPresentValueOfLeaseLiability($id , Request $request){
        try{
            if($request->ajax()){
                $asset = LeaseAssets::query()->findOrFail($id);
                if(!$request->has('lease_liability_value')){
                    $present_value_of_lease_liability = $asset->presentValueOfLeaseLiability(true);
                } else {
                    $present_value_of_lease_liability = $request->lease_liability_value;
                }
                $prepaid_lease_payment = isset($asset->leaseBalanceAsOnDec)?$asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance:0;
                $accured_lease_payment = isset($asset->leaseBalanceAsOnDec)?$asset->leaseBalanceAsOnDec->accrued_lease_payment_balance:0;
                $initial_direct_cost = isset($asset->initialDirectCost)?($asset->initialDirectCost->initial_direct_cost_involved == "yes"?$asset->initialDirectCost->total_initial_direct_cost:0):0;
                $lease_incentive_cost = isset($asset->leaseIncentives)?($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes"?$asset->leaseIncentives->total_lease_incentives0:0):0;
                $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost) - ($accured_lease_payment + $lease_incentive_cost);

                $asset->setAttribute('value_of_lease_asset', $value_of_lease_asset);
                $asset->save();

                return response()->json([
                    'status' => true,
                    'value' => $value_of_lease_asset
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e->getMessage());
            abort(404);
        }
    }

    /**
     * Returns the Lease Asset Impairment Value for the Lease on the Lease Valuation Sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaseAssetImpairment($id, Request $request){
        try{
            if($request->ajax()){
                $asset = LeaseAssets::query()->findOrFail($id);

                if(!$request->has('lease_valuation_value')){
                    $present_value_of_lease_liability = $asset->presentValueOfLeaseLiability(true);
                    $prepaid_lease_payment = isset($asset->leaseBalanceAsOnDec)?$asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance:0;
                    $accured_lease_payment = isset($asset->leaseBalanceAsOnDec)?$asset->leaseBalanceAsOnDec->accrued_lease_payment_balance:0;
                    $initial_direct_cost = isset($asset->initialDirectCost)?($asset->initialDirectCost->initial_direct_cost_involved == "yes"?$asset->initialDirectCost->total_initial_direct_cost:0):0;
                    $lease_incentive_cost = isset($asset->leaseIncentives)?($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes"?$asset->leaseIncentives->total_lease_incentives0:0):0;
                    $value_of_lease_asset = ($present_value_of_lease_liability + $prepaid_lease_payment + $initial_direct_cost) - ($accured_lease_payment + $lease_incentive_cost);
                } else {
                    $value_of_lease_asset = $request->lease_valuation_value;
                }

                $fair_market_value = isset($asset->fairMarketValue)?($asset->fairMarketValue->is_market_value_present == "yes"?$asset->fairMarketValue->total_units:0):0;

                $value = 0;

                if($value_of_lease_asset > $fair_market_value && $fair_market_value > 0){
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
        } catch (\Exception $e){
            abort(404);
        }
    }
}
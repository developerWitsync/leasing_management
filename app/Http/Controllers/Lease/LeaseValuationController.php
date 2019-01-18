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
        if(!checkPreviousSteps($id,'step15')){
               return redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
        }
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
            $lease_id = $lease->id;
            $step= 'step16';
            $complete_step16 = confirmSteps($lease_id,$step);
           
            return view('lease.lease-valuation.index', compact(
                'lease',
                'own_assets',
                'sublease_assets',
                'breadcrumbs'
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
                return response()->json([
                    'status' => true,
                    'value' => number_format($value, 2)
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e){
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
      
}
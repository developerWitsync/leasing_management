<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 07/01/19
 * Time: 10:30 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\Countries;
use App\ReportingCurrencySettings;
use App\ContractClassifications;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssets;
use App\LeaseAssetPayments;
use App\FairMarketValue;
use App\LeaseResidualValue;
use App\LeaseTerminationOption;
use App\LeaseRenewableOption;
use App\PurchaseOption;
use App\LeaseDurationClassified;
use Illuminate\Http\Request;
use Validator;

class ReviewSubmitController extends Controller
{
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request)
    {
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
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->get();
            $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
           


            return view('lease.review-submit.index', compact(
                'lease',
                'assets',
                'breadcrumbs',
                'reporting_currency_settings',
                'contract_classifications',
                'reporting_foreign_currency_transaction_settings'
            ));

        } else {
            abort(404);
        }
    }
  
  public function submit($id, Request $request)
  {
    if ($request->isMethod('post')) {

        $model = Lease::query()->where('id', '=', $id)->first();
        $model->status = "1";
        $model->save();
        // complete Step
        confirmSteps($id, 'step18');
        return redirect(route('addlease.reviewsubmit.index', ['id' => $id]))->with('status', 'Lease Information has been Submitted successfully.');
    }
  }
  public function getalldata($id, Request $request)
  {
    try {
            if ($request->ajax()) {

               $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first()->toArray();

               $underlyning_asset = LeaseAssets::query()->where('lease_id', '=', $id)->first()->toArray();
               $asset_id = $underlyning_asset['id'];

               $lease_payments = LeaseAssetPayments::query()->where('asset_id','=', $asset_id)->first()->toArray();

               $fair_market_value = FairMarketValue::query()->where('lease_id','=', $id)->first()->toArray();

               $residual_value = LeaseResidualValue::query()->where('lease_id','=', $id)->first()->toArray();

               $termination_option = LeaseTerminationOption::query()->where('lease_id', '=', $id)->first()->toArray();
               
               $renewal_option = LeaseRenewableOption::query()->where('lease_id','=', $id)->first()->toArray();

               $purchase_option = PurchaseOption::query()->where('lease_id', '=', $id)->first()->toArray();

               $duration_classified = LeaseDurationClassified::query()->where('lease_id', '=', $id)->first()->toArray();
               print_r($duration_classified);
               

               
              

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
               
              
            return response()->json([ 'status' => 1,'data' => $lease_id]); 
            } else { 
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }
 }   

    
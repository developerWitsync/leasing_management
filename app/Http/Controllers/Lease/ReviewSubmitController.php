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
use App\Countries;
use App\PaymentEscalationDates;
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
use Illuminate\Http\Request;
use Validator;

class ReviewSubmitController extends Controller
{
    private $current_step = 18;
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request)
    {
        try{
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
        }catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * submit the lease here.
     * generate the lease history data and save the details lease_history table...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit($id, Request $request)
    {
        if ($request->isMethod('post')) {

            $model = Lease::query()->where('id', '=', $id)->first();
            $model->status = "1";
            $model->save();


            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first()->toArray();

            $underlyning_asset = LeaseAssets::query()->where('lease_id', '=', $id)->first()->toArray();

            $asset_id = $underlyning_asset['id'];

            $assets = LeaseAssets::query()->findOrFail($asset_id);

            $payment_id = $assets->paymentsduedate->pluck('payment_id')->toArray();

            $lease_payments = LeaseAssetPayments::query()->where('asset_id', '=', $asset_id)->first()->toArray();

            $fair_market_value = FairMarketValue::query()->where('lease_id', '=', $id)->first()->toArray();

            $residual_value = LeaseResidualValue::query()->where('lease_id', '=', $id)->first()->toArray();

            $termination_option = LeaseTerminationOption::query()->where('lease_id', '=', $id)->first()->toArray();

            $renewal_option = LeaseRenewableOption::query()->where('lease_id', '=', $id)->first();
            if($renewal_option) {
                $renewal_option = $renewal_option->toArray();
            }

            $purchase_option = PurchaseOption::query()->where('lease_id', '=', $id)->first();
            if($purchase_option) {
                $purchase_option = $purchase_option->toArray();
            }

            $duration_classified = LeaseDurationClassified::query()->where('lease_id', '=', $id)->first();
            if($duration_classified) {
                $duration_classified = $duration_classified->toArray();
            }

            $payment_esclation_details = PaymentEscalationDetails::query()->where('lease_id', '=', $id)->first();
            if($payment_esclation_details) {
                $payment_esclation_details = $payment_esclation_details->toArray();
            }

            // esclation Payments with due date
            $payment_due_dates = LeaseAssetPaymenetDueDate::query()->whereIn('payment_id', $payment_id)->get()->toArray();

            $escalation_dates = PaymentEscalationDates::query()->whereIn('payment_id', $payment_id)->get()->toArray();

            $low_value = LeaseSelectLowValue::query()->where('lease_id', '=', $id)->first();
            if($low_value) {
                $low_value = $low_value->toArray();
            }

            $discount_rate = LeaseSelectDiscountRate::query()->where('lease_id', '=', $id)->first();
            if($discount_rate){
                $discount_rate = $discount_rate->toArray();
            }

            $lease_balance = LeaseBalanceAsOnDec::query()->where('lease_id', '=', $id)->first();
            if($lease_balance){
                $lease_balance = $lease_balance->toArray();
            }

            $initial_direct_cost = InitialDirectCost::query()->where('lease_id', '=', $id)->first();

            //get supplier details
            if($initial_direct_cost){
                $initial_direct_cost_id = $assets->initialDirectCost->pluck('id')->toArray();
                $supplier_details = SupplierDetails::query()->whereIn('initial_direct_cost_id', $initial_direct_cost_id)->get()->toArray();
            } else {
                $supplier_details = [];
            }

            $lease_incentives = LeaseIncentives::query()->where('lease_id', '=', $id)->first();

            if($lease_incentives){
                //get customer details
                $lease_incentive_id = $assets->leaseIncentiveCost->pluck('id')->toArray();

                $customer_details = CustomerDetails::query()->whereIn('lease_incentive_id', $lease_incentive_id)->get()->toArray();
            } else {
                $customer_details = [];
            }

            $lease_invoice = LeasePaymentInvoice::query()->where('lease_id', '=', $id)->first()->toArray();


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

           
            $record['initial_direct_cost'] = $initial_direct_cost;
            
            $record['initial_direct_cost']['supplier_details']= $supplier_details;

            $record['initial_direct_cost'] = ($initial_direct_cost)?$initial_direct_cost->toArray():[];

            //lease incentives step 15
            $record['lease_incentives'] = ($lease_incentives)?$lease_incentives->toArray():[];

            $record['lease_incentives']['customer_details'] = $customer_details;

            //lease valaution step 16 is only for caculate present value lease liability

            // lessor invoice step 17
            $record['lessor_invoice'] = $lease_invoice;

            //save the record in lease history
            $data = $request->except('_token');
            $data['lease_id'] = $id;
            $data['json_data_steps'] = json_encode($record);
            $data['esclation_payments'] = json_encode($escalation_dates);
            $data['payment_anxure'] = json_encode($payments);

            if(count($model->modifyLeaseApplication) > 0){
                $data['modify_id'] = $model->modifyLeaseApplication->last()->id;
            }

            if(count($model->modifyLeaseApplication) > 0 && $model->modifyLeaseApplication->last()->valuation == "Modify Initial Valuation"){
                //fetch the current history and update the same..
                $lease_history = LeaseHistory::query()->where('lease_id', '=', $id)->first();
                unset($data['modify_id']);
                $lease_history->setRawAttributes($data);
                $lease_history->save();
            } else {
                $lease_history = LeaseHistory::create($data);
            }

            if ($lease_history) {
                // complete Step
                confirmSteps($id, $this->current_step);
            }
            
            $ulacode = createUlaCode();
            $uid['uuid'] = $ulacode;
            $assets->setRawAttributes($uid);
            $assets->save();

            return redirect(route('leasevaluation.index'))->with('status', $ulacode);
        }
    }

}

    
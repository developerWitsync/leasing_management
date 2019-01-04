<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 2/1/19
 * Time: 5:32 PM
 */

namespace App\Http\Controllers\Lease;


use App\ContractEscalationBasis;
use App\EscalationPercentageSettings;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssetPayments;
use App\PaymentEscalationDetails;
use App\RateTypes;
use Illuminate\Http\Request;
use Validator;

class EscalationController extends Controller
{
    /**
     * Renders the index view for the Lease Escalation Clause
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if($lease){

                //take out the payments for every lease asset
                //$payments = LeaseAssetPayments::query()->whereIn('asset_id', $lease->assets->pluck('id')->toArray())->with('asset')->orderBy('asset_id', 'desc')->get();
                return view('lease.escalation.index', compact(
                    'lease'
                ));

            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * update the escalation applicable status for the lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLeaseEscalationApplicableStatus($id, Request $request) {
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if($lease) {
                $validator = Validator::make($request->except('_token'), [
                    'escalation_clause_applicable' => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors());
                }

                $lease->escalation_clause_applicable = $request->escalation_clause_applicable;

                if($lease->save()){
                    return redirect()->back()->with('status', 'Escalation Clause has been saved. Please proceed further and provide the details for the escalations.');
                }

            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @param $id Payment Id
     * @param $lease Lease Id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, $lease, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease)->first();
            if($lease) {
                $payment = LeaseAssetPayments::query()->findOrFail($id);
                $model   =  new PaymentEscalationDetails();
                $lease_end_date = $payment->asset->getLeaseEndDate($payment->asset);
                $contract_escalation_basis = ContractEscalationBasis::query()->get();
                $percentage_rate_types  = RateTypes::query()->get();
                $escalation_percentage_settings = EscalationPercentageSettings::query()->whereIn('business_account_id', getDependentUserIds())->where('number', '<>', '0')->get();
                return view('lease.escalation.create', compact(
                    'payment',
                    'lease',
                    'model',
                    'lease_end_date',
                    'contract_escalation_basis',
                    'percentage_rate_types',
                    'escalation_percentage_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * @todo need to create a common function on helpers to generate all the escalations based upon the inputs of the escalation form and payment details
     * @param $id Payment ID
     * @param Request $request
     */
    public function escalationChart($id, Request $request){
        try{
            if($request->ajax()) {
                $payment = LeaseAssetPayments::query()->findOrFail($id);
                $asset =  $payment->asset;
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
                if($lease) {

                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        }catch (\Exception $e){
            dd($e);
        }
    }
}
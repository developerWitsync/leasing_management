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
use Carbon\Carbon;
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

                if($request->isMethod('post')){
                    $rules = [
                        'is_escalation_applicable'  => 'required',
                        'effective_from'    => 'required_if:is_escalation_applicable,yes',
                        'escalation_basis'  => 'required_if:is_escalation_applicable,yes',
                        'escalation_rate_type' => 'required_if:escalation_basis,1',
                        'is_escalation_applied_annually_consistently'   => 'required_if:is_escalation_applicable,yes',
                        'total_escalation_rate' => 'required_if:escalation_basis,1',
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '2') {
                            $rules['escalated_amount'] = 'required|numeric|min:1';
                        }
                    }

                    $validator = Validator::make($request->except('_token', 'method', 'uri', 'ip'), $rules);

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    if($request->has('effective_from') && $request->effective_from!=""){
                        $request->request->add(['effective_from' => Carbon::parse($request->effective_from)->format('Y-m-d'), 'lease_id' => $lease->id, 'asset_id' => $payment->asset_id, 'payment_id'=> $payment->id]);
                    }

                    $request->request->add(['lease_id' => $lease->id, 'asset_id' => $payment->asset_id, 'payment_id'=> $payment->id]);

                    $model->setRawAttributes($request->except('_token'));
                    if($model->save()){
                        //need to save the escalation dates along with all the
                        
                    }
                }

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
     * generate the escalation chart for the requested parameters as well.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function escalationChart($id, Request $request){
        try{
            if($request->ajax()) {
                $payment = LeaseAssetPayments::query()->findOrFail($id);
                $asset =  $payment->asset;
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
                if($lease) {

                    $rules = [
                        'is_escalation_applicable'  => 'required',
                        'effective_from'    => 'required_if:is_escalation_applicable,yes',
                        'escalation_basis'  => 'required_if:is_escalation_applicable,yes',
                        'escalation_rate_type' => 'required_if:escalation_basis,1',
                        'is_escalation_applied_annually_consistently'   => 'required_if:is_escalation_applicable,yes',
                        'total_escalation_rate' => 'required_if:escalation_basis,1',
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '2') {
                            $rules['escalated_amount'] = 'required|numeric|min:1';
                        }
                    }

                    $validator = Validator::make($request->except('_token', 'method', 'uri', 'ip'), $rules);

                    $errors = [];
                    if($validator->fails()) {
                        $errors = $validator->errors();
                        return view('lease.escalation._chart',compact(
                            'errors'
                        ));
                    }
                    $requestData = $request->except('_token', 'method', 'uri', 'ip');
                    $escalationData = generateEsclationChart($request->except('_token', 'method', 'uri', 'ip'), $payment, $lease, $asset);
                    $years = $escalationData['years'];
                    $months = $escalationData['months'];
                    $escalations = $escalationData['escalations'];

                    return view('lease.escalation._chart',compact(
                        'errors',
                        'lease',
                        'asset',
                        'years',
                        'months',
                        'escalations',
                        'requestData'
                    ));

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

    /**
     * calculates the computed undiscouonted total payments so that it can be populated on the escalation form
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function computeTotalUndiscountedPayment($id, Request $request){
        try{
            if($request->ajax()) {
                $payment = LeaseAssetPayments::query()->findOrFail($id);
                $asset =  $payment->asset;
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
                if($lease) {

                    $rules = [
                        'is_escalation_applicable'  => 'required',
                        'effective_from'    => 'required_if:is_escalation_applicable,yes',
                        'escalation_basis'  => 'required_if:is_escalation_applicable,yes',
                        'escalation_rate_type' => 'required_if:escalation_basis,1',
                        'is_escalation_applied_annually_consistently'   => 'required_if:is_escalation_applicable,yes',
                        'total_escalation_rate' => 'required_if:escalation_basis,1',
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                        }

                        if($request->escalation_basis == '2') {
                            $rules['escalated_amount'] = 'required|numeric|min:1';
                        }
                    }

                    $validator = Validator::make($request->except('_token', 'method', 'uri', 'ip'), $rules);

                    $errors = [];
                    if($validator->fails()) {
                        $errors = $validator->errors();
                        return response()->json([
                            'status' => false,
                            'errors' => $errors
                        ], 200);
                    }

                    $escalationData = generateEsclationChart($request->except('_token', 'method', 'uri', 'ip'), $payment, $lease, $asset);

                    $escalations = $escalationData['escalations'];
                    //calculate the total amount here
                    $total = 0;
                    foreach ($escalations as $escalation){
                        foreach ($escalation as $month){
                            $total += $month['amount'];
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'computed_total' => $total
                    ], 200);

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
<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 2/1/19
 * Time: 5:32 PM
 */

namespace App\Http\Controllers\Lease;


use App\ContractEscalationBasis;
use App\EscalationFrequency;
use App\EscalationPercentageSettings;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssetPayments;
use App\PaymentEscalationDates;
use App\PaymentEscalationDetails;
use App\PaymentEscalationInconsistentData;
use App\LeaseDurationClassified;
use App\RateTypes;
use App\LeaseAssetPaymentDates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class EscalationController extends Controller
{
    private $current_step = 9;
    /**
     * Renders the index view for the Lease Escalation Clause
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){
       try{

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('lease.escalation.index',['id' => $id]),
                    'title' => 'Lease Escalations'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if($lease){

                $asset = $lease->assets()->first();

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                //take out the payments for every lease asset
                $payments = LeaseAssetPayments::query()->whereIn('asset_id', $lease->assets->pluck('id')->toArray())
                    ->with('asset')
                    ->with('paymentEscalations')
                    ->orderBy('asset_id', 'desc')->get();

                $show_next = false;
                $required_escalations = 0;
                $completed_escalations = 0;
                if($lease->escalation_clause_applicable == "no") {
                    confirmSteps($lease->id,$this->current_step);
                    $show_next = true;
                } else {
                    foreach ($payments as $payment){
                        $required_escalations = $required_escalations + 1;
                        if(count($payment->paymentEscalations) > 0){
                            $completed_escalations = $completed_escalations + 1;
                        }
                    }
                }

                if($lease->escalation_clause_applicable == "yes") {
                    if($required_escalations == $completed_escalations){
                        $show_next = true;
                    }
                }

                //find the back_url and have to check if there were any assets on the lease duration classified
                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->where('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset_on_duration_classified = \App\LeaseAssets::query()->where('lease_id', '=', $id)
                    ->whereNotIn('category_id', $category_excluded_id)->count();

                if($asset_on_duration_classified > 0){
                    $back_url = route('addlease.durationclassified.index', ['id' => $id]);
                } else {
                    $back_url = route('addlease.residual.index', ['id' => $id]);
                }

                //to get current step for steps form
                $current_step = $this->current_step;

                return view('lease.escalation.index', compact(
                    'lease',
                    'show_next',
                    'breadcrumbs',
                    'subsequent_modify_required',
                    'back_url',
                    'current_step',
                    'asset'
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

                if($request->escalation_clause_applicable == "no"){
                    confirmSteps($lease->id,$this->current_step);
                } else {
                    \App\LeaseCompletedSteps::query()->where('lease_id', '=',$lease->id)->where('completed_step', '=', 10)->delete();
                }

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
     * creates or updates the payment escalations from the same function
     * @param $id Payment Id
     * @param $lease Lease Id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, $lease, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease)->first();
            if($lease) {
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $payment = LeaseAssetPayments::query()
                    ->with(['paymentDueDates' => function($query){
                        $query->where('total_payment_amount', '>', 0);
                    }])
                    ->findOrFail($id);
                $asset =  $payment->asset;
                $model   =  PaymentEscalationDetails::query()->where('payment_id','=', $id)->first();
                if(is_null($model)) {
                    $model   =  new PaymentEscalationDetails();
                }

                $inconsistentDataModel = PaymentEscalationInconsistentData::query()->where('payment_id', '=', $payment->id)->first();

                if($request->isMethod('post')){
                    $rules = [
                        'is_escalation_applicable'  => 'required',
                        'effective_from'    => 'required_if:is_escalation_applicable,yes',
                        'escalation_basis'  => 'required_if:is_escalation_applicable,yes',
                        'escalation_rate_type' => 'required_if:escalation_basis,1',
                        'is_escalation_applied_annually_consistently'   => 'required_if:is_escalation_applicable,yes',
                        'total_undiscounted_lease_payment_amount'   => 'required_if:is_escalation_applicable,yes',
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
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

                    $data  = $request->except('_token', 'inconsistent_escalation_frequency', 'inconsistent_effective_date', 'inconsistent_amount_based_currency', 'inconsistent_escalated_amount', 'inconsistent_current_variable_rate', 'inconsistent_total_escalation_rate', 'inconsistent_fixed_rate');

                    if($request->is_escalation_applicable == "no"){
                        $data['effective_from'] = null;
                        $data['escalation_basis'] = null;
                        $data['escalation_rate_type'] = null;
                        $data['is_escalation_applied_annually_consistently'] = null;
                        $data['fixed_rate'] = null;
                        $data['current_variable_rate'] = null;
                        $data['total_escalation_rate'] = null;
                        $data['amount_based_currency'] = null;
                        $data['escalated_amount'] = null;
                        $data['escalation_currency'] = null;
                        $data['total_undiscounted_lease_payment_amount'] = null;
                    }

                    $model->setRawAttributes($data);
                    if($model->save()){

                        if(is_null($inconsistentDataModel)) {
                            $inconsistentDataModel = new PaymentEscalationInconsistentData();
                        }

                        if($request->is_escalation_applied_annually_consistently == 'no') {
                            //have to save the data for the inconsistently applied to payments_escalation_inconsistent_inputs
                            $inconsistent_array = [];
                            $inconsistent_array['inconsistent_escalation_frequency'] = $request->inconsistent_escalation_frequency;
                            $inconsistent_array['inconsistent_effective_date'] = $request->inconsistent_effective_date;

                            if($request->escalation_basis == '2'){
                                $inconsistent_array['inconsistent_amount_based_currency'] = $request->inconsistent_amount_based_currency;
                            }

                            $inconsistent_array['inconsistent_current_variable_rate']   = $request->inconsistent_current_variable_rate;
                            $inconsistent_array['inconsistent_total_escalation_rate']   = $request->inconsistent_total_escalation_rate;
                            $inconsistent_array['inconsistent_fixed_rate']   = $request->inconsistent_fixed_rate;

                            $inconsistent_array['inconsistent_escalated_amount']    = $request->inconsistent_escalated_amount;

                            $inconsistent_array = serialize($inconsistent_array);
                            $inconsistentDataModel->payment_id = $payment->id;
                            $inconsistentDataModel->inconsistent_data = $inconsistent_array;
                            $inconsistentDataModel->save();
                        } elseif($inconsistentDataModel) {
                            $inconsistentDataModel->delete();
                        }

                        //delete all the previous escalation dates for the payment if exists
                        PaymentEscalationDates::query()->where('payment_id', '=', $payment->id)->delete();

                        //need to save the escalation dates along with all the
                        if($request->is_escalation_applicable == 'yes'){
                            $escalationData = generateEsclationChart($request->except('_token', 'method', 'uri', 'ip'), $payment, $lease, $asset);
                            foreach ($escalationData['escalations'] as $year=>$escalations){
                                foreach ($escalations as $month=>$escalation){
                                    $data = [
                                        'payment_id' => $payment->id,
                                        'escalation_year'   => $year,
                                        'escalation_month'  => date('m', strtotime($month)),
                                        'percentage_or_amount_based'    => ($request->escalation_basis=='2')?'amount':'percentage',
                                        'value_escalated'   => $escalation['percentage'],
                                        'total_amount_payable'  => $escalation['amount']
                                    ];
                                    PaymentEscalationDates::create($data);
                                }
                            }
                        }
                        // complete Step
                        confirmSteps($lease->id,$this->current_step);

                        return redirect(route('lease.escalation.index', ['id' =>$lease ]))->with('status', 'Escalation Details has been saved sucessfully.');
                    }
                }

                //code for the inconsistent escalations to be applied
                $start_date = $asset->accural_period; //start date with the free period
                $end_date   = $asset->getLeaseEndDate($asset); //end date based upon all the conditions
                $base_date =  getParentDetails()->accountingStandard->base_date;
                $start_date = ($asset->using_lease_payment == '1')?\Carbon\Carbon::parse($base_date):\Carbon\Carbon::parse($start_date);
                $end_date   = \Carbon\Carbon::parse($end_date);

                $years =  [];
                $start_year = $start_date->format('Y');
                $end_year = $end_date->format('Y');

                if($start_year == $end_year) {
                    $years[] = $end_year;
                } else if($end_year > $start_year) {
                    $years = range($start_year, $end_year);
                }

                $lease_end_date = $payment->asset->getLeaseEndDate($payment->asset);
                $contract_escalation_basis = ContractEscalationBasis::query()->get();
                $percentage_rate_types  = RateTypes::query()->get();
                $escalation_percentage_settings = EscalationPercentageSettings::query()->whereIn('business_account_id', getDependentUserIds())->where('number', '<>', '0')->get();
                $escalation_frequency = EscalationFrequency::all();
                $paymentDueDates = $payment->paymentDueDates->pluck('date')->toArray();
                $current_step = $this->current_step;
                //lease asset payment dates
                $payment_dates = LeaseAssetPaymentDates::query()
                    ->where('total_payment_amount','>', 0)
                    ->where('asset_id',$payment->asset_id)->get();
                
                return view('lease.escalation.create', compact(
                    'payment',
                    'lease',
                    'model',
                    'lease_end_date',
                    'contract_escalation_basis',
                    'percentage_rate_types',
                    'escalation_percentage_settings',
                    'years',
                    'escalation_frequency',
                    'paymentDueDates',
                    'inconsistentDataModel',
                    'subsequent_modify_required',
                    'current_step',
                    'payment_dates'
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
                        'total_undiscounted_lease_payment_amount'   => 'required'
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
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
                        'is_escalation_applied_annually_consistently'   => 'required_if:is_escalation_applicable,yes'
                    ];

                    if($request->is_escalation_applicable == "yes" && $request->is_escalation_applied_annually_consistently == "yes") {
                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '1' || $request->escalation_rate_type == '3')) {
                            $rules['fixed_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
                        }

                        if($request->escalation_basis == '1' && ($request->escalation_rate_type == '2' || $request->escalation_rate_type == '3')) {
                            $rules['current_variable_rate'] = 'required|numeric';
                            $rules['total_escalation_rate'] = 'required_if:escalation_basis,1';
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
            abort(404);
        }
    }

    /**
     * Genearate the
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paymentAnnexure($id, Request $request){
        try{
            if($request->ajax()) {
                $payment = LeaseAssetPayments::query()->findOrFail($id);
                $asset =  $payment->asset;
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
                if($lease) {
                    if($request->is_escalation_applicable == "no") {
                        $escalationData = generateEsclationChart($request->except('_token', 'method', 'uri', 'ip'), $payment, $lease, $asset);
                        $years = $escalationData['years'];
                        $months = $escalationData['months'];
                        $escalations = $escalationData['escalations'];
                        $requestData = $request->except('_token', 'method', 'uri', 'ip');
                        $errors = [];
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
            } else {
                abort(404);
            }
        }catch (\Exception $e){
            dd($e);
        }
    }
}
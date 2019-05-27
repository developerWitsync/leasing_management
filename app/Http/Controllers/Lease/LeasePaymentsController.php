<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 27/12/18
 * Time: 2:28 PM
 */

namespace App\Http\Controllers\Lease;


use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssetPaymenetDueDate;
use App\LeaseAssetPayments;
use App\LeaseAssetPaymentsNature;
use App\LeaseAssets;
use App\LeasePaymentsBasis;
use App\LeasePaymentsFrequency;
use App\LeasePaymentsInterval;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Validator;

class LeasePaymentsController extends Controller
{
    private $current_step = 6;
    /**
     * validation rules for the create and update payments
     * @param bool $create
     * @return array
     */
    protected function validationRules($create = true)
    {
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'nature' => 'required',
            'variable_basis' => 'required_if:nature,2',
            'variable_amount_determinable' => 'required_if:nature,2',
            'payment_interval' => 'required',
            'payout_time' => 'required',
            'first_payment_start_date' => 'required|date_format:d-M-Y',
            'last_payment_end_date' => 'required|date_format:d-M-Y',
            'payment_currency' => 'required',
            'similar_chateristics_assets' => 'required|numeric',
            'payment_per_interval_per_unit' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
            'total_amount_per_interval' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
            'attachment' => config('settings.file_size_limits.file_rule'),
            'due_dates_confirmed' => 'in:1',
            'lease_payment_per_interval' => 'required',
            'altered_payment_due_date.*' => 'required|date',
            'inconsistent_date_payment.*' => 'required_if:lease_payment_per_interval,2|numeric'
        ];

        return $rules;
    }

    /**
     * Renders the form to select the total number of the payments for the asset and renders the table to list all the asset payments as well.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        try {

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if ($lease) {

                $asset = $lease->assets()->first();

                $breadcrumbs = [
                    [
                        'link' => route('add-new-lease.index'),
                        'title' => 'Add New Lease'
                    ],
                    [
                        'link' => route('addlease.payments.index', ['id' => $id]),
                        'title' => 'Lease Payments'
                    ]
                ];

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $is_subsequent = $subsequent_modify_required;

                $asset = LeaseAssets::query()->where('lease_id', '=', $id)->where('id', '=', $asset->id)->first();
                if ($asset) {

                    $completed_payments = 0;
                    $required_payments = 0;
                    foreach ($lease->assets as $asset) {
                        $required_payments += $asset->total_payments;
                        if ($asset->total_payments > 0 && count($asset->payments) > 0) {
                            if($subsequent_modify_required){
                                $completed_payments += count($asset->payments()->where('subsequent_status', '=', '1')->get());
                            } else {
                                $completed_payments += count($asset->payments);
                            }
                        } else {
                            $required_payments += 1; //incrementing by one to not show the next button
                            break;
                        }
                    }

                    $show_next = $required_payments == $completed_payments;

                    //find the back button URL for the lease payments step
                    $asset_on_purchase_option_and_renewal = LeaseAssets::query()->where('lease_id', '=', $id)->whereHas('terminationOption', function ($query) {
                        $query->where('lease_termination_option_available', '=', 'yes');
                        $query->where('exercise_termination_option_available', '=', 'no');
                    })->count();

                    if($asset_on_purchase_option_and_renewal){
                        $back_url = route('addlease.purchaseoption.index', ['id' => $id]);
                    } else {
                        $back_url = route('addlease.leaseterminationoption.index', ['id' => $id]);
                    }

                    $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id', 'number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    $current_step = $this->current_step;
                    return view('lease.payments.index', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments',
                        'breadcrumbs',
                        'subsequent_modify_required',
                        'current_step',
                        'show_next',
                        'back_url',
                        'is_subsequent'
                    ));

                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Create the Asset Payment for the asset and redirects the user based upon the action button that has been pressed
     * @param $id Asset Id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAssetPayments($id, Request $request)
    {
        try {

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $asset->lease;
            //check if the Subsequent Valuation is applied for the lease modification
            $subsequent_modify_required = false; //it will remain false as the users will be creating new payment here and they will provide all the details here.
            $is_subsequent = $lease->isSubsequentModification();

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.payments.index', ['id' => $asset->lease->id]),
                    'title' => 'Lease Payments'
                ],
                [
                    'link' => route('lease.payments.createassetpayment', ['id' => $id ]),
                    'title' => 'Create Lease Asset Payment'
                ]
            ];

            $payment = new LeaseAssetPayments();

            //check if the already created payments are less than the total payments.
            if (!(count($asset->payments) < $asset->total_payments)) {
                return redirect()->back()->with('error', 'You cannot add more payments to this asset.');
            }

            if ($request->isMethod('post')) {
                Validator::extend('required_when_variable_determinable', function($attribute, $value, $parameters, $validator) use ($request){
                    $nature = array_get($validator->getData(), $parameters[0], null);
                    $amount_determinable = array_get($validator->getData(), $parameters[1], null);
                    $required = true;
                    if($nature == "2" && $amount_determinable == "yes" || $nature == "1"){
                        //this means that the field is required...
                        $required = true;
                    } elseif($nature == "2" && $amount_determinable == "no") {
                        //the field is not required
                        $required = false;
                    }
                    if($required){
                        if($attribute == "due_dates_confirmed"){
                            return trim($value!="") && $value == '1';
                        } else {
                            return trim($value!="");
                        }
                    } else {
                        return true;
                    }
                });

                $rules = [
                    'name' => 'required',
                    'type' => 'required',
                    'nature' => 'required',
                    'variable_basis' => 'required_if:nature,2',
                    'variable_amount_determinable' => 'required_if:nature,2',
                    'payment_interval' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                    'payout_time' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                    'first_payment_start_date' => 'required_when_variable_determinable:nature,variable_amount_determinable|date_format:d-M-Y|nullable',
                    'last_payment_end_date' => 'required_when_variable_determinable:nature,variable_amount_determinable|date_format:d-M-Y|nullable',
                    'payment_currency' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                    'similar_chateristics_assets' => 'required_when_variable_determinable:nature,variable_amount_determinable|numeric',
                    'payment_per_interval_per_unit' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
                    'total_amount_per_interval' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
                    'attachment' => config('settings.file_size_limits.file_rule'),
                    'due_dates_confirmed' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                    'lease_payment_per_interval' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                    'altered_payment_due_date.*' => 'required_when_variable_determinable:nature,variable_amount_determinable|date|nullable',
                    'inconsistent_date_payment.*' => 'required_if:lease_payment_per_interval,2|numeric|nullable'
                ];

                $validator = Validator::make($request->except('_token'), $rules, [
                    'required_when_variable_determinable' => 'The :attribute field is required.',
                    'altered_payment_due_date.*.required' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                    'due_dates_confirmed.required_when_variable_determinable' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                    'file.max' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.',
                    'file.uploaded' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                }

                $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit', 'altered_payment_due_date', 'due_dates_confirmed', 'inconsistent_date_payment');
                $create_payment_dates = true;
                if($data['nature'] == '2' && $data['variable_amount_determinable'] == "no"){
                    $data['payment_interval'] = null;
                    $data['payout_time'] = null;
                    $data['first_payment_start_date'] = null;
                    $data['last_payment_end_date'] = null;
                    $data['payment_currency'] = null;
                    $data['lease_payment_per_interval'] = null;
                    $data['payment_per_interval_per_unit'] = null;
                    $data['total_amount_per_interval'] = null;
                    $create_payment_dates = false;
                } else {
                    $data['first_payment_start_date'] = Carbon::parse($request->first_payment_start_date)->format('Y-m-d');
                    $data['last_payment_end_date'] = Carbon::parse($request->last_payment_end_date)->format('Y-m-d');
                }

                $data['attachment'] = "";
                $data['asset_id'] = $asset->id;

                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $uniqueFileName = uniqid() . $file->getClientOriginalName();
                    $request->file('attachment')->move('uploads', $uniqueFileName);
                    $data['attachment'] = $uniqueFileName;
                }

                if($is_subsequent){
                    //update the subsequent_status field to 1 as well..
                    $data['subsequent_status'] = '1';
                }

                $payment->setRawAttributes($data);

                if ($payment->save()) {

                    //code to create the due dates for the payment
                    LeaseAssetPaymenetDueDate::query()->where('asset_id', '=', $id)->where('payment_id', '=', $payment->id)->delete();
                    if($create_payment_dates){
                        if($request->lease_payment_per_interval == '1') {
                            if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                                //create new dates here from the posted dates
                                foreach ($request->altered_payment_due_date as $date) {
                                    if ($date) {
                                        LeaseAssetPaymenetDueDate::create([
                                            'asset_id' => $id,
                                            'payment_id' => $payment->id,
                                            'date' => Carbon::parse($date)->format('Y-m-d'),
                                            'total_payment_amount' => $request->total_amount_per_interval
                                        ]);
                                    }
                                }
                            }
                        } else {
                            if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                                //create new dates here from the posted dates
                                foreach ($request->altered_payment_due_date as $date) {
                                    if (isset($request->inconsistent_date_payment[Carbon::parse($date)->format('Y-m-d')])) {
                                        LeaseAssetPaymenetDueDate::create([
                                            'asset_id' => $id,
                                            'payment_id' => $payment->id,
                                            'date' => Carbon::parse($date)->format('Y-m-d'),
                                            'total_payment_amount' => $request->inconsistent_date_payment[Carbon::parse($date)->format('Y-m-d')]
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    // complete Step
                    $lease_id = $asset->lease->id;
                    confirmSteps($lease_id, $this->current_step);

                    return redirect(route('addlease.payments.index', ['id' => $asset->lease->id]))->with('status', 'Lease Asset Payments has been added successfully.');
                } else {
                    return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput($request->except('_token'));
                }
            }

            $lease_payments_types = LeasePaymentComponents::query()->get();
            $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $payments_frequencies = LeasePaymentsFrequency::query()->get();
            $payments_payout_times = LeasePaymentsInterval::query()->get();
            $lease_span_time_in_days = Carbon::parse($asset->lease_end_date)->diffInMonths(Carbon::parse($asset->accural_period));

            $payout_due_dates = [];
            $current_step = $this->current_step;
            $variable_basis = LeasePaymentsBasis::query()->whereIn('business_account_id', getDependentUserIds())->get();
            return view('lease.payments.createpayment', compact(
                'asset',
                'lease',
                'payment',
                'lease_payments_nature',
                'lease_payments_types',
                'payments_frequencies',
                'payments_payout_times',
                'payout_due_dates',
                'breadcrumbs',
                'lease_span_time_in_days',
                'subsequent_modify_required',
                'current_step',
                'variable_basis',
                'is_subsequent'
            ));

        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * update a particular already created asset payment
     * @param $id
     * @param $payment_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateAssetPayments($id, $payment_id, Request $request)
    {
        try {
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $asset->lease;
            //check if the Subsequent Valuation is applied for the lease modification
            $subsequent_modify_required = $lease->isSubsequentModification();
            $is_subsequent = $subsequent_modify_required;
            $payment = LeaseAssetPayments::query()->where('asset_id', '=', $id)->where('id', '=', $payment_id)->first();
            if ($payment) {
                if ($request->isMethod('post')) {

                    Validator::extend('required_when_variable_determinable', function($attribute, $value, $parameters, $validator) use ($request){
                        $nature = array_get($validator->getData(), $parameters[0], null);
                        $amount_determinable = array_get($validator->getData(), $parameters[1], null);
                        $required = true;
                        if($nature == "2" && $amount_determinable == "yes" || $nature == "1"){
                            //this means that the field is required...
                            $required = true;
                        } elseif($nature == "2" && $amount_determinable == "no") {
                            //the field is not required
                            $required = false;
                        }
                        if($required){
                            if($attribute == "due_dates_confirmed"){
                                return trim($value!="") && $value == '1';
                            } else {
                                return trim($value!="");
                            }
                        } else {
                            return true;
                        }
                    });

                    $rules = [
                        'name' => 'required',
                        'type' => 'required',
                        'nature' => 'required',
                        'variable_basis' => 'required_if:nature,2',
                        'variable_amount_determinable' => 'required_if:nature,2',
                        'payment_interval' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                        'payout_time' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                        'first_payment_start_date' => 'required_when_variable_determinable:nature,variable_amount_determinable|date|nullable',
                        'last_payment_end_date' => 'required_when_variable_determinable:nature,variable_amount_determinable|date|nullable',
                        'payment_currency' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                        'similar_chateristics_assets' => 'required_when_variable_determinable:nature,variable_amount_determinable|numeric',
                        'payment_per_interval_per_unit' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
                        'total_amount_per_interval' => 'numeric|required_if:lease_payment_per_interval,1|nullable',
                        'attachment' => config('settings.file_size_limits.file_rule'),
                        'due_dates_confirmed' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                        'lease_payment_per_interval' => 'required_when_variable_determinable:nature,variable_amount_determinable',
                        'altered_payment_due_date.*' => 'required_when_variable_determinable:nature,variable_amount_determinable|date|nullable',
                        'inconsistent_date_payment.*' => 'required_if:lease_payment_per_interval,2|numeric|nullable'
                    ];

                    $validator = Validator::make($request->except('_token'), $rules, [
                        'required_when_variable_determinable' => 'The :attribute field is required.',
                        'altered_payment_due_date.*.required' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                        'due_dates_confirmed.required_when_variable_determinable' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                    'file.max' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.',
                    'file.uploaded' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.'
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                    }

                    $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit', 'altered_payment_due_date', 'due_dates_confirmed', 'inconsistent_date_payment');
                    $create_payment_dates = true;
                    if($data['nature'] == '2' && $data['variable_amount_determinable'] == "no"){
                        $data['payment_interval'] = null;
                        $data['payout_time'] = null;
                        $data['first_payment_start_date'] = null;
                        $data['last_payment_end_date'] = null;
                        $data['payment_currency'] = null;
                        $data['lease_payment_per_interval'] = null;
                        $data['payment_per_interval_per_unit'] = null;
                        $data['total_amount_per_interval'] = null;
                        $create_payment_dates = false;
                    } else {
                        $data['first_payment_start_date'] = Carbon::parse($request->first_payment_start_date)->format('Y-m-d');
                        $data['last_payment_end_date'] = Carbon::parse($request->last_payment_end_date)->format('Y-m-d');
                    }

                    $data['attachment'] = "";
                    $data['asset_id'] = $asset->id;
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    if($subsequent_modify_required){
                        //update the subsequent_status field to 1 as well..
                        $data['subsequent_status'] = '1';
                    }

                    $payment->setRawAttributes($data);
                    if ($payment->save()) {
                        //code to create the due dates for the payment
                        LeaseAssetPaymenetDueDate::query()->where('asset_id', '=', $id)->where('payment_id', '=', $payment_id)->delete();
                        if($create_payment_dates){
                            if($request->lease_payment_per_interval == '1') {
                                if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                                    //create new dates here from the posted dates
                                    foreach ($request->altered_payment_due_date as $date) {
                                        if ($date) {
                                            LeaseAssetPaymenetDueDate::create([
                                                'asset_id' => $id,
                                                'payment_id' => $payment_id,
                                                'date' => Carbon::parse($date)->format('Y-m-d'),
                                                'total_payment_amount' => $request->total_amount_per_interval
                                            ]);
                                        }
                                    }
                                }
                            } else {
                                if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                                    //create new dates here from the posted dates
                                    foreach ($request->altered_payment_due_date as $date) {
                                        if (isset($request->inconsistent_date_payment[Carbon::parse($date)->format('Y-m-d')])) {
                                            LeaseAssetPaymenetDueDate::create([
                                                'asset_id' => $id,
                                                'payment_id' => $payment_id,
                                                'date' => Carbon::parse($date)->format('Y-m-d'),
                                                'total_payment_amount' => $request->inconsistent_date_payment[Carbon::parse($date)->format('Y-m-d')]
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                        // complete Step
                        $lease_id = $asset->lease->id;
                        confirmSteps($lease_id, $this->current_step);

                        return redirect(route('addlease.payments.index', ['id' => $asset->lease->id]))->with('status', 'Lease Asset Payments has been updated successfully.');
                    } else {
                        return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput($request->except('_token'));
                    }
                }

                $lease_payments_types = LeasePaymentComponents::query()->get();
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                $payments_frequencies = LeasePaymentsFrequency::query()->get();
                $payments_payout_times = LeasePaymentsInterval::query()->get();
                $payout_due_dates = $payment->paymentDueDates->pluck('date')->toArray();

                $lease_span_time_in_days = Carbon::parse($asset->lease_end_date)->diffInMonths(Carbon::parse($asset->accural_period));

                $current_step = $this->current_step;

                $variable_basis = LeasePaymentsBasis::query()->whereIn('business_account_id', getDependentUserIds())->get();

                return view('lease.payments.updatepayment', compact(
                    'asset',
                    'lease',
                    'payment',
                    'lease_payments_nature',
                    'lease_payments_types',
                    'payments_frequencies',
                    'payments_payout_times',
                    'payout_due_dates',
                    'lease_span_time_in_days',
                    'subsequent_modify_required',
                    'current_step',
                    'variable_basis',
                    'is_subsequent'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Save the Total Payments number to the lease asset
     * @param $id Asset ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTotalPayments($id, Request $request)
    {
        try {
            $asset = LeaseAssets::query()->findOrFail($id);
            $asset->total_payments = $request->has('no_of_lease_payments') ? $request->no_of_lease_payments : 0;
            $asset->save();
            return redirect()->back();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * fetch the lease asset payments for a single asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchAssetPayments($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = LeaseAssetPayments::query()->where('asset_id', '=', $id)->with('category');
                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && trim($request->search["value"]) != "") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Calculates all the payment due dates provided the first payment start date and the last payment end date
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function dueDatesAnnexure(Request $request)
    {
        try {
            if ($request->ajax()) {


                $errors = [];

                $validator = Validator::make($request->all(), [
                    'lease_id' => 'required|exists:lease,id',
                    'asset_id' => 'required|exists:lease_assets,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                    'payment_interval' => 'required|exists:lease_payments_frequency,id',
                    'payment_payout' => 'required|exists:lease_payment_interval,id'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                }

                $lease = Lease::query()->findOrFail($request->lease_id);

                $asset = LeaseAssets::query()->findOrFail($request->asset_id);

                $final_payout_dates = [];
                $years = [];

                $start_year = Carbon::parse($asset->accural_period)->format('Y');
//                $end_year = Carbon::parse($asset->lease_end_date)->format('Y');

                $end_year = Carbon::parse($request->end_date)->format('Y');

                if ($start_year == $end_year) {
                    $years[] = $end_year;
                } else if ($end_year > $start_year) {
                    $years = range($start_year, $end_year);
                }

                for ($m = 1; $m <= 12; ++$m) {
                    $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
                }

                //calculate all the due dates here from the start date till the end date...
                if ($request->payment_interval == 1) {
                    //check if the payments are going to be One-Time
                    if ($request->payment_payout == 1) {
                        //means that the payment is going to be made at the start of the interval
                        $month = Carbon::parse($request->start_date)->format('F');
                        $current_year = Carbon::parse($request->start_date)->format('Y');
                        $final_payout_dates[$current_year][$month][$request->start_date] = $request->start_date;
                    } else if ($request->payment_payout == 2) {
                        //means that the payment is going to be made at the end of the interval
                        $month = Carbon::parse($request->end_date)->format('F');
                        $current_year = Carbon::parse($request->end_date)->format('Y');
                        $final_payout_dates[$current_year][$month][$request->end_date] = $request->end_date;
                    }
                }

                switch ($request->payment_interval) {
                    case 2:
                        $final_payout_dates = calculatePaymentDueDates($request->start_date, $request->end_date, $request->payment_payout, 1);
                        break;
                    case 3:
                        $final_payout_dates = calculatePaymentDueDates($request->start_date, $request->end_date, $request->payment_payout, 3);
                        break;
                    case 4:
                        $final_payout_dates = calculatePaymentDueDates($request->start_date, $request->end_date, $request->payment_payout, 6);
                        break;
                    case 5:
                        $final_payout_dates = calculatePaymentDueDates($request->start_date, $request->end_date, $request->payment_payout, 12);
                        break;
                }

                return response()->json([
                    'status' => true,
                    'html' => view('lease.payments._due_dates_annexure', compact('errors', 'months', 'years', 'final_payout_dates'))->render(),
                    'final_payout_dates' => $final_payout_dates
                ], 200);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Renders the current payment due dates on the modal pop up...
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function viewExistingDates($id)
    {
        if ($id) {
            $payment = LeaseAssetPayments::query()->where('id', '=', $id)->first();
            if ($payment) {
                return response()->json([
                    'status' => true,
                    'html' => view('lease.payments._current_due_dates_annexure', compact(
                        'payment'
                    ))->render()
                ]);
            } else {
                return response()->json(['status' => false], 200);
            }
        }
    }

    /**
     * load the inconsistent interval annexure.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inconsistentIntervalAnnexure(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'lease_id' => 'required|exists:lease,id',
                'asset_id' => 'required|exists:lease_assets,id',
                'paymentDates.*' => 'required'
            ],[
                'paymentDates.*.required' => 'Please confirm the payment dates annexure first.'
            ]);

            $errors = $months = $years = $dates = [];

            if ($validator->fails()) {
                $errors = $validator->errors();
            } else {

                for ($m = 1; $m <= 12; ++$m) {
                    $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
                }

                $paymentDates = Arr::sort($request->paymentDates);

                //populate the years array here
                foreach ($paymentDates as $paymentDate) {
                    $years[Carbon::parse($paymentDate)->format('Y')] = Carbon::parse($paymentDate)->format('Y');
                    $dates[Carbon::parse($paymentDate)->format('Y')][Carbon::parse($paymentDate)->format('m')][] = [
                        'date' => $paymentDate,
                        'amount' => 0
                    ];
                }
            }


            $data = $request->all();

            return view('lease.payments._inconsistent_annexure', compact(
                'data',
                'errors',
                'years',
                'months',
                'dates'
            ));

        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * ajax request to load the inconsistent payment annexure for the dates..
     * @param $payment_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadInconsistentAnnexure($payment_id){
        try{
            $paymentDates = LeaseAssetPaymenetDueDate::query()->where('payment_id', $payment_id)->orderBy('date', 'asc')->get();

            $errors = $months = $years = $dates = [];
            for ($m = 1; $m <= 12; ++$m) {
                $months[$m] = date('F', mktime(0, 0, 0, $m, 1));
            }


            //populate the years array here
            foreach ($paymentDates as $paymentDate) {
                $years[Carbon::parse($paymentDate->date)->format('Y')] = Carbon::parse($paymentDate->date)->format('Y');
                $dates[Carbon::parse($paymentDate->date)->format('Y')][Carbon::parse($paymentDate->date)->format('m')][] = [
                    'date' => $paymentDate->date,
                    'amount' => $paymentDate->total_payment_amount
                ];
            }

            return view('lease.payments._inconsistent_annexure', compact(
                'data',
                'errors',
                'years',
                'months',
                'dates'
            ));

        }catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * delete a particular lease asset payment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $request->lease_id)->first();
            if($lease) {
                $payment = LeaseAssetPayments::query()->findOrFail($request->payment_id);
                $payment->delete();
            }
            return response()->json([
                'status' => true
            ], 200);
        } catch (\Exception $e){
            return response()->json([
                'status' => false
            ], 200);
        }
    }
}
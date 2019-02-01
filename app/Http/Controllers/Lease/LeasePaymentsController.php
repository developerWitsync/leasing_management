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
use App\LeasePaymentsFrequency;
use App\LeasePaymentsInterval;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class LeasePaymentsController extends Controller
{

    /**
     * validation rules for the create and update payments
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
            'first_payment_start_date' => 'required|date',
            'last_payment_end_date' => 'required|date',
            'payment_currency' => 'required',
            'similar_chateristics_assets' => 'required|numeric',
            'payment_per_interval_per_unit' => 'required|numeric',
            'total_amount_per_interval' => 'required|numeric',
            'attachment' => 'file|mimes:jpeg,pdf,doc',
            'due_dates_confirmed' => 'in:1',
            'altered_payment_due_date.*' => 'required|date'
        ];

        return $rules;
    }

    /**
     * Render the table for all the lease assets so that the user can complete steps for the payments
     * @param $id
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
                'link' => route('addlease.payments.index', ['id' => $id]),
                'title' => 'Lease Payments'
            ],
        ];

        try {
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                ->with('assets.category')
                ->with('assets.subcategory')
                ->where('id', '=', $id)
                ->first();


            if ($lease) {
                $show_next = false;
                $completed_payments = 0;
                $required_payments = 0;
                foreach ($lease->assets as $asset) {
                    $required_payments += $asset->total_payments;
                    if ($asset->total_payments > 0 && count($asset->payments) > 0) {
                        $completed_payments += count($asset->payments);
                    } else {
                        $required_payments += 1; //incrementing by one to not show the next button
                        break;
                    }
                }

                $show_next = $required_payments == $completed_payments;

                return view('lease.payments.index', compact('breadcrumbs',
                    'lease',
                    'show_next'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Renders the form to select the total number of the payments for the asset and renders the table to list all the asset payments as well.
     * @param $lease_id
     * @param $asset_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($lease_id, $asset_id, Request $request)
    {
        try {

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.payments.index', ['id' => $lease_id]),
                    'title' => 'Lease Payments'
                ],
                [
                    'link' => route('lease.payments.add', ['lease_id' => $lease_id, 'asset_id' => $asset_id, 'payment_id' => $request->payment_id]),
                    'title' => 'Add Lease Payments'
                ]
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();

            if ($lease) {
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();
                if ($asset) {
                    $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id', 'number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    return view('lease.payments.create', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments',
                        'breadcrumbs',
                        'subsequent_modify_required'
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
                    'link' => route('lease.payments.add', ['lease_id' => $lease->id, 'asset_id' => $id]),
                    'title' => 'Add Lease Payments'
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
                $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                    'altered_payment_due_date.*.required' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                    'due_dates_confirmed.in' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                }
                $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit', 'altered_payment_due_date', 'due_dates_confirmed');
                $data['first_payment_start_date'] = Carbon::parse($request->first_payment_start_date)->format('Y-m-d');
                $data['last_payment_end_date'] = Carbon::parse($request->last_payment_end_date)->format('Y-m-d');
                $data['attachment'] = "";
                $data['asset_id'] = $asset->id;
                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $uniqueFileName = uniqid() . $file->getClientOriginalName();
                    $request->file('attachment')->move('uploads', $uniqueFileName);
                    $data['attachment'] = $uniqueFileName;
                }
                $payment->setRawAttributes($data);
                if ($payment->save()) {
                    //code to create the due dates for the payment
                    LeaseAssetPaymenetDueDate::query()->where('asset_id', '=', $id)->where('payment_id', '=', $payment->id)->delete();
                    if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                        //create new dates here from the posted dates
                        foreach ($request->altered_payment_due_date as $date) {
                            if ($date) {
                                LeaseAssetPaymenetDueDate::create([
                                    'asset_id' => $id,
                                    'payment_id' => $payment->id,
                                    'date' => Carbon::parse($date)->format('Y-m-d')
                                ]);
                            }
                        }
                    }

                    // complete Step
                    $lease_id = $asset->lease->id;
                    $step = 'step3';
                    $complete_step3 = confirmSteps($lease_id, $step);

                    return redirect(route('lease.payments.add', ['lease_id' => $asset->lease->id, 'asset_id' => $asset->id]))->with('status', 'Lease Asset Payments has been added successfully.');
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
                'lease_span_time_in_days'
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
            $payment = LeaseAssetPayments::query()->where('asset_id', '=', $id)->where('id', '=', $payment_id)->first();
            if ($payment) {
                if ($request->isMethod('post')) {

                    $validator = Validator::make($request->except('_token'), $this->validationRules(false), [
                        'altered_payment_due_date.*.required' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.',
                        'due_dates_confirmed.in' => 'Please confirm the payment due dates by clicking on the Confirm Payment Due Dates.'
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                    }

                    $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit', 'altered_payment_due_date', 'due_dates_confirmed');
                    $data['first_payment_start_date'] = Carbon::parse($request->first_payment_start_date)->format('Y-m-d');
                    $data['last_payment_end_date'] = Carbon::parse($request->last_payment_end_date)->format('Y-m-d');
                    $data['attachment'] = "";
                    $data['asset_id'] = $asset->id;
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }
                    $payment->setRawAttributes($data);
                    if ($payment->save()) {
                        //code to create the due dates for the payment
                        LeaseAssetPaymenetDueDate::query()->where('asset_id', '=', $id)->where('payment_id', '=', $payment_id)->delete();
                        if ($request->has('altered_payment_due_date') && !empty($request->altered_payment_due_date)) {
                            //create new dates here from the posted dates
                            foreach ($request->altered_payment_due_date as $date) {
                                if ($date) {
                                    LeaseAssetPaymenetDueDate::create([
                                        'asset_id' => $id,
                                        'payment_id' => $payment_id,
                                        'date' => Carbon::parse($date)->format('Y-m-d')
                                    ]);
                                }
                            }

                        }

                        return redirect(route('lease.payments.add', ['lease_id' => $asset->lease->id, 'asset_id' => $asset->id]))->with('status', 'Lease Asset Payments has been updated successfully.');
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

                return view('lease.payments.updatepayment', compact(
                    'asset',
                    'lease',
                    'payment',
                    'lease_payments_nature',
                    'lease_payments_types',
                    'payments_frequencies',
                    'payments_payout_times',
                    'payout_due_dates',
                    'lease_span_time_in_days'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
                $end_year = Carbon::parse($asset->lease_end_date)->format('Y');

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
}
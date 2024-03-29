<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 04/01/19
 * Time: 9:43 AM
 */

namespace App\Http\Controllers\Lease;

use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseIncentives;
use App\ForeignCurrencyTransactionSettings;
use App\ReportingCurrencySettings;
use App\Currencies;
use App\CustomerDetails;
use App\LeaseAssets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class LeaseIncentivesController extends Controller
{
    private $current_step = 15;

    protected function validationRules()
    {
        return [
            'is_any_lease_incentives_receivable' => 'required',
            'currency' => 'required_if:is_any_lease_incentives_receivable,yes',
            'total_lease_incentives' => 'required_if:is_any_lease_incentives_receivable,yes',
            'customer_name.*' => 'required_if:is_any_lease_incentives_receivable,yes|nullable',
            'description.*' => 'required_if:is_any_lease_incentives_receivable,yes|nullable',
            'incentive_date.*' => 'required_if:is_any_lease_incentives_receivable,yes|date|nullable|date_format:d-M-Y',
            'currency_id.*' => 'required_if:is_any_lease_incentives_receivable,yes|nullable',
            'amount.*' => 'required_if:is_any_lease_incentives_receivable,yes|numeric|nullable',
            'exchange_rate.*' => 'required_if:is_any_lease_incentives_receivable,yes|numeric|nullable'
        ];
    }

    /**
     * updated new function that will be now used for the lease incentives as we will now have only one lease asset..
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index_V2($id, Request $request)
    {
        try {
            $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
            if($settings->date_of_initial_application == 2){
                $base_date = Carbon::parse(getParentDetails()->baseDate->final_base_date)->subYear(1)->format('Y-m-d');
            } else {
                $base_date = getParentDetails()->baseDate->final_base_date;
            }
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.leaseincentives.index', ['id' => $id]),
                    'title' => 'Lease Incentives'
                ],
            ];
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if ($lease) {

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('accural_period', '>=', $base_date)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })
                    ->first();

                if ($asset) {

                    $currencies = fetchCurrenciesFromSettings();

                    if ($asset->leaseIncentives) {
                        $model = $asset->leaseIncentives;
                        $lease_incentive_id = $model->id;
                    } else {
                        $model = new LeaseIncentives();
                        $lease_incentive_id = null;
                    }

                    if ($request->isMethod('post')) {

                        if ($request->has('is_any_lease_incentives_receivable') && $request->is_any_lease_incentives_receivable == "yes") {
                            $total = 0;
                            foreach ($request->customer_name as $key => $customer) {
                                $total += ($request->amount[$key] * $request->exchange_rate[$key]);
                            }
                            $request->request->add(['total_lease_incentives' => $total]);
                        }

                        $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                            'customer_name.*.required_if' => 'Customer name is required',
                            'description.*.required_if' => 'Description is required',
                            'incentive_date.*.required_if' => 'Incentive Date is required',
                            'currency_id.*.required_if' => 'Currency is required',
                            'amount.*.required_if' => 'Amount is required',
                            'exchange_rate.*.required_if' => 'Rate is required',
                            'incentive_date.*.date_format' => 'Required date format is d-M-Y',
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token', 'submit', 'customer_name', 'description', 'incentive_date', 'currency_id', 'amount', 'exchange_rate', 'uuid', 'asset_name', 'asset_category', 'action');
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;
                        $model->setRawAttributes($data);
                        if ($model->save()) {
                            //Delete all the customer and create them again..
                            CustomerDetails::query()->where('lease_incentive_id', '=', ($lease_incentive_id) ? $lease_incentive_id : $model->id)->delete();
                            if ($request->is_any_lease_incentives_receivable == "yes") {
                                foreach ($request->customer_name as $key => $value) {
                                    CustomerDetails::create([
                                        'lease_incentive_id' => ($lease_incentive_id) ? $lease_incentive_id : $model->id,
                                        'customer_name' => $value,
                                        'description' => $request->description[$key],
                                        'incentive_date' => date('Y-m-d', strtotime($request->incentive_date[$key])),
                                        'currency_id' => $request->currency_id[$key],
                                        'amount' => $request->amount[$key],
                                        'exchange_rate' => $request->exchange_rate[$key]
                                    ]);
                                }
                            }
                            // complete Step
                            confirmSteps($lease->id, 15);
                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('lease.dismantlingcosts.index', ['id' => $lease->id]))->with('status', 'Lease incentive cost has been added successfully.');
                            } else {

                                return redirect(route('addlease.leaseincentives.index', ['id' => $lease->id]))->with('status', 'Lease incentive cost has been added successfully.');

                            }

                        }
                    }

                    //to get current step for steps form
                    $current_step = $this->current_step;

                    return view('lease.lease-incentives.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'customer_model',
                        'customer_details',
                        'currencies',
                        'breadcrumbs',
                        'current_step',
                        'subsequent_modify_required'
                    ));

                } else {
                    //redirect user to the next step
                    return redirect(route('lease.dismantlingcosts.index', ['id' => $id]));
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
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
                'link' => route('addlease.leaseincentives.index', ['id' => $id]),
                'title' => 'Lease Incentives'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if ($lease) {
            //Load the assets only lease start on or after jan 01 2019
            $base_date = getParentDetails()->baseDate->final_base_date;
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '>=', $base_date)->get();
            return view('lease.lease-incentives.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add lease incentive cost details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request)
    {
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.leaseincentives.index', ['id' => $id]),
                'title' => 'Lease Incentives'
            ],
        ];
        try {

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            if ($lease) {

                $model = new LeaseIncentives();

                $customer_model = new CustomerDetails();

                if ($request->isMethod('post')) {

                    if ($request->has('is_any_lease_incentives_receivable') && $request->is_any_lease_incentives_receivable == "yes") {
                        $total = 0;
                        foreach ($request->customer_name as $key => $customer) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_lease_incentives' => $total]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'customer_name.*.required_if' => 'Customer name is required',
                        'description.*.required_if' => 'Description is required',
                        'incentive_date.*.required_if' => 'Incentive Date is required',
                        'currency_id.*.required_if' => 'Currency is required',
                        'amount.*.required_if' => 'Amount is required',
                        'exchange_rate.*.required_if' => 'Rate is required'
                    ]);

                    if ($validator->fails()) {
                        //dd($validator->errors());
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'customer_name', 'description', 'incentive_date', 'currency_id', 'amount', 'exchange_rate');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    $lease_incentive_cost = LeaseIncentives::create($data);

                    if ($lease_incentive_cost) {
                        if ($request->is_any_lease_incentives_receivable == "yes") {
                            foreach ($request->customer_name as $key => $value) {
                                CustomerDetails::create([
                                    'lease_incentive_id' => $lease_incentive_cost->id,
                                    'customer_name' => $value,
                                    'description' => $request->description[$key],
                                    'incentive_date' => date('Y-m-d', strtotime($request->incentive_date[$key])),
                                    'currency_id' => $request->currency_id[$key],
                                    'amount' => $request->amount[$key],
                                    'exchange_rate' => $request->exchange_rate[$key]
                                ]);
                            }
                        }
                        // complete Step
                        confirmSteps($lease->id, 15);
                        return redirect(route('addlease.leaseincentives.index', ['id' => $lease->id]))->with('status', 'Lease incentive cost has been added successfully.');
                    }
                }

                $customer_details = [];

                return view('lease.lease-incentives.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'customer_model',
                    'customer_details',
                    'currencies',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(404);
        }
    }

    /**
     * edit existing lease Incentive value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        try {
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currencies = Currencies::query()->where('status', '=', '1')->get();

            if ($lease) {

                $model = LeaseIncentives::query()->where('asset_id', '=', $id)->first();
                $lease_incentive_id = $model->id;

                if ($request->isMethod('post')) {

                    if ($request->has('is_any_lease_incentives_receivable') && $request->is_any_lease_incentives_receivable == "yes") {
                        $total = 0;
                        foreach ($request->customer_name as $key => $customer) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_lease_incentives' => $total]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'customer_name.*.required_if' => 'Customer name is required',
                        'description.*.required_if' => 'Description is required',
                        'incentive_date.*.required_if' => 'Incentive Date is required',
                        'currency_id.*.required_if' => 'Currency is required',
                        'amount.*.required_if' => 'Amount is required',
                        'exchange_rate.*.required_if' => 'Rate is required'
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'customer_name', 'description', 'incentive_date', 'currency_id', 'amount', 'exchange_rate');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;

                    if ($request->initial_direct_cost_involved == 'no') {
                        $data['total_lease_incentives'] = 0;
                    }
                    $model->setRawAttributes($data);

                    if ($model->save()) {
                        //Delete all the customer and create them again..
                        CustomerDetails::query()->where('lease_incentive_id', '=', $lease_incentive_id)->delete();
                        if ($request->is_any_lease_incentives_receivable == "yes") {
                            foreach ($request->customer_name as $key => $value) {
                                CustomerDetails::create([
                                    'lease_incentive_id' => $lease_incentive_id,
                                    'customer_name' => $value,
                                    'description' => $request->description[$key],
                                    'incentive_date' => date('Y-m-d', strtotime($request->incentive_date[$key])),
                                    'currency_id' => $request->currency_id[$key],
                                    'amount' => $request->amount[$key],
                                    'exchange_rate' => $request->exchange_rate[$key]
                                ]);
                            }
                        }
                        return redirect(route('addlease.leaseincentives.index', ['id' => $lease->id]))->with('status', 'Lease Incentives has been updated successfully.');
                    }
                }
                return view('lease.lease-incentives.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(404);
        }
    }


}
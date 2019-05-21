<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;


use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssets;
use App\InitialDirectCost;
use App\SupplierDetails;
use App\Currencies;
use App\CategoriesLeaseAssetExcluded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class InitialDirectCostController extends Controller
{
    private $current_step = 14;

    protected function validationRules()
    {
        return [
            'initial_direct_cost_involved' => 'required',
            'currency' => 'required_if:initial_direct_cost_involved,yes',
            'total_initial_direct_cost' => 'required_if:initial_direct_cost_involved,yes',
            'supplier_name.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'direct_cost_description.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'expense_date.*' => 'required_if:initial_direct_cost_involved,yes|date|nullable|date_format:d-M-Y',
            'supplier_currency.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'amount.*' => 'required_if:initial_direct_cost_involved,yes|numeric|nullable',
            'rate.*' => 'required_if:initial_direct_cost_involved,yes|numeric|nullable'
        ];

    }

    /**
     * Create or update the details for the Single Lease asset..
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index_V2($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.initialdirectcost.index', ['id' => $id]),
                    'title' => 'Initial Direct Cost'
                ],
            ];
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if ($lease) {
                $base_date = getParentDetails()->accountingStandard->base_date;
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $id)
                    ->where('lease_start_date', '>=', $base_date)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })
                    ->first(); //since there can be only one lease asset per lease

                if ($asset) {
                    $currencies = fetchCurrenciesFromSettings();
                    if ($asset->initialDirectCost) {
                        $model = $asset->initialDirectCost;
                    } else {
                        $model = new InitialDirectCost();
                    }

                    if ($request->isMethod('post')) {

                        if ($request->has('initial_direct_cost_involved') && $request->initial_direct_cost_involved == "yes") {
                            $total = 0;
                            foreach ($request->supplier_name as $key => $supplier) {
                                $total += ($request->amount[$key] * $request->rate[$key]);
                            }
                            $request->request->add(['total_initial_direct_cost' => $total]);
                        }

                        $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                            'supplier_name.*.required_if' => 'Supplier name is required',
                            'direct_cost_description.*.required_if' => 'Direct Cost Description is required',
                            'expense_date.*.required_if' => 'Expense Date is required',
                            'expense_date.*.date_format' => 'Required date format is d-M-Y',
                            'supplier_currency.*.required_if' => 'Currency is required',
                            'amount.*.required_if' => 'Amount is required',
                            'rate.*.required_if' => 'Rate is required'
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token', 'submit', 'supplier_name', 'direct_cost_description', 'expense_date', 'supplier_currency', 'amount', 'rate', 'id', 'uuid', 'asset_name', 'asset_category', 'action');
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;
                        $model->setRawAttributes($data);
                        $initial_direct_cost = $model->save();
                        if ($initial_direct_cost) {
                            //Delete all the suppliers and create them again..

                            SupplierDetails::query()
                                ->where('initial_direct_cost_id', '=', ($request->has('id') && $request->id) ? $request->id : $model->id)
                                ->where('type', '=', 'initial_direct_cost')
                                ->delete();

                            if ($request->initial_direct_cost_involved == "yes") {
                                foreach ($request->supplier_name as $key => $value) {
                                    SupplierDetails::create([
                                        'initial_direct_cost_id' => ($request->has('id') && $request->id) ? $request->id : $model->id,
                                        'supplier_name' => $value,
                                        'direct_cost_description' => $request->direct_cost_description[$key],
                                        'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                        'supplier_currency' => $request->supplier_currency[$key],
                                        'amount' => $request->amount[$key],
                                        'rate' => $request->rate[$key]
                                    ]);
                                }
                            }

                            // complete Step
                            confirmSteps($lease->id, 14);
                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('addlease.leaseincentives.index', ['id' => $lease->id]))->with('status', 'Initial Direct Cost has been added successfully.');
                            } else {
                                return redirect(route('addlease.initialdirectcost.index', ['id' => $lease->id]))->with('status', 'Initial Direct Cost has been added successfully.');
                            }
                        }
                    }

                    $asset_on_balence = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '<', $base_date)->count();
                    if ($asset_on_balence > 0) {
                        $back_url = route('addlease.balanceasondec.index', ['id' => $id]);
                    } else {
                        $category_excluded = CategoriesLeaseAssetExcluded::query()
                            ->whereIn('business_account_id', getDependentUserIds())
                            ->where('status', '=', '0')
                            ->get();
                        $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                        $asset_on_discount = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                            ->where('specific_use', 1)
                            ->whereNotIn('category_id', $category_excluded_id)
                            ->whereHas('leaseSelectLowValue', function ($query) {
                                $query->where('is_classify_under_low_value', '=', 'no');
                            })
                            ->whereHas('leaseDurationClassified', function ($query) {
                                $query->where('lease_contract_duration_id', '=', '3');
                            })->count();

                        if (is_null($asset_on_discount)) {
                            $asset_on_discount = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                                ->where('specific_use', 2)
                                ->whereNotIn('category_id', $category_excluded_id)
                                ->whereHas('leaseSelectLowValue', function ($query) {
                                    $query->where('is_classify_under_low_value', '=', 'no');
                                })
                                ->whereHas('leaseDurationClassified', function ($query) {

                                    $query->where('lease_contract_duration_id', '=', '3');
                                })->count();
                        }
                        if ($asset_on_discount > 0) {
                            $back_url = route('addlease.discountrate.index', ['id' => $id]);
                        } else {
                            //$back_url = route('addlease.lowvalue.index', ['id' => $id]);
                            $category_excluded = CategoriesLeaseAssetExcluded::query()
                                ->whereIn('business_account_id', getDependentUserIds())
                                ->where('status', '=', '0')
                                ->get();

                            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                            $asset_on_low = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereNotIn('specific_use', [2])
                                ->whereHas('leaseDurationClassified', function ($query) {
                                    $query->whereNotIn('lease_contract_duration_id', [1, 2]);
                                })->whereNotIn('category_id', $category_excluded_id)->count();

                            if ($asset_on_low > 0) {
                                $back_url = route('addlease.lowvalue.index', ['id' => $id]);
                            } else {
                                $back_url = route('lease.escalation.index', ['id' => $id]);
                            }
                        }
                    }

                    //to get current step for steps form
                    $current_step = $this->current_step;


                    return view('lease.initial-direct-cost.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'supplier_model',
                        'currencies',
                        'breadcrumbs',
                        'back_url',
                        'current_step',
                        'subsequent_modify_required'
                    ));

                } else {
                    //redirect the user to the next step..
                    return redirect(route('addlease.leaseincentives.index', ['id' => $id]));
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

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
                'link' => route('addlease.initialdirectcost.index', ['id' => $id]),
                'title' => 'Initial Direct Cost'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if ($lease) {
            $base_date = getParentDetails()->accountingStandard->base_date;
            //Load the assets only lease start on or after jan 01 2019
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '>=', $base_date)->get();

            return view('lease.initial-direct-cost.index', compact(
                'assets',
                'breadcrumbs',
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add initails direct cost details for an asset
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
                'link' => route('addlease.initialdirectcost.index', ['id' => $id]),
                'title' => 'Initial Direct Cost'
            ],
        ];
        try {
            $base_date = getParentDetails()->accountingStandard->base_date;
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            if ($lease) {

                $model = new InitialDirectCost();

                $supplier_model = new SupplierDetails();

                if ($request->isMethod('post')) {

                    if ($request->has('initial_direct_cost_involved') && $request->initial_direct_cost_involved == "yes") {
                        $total = 0;
                        foreach ($request->supplier_name as $key => $supplier) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_initial_direct_cost' => $total]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'supplier_name.*.required_if' => 'Supplier name is required',
                        'direct_cost_description.*.required_if' => 'Direct Cost Description is required',
                        'expense_date.*.required_if' => 'Expense Date is required',
                        'supplier_currency.*.required_if' => 'Currency is required',
                        'amount.*.required_if' => 'Amount is required',
                        'rate.*.required_if' => 'Rate is required'
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'supplier_name', 'direct_cost_description', 'expense_date', 'supplier_currency', 'amount', 'rate');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    $initial_direct_cost = InitialDirectCost::create($data);

                    if ($initial_direct_cost) {
                        if ($request->initial_direct_cost_involved == "yes") {
                            foreach ($request->supplier_name as $key => $value) {
                                SupplierDetails::create([
                                    'initial_direct_cost_id' => $initial_direct_cost->id,
                                    'supplier_name' => $value,
                                    'direct_cost_description' => $request->direct_cost_description[$key],
                                    'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                    'supplier_currency' => $request->supplier_currency[$key],
                                    'amount' => $request->amount[$key],
                                    'rate' => $request->rate[$key]
                                ]);
                            }
                        }
                        // complete Step
                        confirmSteps($lease->id, 14);
                        return redirect(route('addlease.initialdirectcost.index', ['id' => $lease->id]))->with('status', 'Initial Direct Cost has been added successfully.');
                    }
                }

                $supplier_details = [];
                $asset_on_balence = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '<', $base_date)->first();
                if ($asset_on_balence > 0) {
                    $back_url = route('addlease.balanceasondec.index', ['id' => $id]);
                } else {
                    $back_url = route('addlease.discountrate.index', ['id' => $id]);
                }

                return view('lease.initial-direct-cost.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'supplier_model',
                    'supplier_details',
                    'currencies',
                    'breadcrumbs',
                    'back_url'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * edit existing InitiaLdirectCost Controller details for an asset
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

                $model = InitialDirectCost::query()->where('asset_id', '=', $id)->first();
                $initial_direct_cost_id = $model->id;

                if ($request->isMethod('post')) {

                    if ($request->has('initial_direct_cost_involved') && $request->initial_direct_cost_involved == "yes") {
                        $total = 0;
                        foreach ($request->supplier_name as $key => $supplier) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_initial_direct_cost' => $total]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'supplier_name.*.required_if' => 'Supplier name is required',
                        'direct_cost_description.*.required_if' => 'Direct Cost Description is required',
                        'expense_date.*.required_if' => 'Expense Date is required',
                        'supplier_currency.*.required_if' => 'Currency is required',
                        'amount.*.required_if' => 'Amount is required',
                        'rate.*.required_if' => 'Rate is required'
                    ]);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'supplier_name', 'direct_cost_description', 'expense_date', 'supplier_currency', 'amount', 'rate');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;

                    if ($request->initial_direct_cost_involved == 'no') {
                        $data['total_initial_direct_cost'] = 0;
                    }

                    $model->setRawAttributes($data);

                    if ($model->save()) {
                        //Delete all the suppliers and create them again..
                        SupplierDetails::query()
                            ->where('initial_direct_cost_id', '=', $initial_direct_cost_id)
                            ->where('type', '=', 'initial_direct_cost')
                            ->delete();
                        if ($request->initial_direct_cost_involved == "yes") {
                            foreach ($request->supplier_name as $key => $value) {
                                SupplierDetails::create([
                                    'initial_direct_cost_id' => $initial_direct_cost_id,
                                    'supplier_name' => $value,
                                    'direct_cost_description' => $request->direct_cost_description[$key],
                                    'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                    'supplier_currency' => $request->supplier_currency[$key],
                                    'amount' => $request->amount[$key],
                                    'rate' => $request->rate[$key]
                                ]);
                            }
                        }
                        return redirect(route('addlease.initialdirectcost.index', ['id' => $lease->id]))->with('status', 'Initial Direct Cost has been updated successfully.');
                    }
                }
                return view('lease.initial-direct-cost.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

}


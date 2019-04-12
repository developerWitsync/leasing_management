<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 19/3/19
 * Time: 6:06 PM
 */

namespace App\Http\Controllers\Lease;

use App\SupplierDetails;
use Validator;
use App\Currencies;
use App\DismantlingCosts;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssets;
use Illuminate\Http\Request;

class DismantlingCostsController extends Controller
{
    private $current_step = 16;

    protected function validationRules()
    {
        return [
            'cost_of_dismantling_incurred' => 'required',
            'obligation_cost_of_dismantling_incurred' => 'required_if:cost_of_dismantling_incurred,yes|nullable',
            'currency' => 'required_if:obligation_cost_of_dismantling_incurred,yes',
            'total_estimated_cost' => 'required_if:obligation_cost_of_dismantling_incurred,yes',
            'supplier_name.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|nullable',
            'direct_cost_description.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|nullable',
            'expense_date.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|date|nullable|date_format:d-M-Y',
            'supplier_currency.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|nullable',
            'amount.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|numeric|nullable',
            'rate.*' => 'required_if:obligation_cost_of_dismantling_incurred,yes|numeric|nullable'
        ];

    }

    /**
     * save and update the dismantling costs for the current lease asset...
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index_V2($id, Request $request)
    {
        try {

            $base_date = getParentDetails()->accountingStandard->base_date;
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('lease.dismantlingcosts.index', ['id' => $id]),
                    'title' => 'Dismantling Costs'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                ->where('id', '=', $id)
                ->where('status', '=', '0')
                ->first();

            if ($lease) {

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('lease_start_date', '>=', $base_date)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })
                    ->first();

                if ($asset) {
                    $currencies = Currencies::query()->where('status', '=', '1')->get();

                    if ($asset->dismantlingCost) {
                        $model = $asset->dismantlingCost;
                    } else {
                        $model = new DismantlingCosts();
                    }

                    if ($request->isMethod('post')) {
                        if ($request->has('cost_of_dismantling_incurred') && $request->cost_of_dismantling_incurred == "yes" && $request->has('obligation_cost_of_dismantling_incurred') && $request->obligation_cost_of_dismantling_incurred == "yes") {
                            $total = 0;
                            foreach ($request->supplier_name as $key => $supplier) {
                                $total += ($request->amount[$key] * $request->rate[$key]);
                            }
                            $request->request->add(['total_estimated_cost' => $total]);
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

                        if ($data['cost_of_dismantling_incurred'] == "no") {
                            $data['obligation_cost_of_dismantling_incurred'] = null;
                            $data['total_estimated_cost'] = null;
                        }

                        $model->setRawAttributes($data);
                        $dismantling_cost = $model->save();
                        if ($dismantling_cost) {
                            //Delete all the suppliers and create them again..
                            SupplierDetails::query()
                                ->where('initial_direct_cost_id', '=', ($request->has('id') && $request->id) ? $request->id : $model->id)
                                ->where('type', '=', 'dismantling_cost')
                                ->delete();

                            if ($request->cost_of_dismantling_incurred == "yes" && $request->obligation_cost_of_dismantling_incurred == "yes") {
                                foreach ($request->supplier_name as $key => $value) {
                                    SupplierDetails::create([
                                        'initial_direct_cost_id' => ($request->has('id') && $request->id) ? $request->id : $model->id,
                                        'supplier_name' => $value,
                                        'direct_cost_description' => $request->direct_cost_description[$key],
                                        'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                        'supplier_currency' => $request->supplier_currency[$key],
                                        'amount' => $request->amount[$key],
                                        'rate' => $request->rate[$key],
                                        'type' => 'dismantling_cost'
                                    ]);
                                }
                            }
                            // complete Step
                            confirmSteps($lease->id, $this->current_step);
                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('addlease.leasevaluation.index', ['id' => $lease->id]))->with('status', 'Dismantling Costs has been added successfully.');
                            } else {
                                return redirect(route('lease.dismantlingcosts.index', ['id' => $lease->id]))->with('status', 'Dismantling Costs has been added successfully.');
                            }
                        }
                    }

                    $back_url = route('addlease.leaseincentives.index', ['id' => $id]); //if dismantling is applicable then in that case lease incentives will also be applicable..
                    //to get current step for steps form
                    $current_step = $this->current_step;
                    return view('lease.dismantling-costs.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'customer_model',
                        'customer_details',
                        'currencies',
                        'breadcrumbs',
                        'current_step',
                        'subsequent_modify_required',
                        'back_url'
                    ));

                } else {
                    //redirect user to the next step
                    return redirect(route('addlease.leasevaluation.index', ['id' => $id]));
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
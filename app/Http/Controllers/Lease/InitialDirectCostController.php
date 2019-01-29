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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class InitialDirectCostController extends Controller
{
    protected function validationRules()
    {
        return [
            'initial_direct_cost_involved' => 'required',
            'currency' => 'required_if:initial_direct_cost_involved,yes',
            'total_initial_direct_cost' => 'required_if:initial_direct_cost_involved,yes',
            'supplier_name.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'direct_cost_description.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'expense_date.*' => 'required_if:initial_direct_cost_involved,yes|date|nullable',
            'supplier_currency.*' => 'required_if:initial_direct_cost_involved,yes|nullable',
            'amount.*' => 'required_if:initial_direct_cost_involved,yes|numeric|nullable',
            'rate.*' => 'required_if:initial_direct_cost_involved,yes|numeric|nullable'
        ];
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
            //Load the assets only lease start on or after jan 01 2019
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '>=', '2019-01-01')->get();
            if (count($assets) > 0) {
                if(!checkPreviousSteps($id,'step13')){
                     return redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
                 }
            }
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

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            if ($lease) {

                $model = new InitialDirectCost();

                $supplier_model = new SupplierDetails();

                if ($request->isMethod('post')) {

                    if($request->has('initial_direct_cost_involved') && $request->initial_direct_cost_involved == "yes") {
                        $total = 0;
                        foreach ($request->supplier_name as $key=>$supplier) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_initial_direct_cost' => $total ]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(),[
                        'supplier_name.*.required_if' =>  'Supplier name is required',
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
                        if($request->initial_direct_cost_involved == "yes") {
                            foreach ($request->supplier_name as $key=>$value){
                                SupplierDetails::create([
                                    'initial_direct_cost_id' => $initial_direct_cost->id,
                                    'supplier_name' => $value,
                                    'direct_cost_description' => $request->direct_cost_description[$key],
                                    'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                    'supplier_currency' =>  $request->supplier_currency[$key],
                                    'amount' => $request->amount[$key],
                                    'rate' => $request->rate[$key]
                                ]);
                            }
                        }
                        // complete Step
                        confirmSteps($lease->id, 'step14');
                        return redirect(route('addlease.initialdirectcost.index', ['id' => $lease->id]))->with('status', 'Initial Direct Cost has been added successfully.');
                    }
                }

                $supplier_details = [];

                return view('lease.initial-direct-cost.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'supplier_model',
                    'supplier_details',
                    'currencies',
                    'breadcrumbs'
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

                    if($request->has('initial_direct_cost_involved') && $request->initial_direct_cost_involved == "yes") {
                        $total = 0;
                        foreach ($request->supplier_name as $key=>$supplier) {
                            $total += $request->amount[$key];
                        }
                        $request->request->add(['total_initial_direct_cost' => $total ]);
                    }

                    $validator = Validator::make($request->except('_token'), $this->validationRules(),[
                        'supplier_name.*.required_if' =>  'Supplier name is required',
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
                        SupplierDetails::query()->where('initial_direct_cost_id', '=', $initial_direct_cost_id)->delete();
                        if($request->initial_direct_cost_involved == "yes") {
                            foreach ($request->supplier_name as $key=>$value){
                                SupplierDetails::create([
                                    'initial_direct_cost_id' => $initial_direct_cost_id,
                                    'supplier_name' => $value,
                                    'direct_cost_description' => $request->direct_cost_description[$key],
                                    'expense_date' => date('Y-m-d', strtotime($request->expense_date[$key])),
                                    'supplier_currency' =>  $request->supplier_currency[$key],
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


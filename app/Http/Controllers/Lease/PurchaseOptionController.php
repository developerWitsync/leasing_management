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
use App\Currencies;
use App\LeaseAssets;
use App\PurchaseOption;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class PurchaseOptionController extends Controller
{
    protected function validationRules()
    {
        return [
            'purchase_option_clause' => 'required',
            'purchase_option_exerecisable' => 'required_if:purchase_option_clause,yes',
            'expected_purchase_date' => 'required_if:purchase_option_exerecisable,yes|nullable|date',
            'expected_lease_end_date' => 'required_if:purchase_option_exerecisable,yes|nullable|date',
            'currency' => 'required_if:purchase_option_exerecisable,yes',
            'purchase_price' => 'required_if:purchase_option_exerecisable,yes|nullable|numeric',
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
                'link' => route('addlease.purchaseoption.index', ['id' => $id]),
                'title' => 'Purchase Option'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if ($lease) {
            //Load the assets only for the assets where no selected at `exercise_termination_option_available` on lease termination
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereHas('terminationOption', function ($query) {
                $query->where('exercise_termination_option_available', '=', 'no');
            })->get();


            return view('lease.purchase-option.index', compact('breadcrumbs',
                'lease',
                'assets'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * create lease asset purchase options for the lease asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.purchaseoption.create', ['id' => $id]),
                    'title' => 'Create Purchase Option'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {

                $model = new PurchaseOption();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    if ($request->has('expected_purchase_date') && $data['expected_purchase_date'] != "") {
                        $data['expected_purchase_date'] = Carbon::parse($data['expected_purchase_date'])->format('Y-m-d');
                    }

                    if ($request->has('expected_lease_end_date') && $data['expected_lease_end_date'] != "") {
                        $data['expected_lease_end_date'] = Carbon::parse($data['expected_lease_end_date'])->format('Y-m-d');
                    }

                    if ($request->has('purchase_option_exerecisable') && $data['purchase_option_exerecisable'] == 'no') {
                        $data['expected_purchase_date'] = null;
                        $data['expected_lease_end_date'] = null;
                        $data['currency'] = null;
                        $data['purchase_price'] = null;
                    }

                    $purchase_option = PurchaseOption::create($data);

                    if ($purchase_option) {

                        // complete Step
                        $complete_step8 = confirmSteps($lease->id, 'step8');

                        return redirect(route('addlease.purchaseoption.index', ['id' => $lease->id]))->with('status', 'Lease Termination Option Details has been added successfully.');
                    }
                }

                return view('lease.purchase-option.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    /**
     * edit existing purchase options for the lease asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.purchaseoption.update', ['id' => $id]),
                    'title' => 'Update Purchase Option'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {

                $model = PurchaseOption::query()->where('asset_id', '=', $id)->first();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;

                    if ($request->has('expected_purchase_date') && $data['expected_purchase_date'] != "") {
                        $data['expected_purchase_date'] = Carbon::parse($data['expected_purchase_date'])->format('Y-m-d');
                    }

                    if ($request->has('expected_lease_end_date') && $data['expected_lease_end_date'] != "") {
                        $data['expected_lease_end_date'] = Carbon::parse($data['expected_lease_end_date'])->format('Y-m-d');
                    }

                    if ($request->has('purchase_option_exerecisable') && $data['purchase_option_exerecisable'] == 'no') {
                        $data['expected_purchase_date'] = null;
                        $data['expected_lease_end_date'] = null;
                        $data['currency'] = null;
                        $data['purchase_price'] = null;
                    }

                    $model->setRawAttributes($data);

                    if ($model->save()) {
                        return redirect(route('addlease.purchaseoption.index', ['id' => $lease->id]))->with('status', 'Purchase Option Details has been updated successfully.');
                    }
                }

                return view('lease.purchase-option.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
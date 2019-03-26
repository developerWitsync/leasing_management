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
use App\LeasePaymentInvoice;
use Illuminate\Http\Request;
use Validator;

class LeaseInvoiceController extends Controller
{
    private $current_step = 18;

    protected function validationRules()
    {
        return [
            'lease_payment_invoice_received' => 'required'
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
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.leasepaymentinvoice.index', ['id' => $id]),
                    'title' => 'Lease Payment Invoice from Lessor'
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

                $asset = LeaseAssets::query()->where('lease_id', '=', $id)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })
                    ->first();

                if($asset) {
                    $model = LeasePaymentInvoice::query()->where('lease_id', '=', $id)->first();

                    if (is_null($model)) {
                        $model = new LeasePaymentInvoice();
                    }

                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->except('_token'), $this->validationRules());

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token', 'action');
                        $data['lease_id'] = $id;

                        $model->setRawAttributes($data);

                        if ($model->save()) {
                            // complete Step
                            confirmSteps($lease->id, $this->current_step);
                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('addlease.securitydeposit.index', ['id' => $lease->id]))->with('status', 'Lease Payment Invoice details has been updated successfully.');
                            } else {

                                return redirect(route('addlease.leasepaymentinvoice.update', ['id' => $lease->id]))->with('status', 'Lease Payment Invoice details has been updated successfully.');

                            }

                        }
                    }
                    $current_step = $this->current_step;
                    return view('lease.lease-payment-invoice.index', compact('breadcrumbs',
                        'lease',
                        'model',
                        'current_step',
                        'subsequent_modify_required'
                    ));
                } else {
                    return redirect(route('addlease.securitydeposit.index', ['id' => $lease->id]));
                }

            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}

    


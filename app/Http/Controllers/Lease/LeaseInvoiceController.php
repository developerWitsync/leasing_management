<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;

use App\ExpectedLifeOfAsset;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAccountingTreatment;
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\UseOfLeaseAsset;
use App\LeasePaymentInvoice;
use Illuminate\Http\Request;
use Validator;

class LeaseInvoiceController extends Controller
{
    protected function validationRules(){
        return [
            'lease_payment_invoice_received'   => 'required'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        try{

            if(!checkPreviousSteps($id,'step16')){
                return redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
            }

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.leasepaymentinvoice.index',['id' => $id]),
                    'title' => 'Lease Payment Invoice from Lessor'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if($lease) {
                $model = LeasePaymentInvoice::query()->where('lease_id', '=', $id)->first();

                if(is_null($model)) {
                    $model = new LeasePaymentInvoice();
                }

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $id;

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.leasepaymentinvoice.update',['id' => $lease->id]))->with('status', 'Lease Payment Invoice details has been updated successfully.');
                    }
                }

                return view('lease.lease-payment-invoice.index', compact('breadcrumbs',
                    'lease',
                'model'
                ));
            } else {
                abort(404);
            }
        }catch (\Exception $e){
            dd($e);
        }

    }
}

    
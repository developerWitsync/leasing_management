<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 07/01/19
 * Time: 10:30 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\LeaseDurationClassified;

use App\ReportingCurrencySettings;
use App\ContractClassifications;
use App\ForeignCurrencyTransactionSettings;

use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class ReviewSubmitController extends Controller
{
    protected function validationRules(){
        return [
            'interest_rate'   => 'required',
            'annual_average_esclation_rate' => 'required',
            'discount_rate_to_use' => 'required|numeric|min:2'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){

        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.reviewsubmit.index',['id' => $id]),
                'title' => 'Review & Submit'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
             
             $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->get();

            //  $sublease_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('specific_use',2)->whereNotIn('category_id',[8,5])->whereHas('leaseDurationClassified',  function($query){
            //     $query->where('lease_contract_duration_id', '=', '3');
            // })->get();

             $reporting_currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
              $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
              $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())->get();
           
            return view('lease.review-submit.index', compact(
                'lease',
                'assets',
                'breadcrumbs',
                'reporting_currency_settings',
                'contract_classifications',
                'reporting_foreign_currency_transaction_settings'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add select discount rate details for an asset in NL-10
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseSelectDiscountRate();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $select_discount_value = LeaseSelectDiscountRate::create($data);
                    if($select_discount_value){
                        return redirect(route('addlease.discountrate.index',['id' => $lease->id]))->with('status', 'Select Discount Rate has been added successfully.');
                    }
                }
                return view('lease.review-submit.create', compact(
                    'model',
                    'lease',
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
     * edit existing select discount rate details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseSelectDiscountRate::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                   
                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.discountrate.index',['id' => $lease->id]))->with('status', 'Select Discount Rate has been updated successfully.');
                    }
                }
                return view('lease.review-submit.update', compact(
                    'model',
                    'lease',
                    'asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
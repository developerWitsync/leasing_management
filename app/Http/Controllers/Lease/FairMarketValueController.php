<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;


use App\Countries;
use App\ExpectedLifeOfAsset;
use App\Http\Controllers\Controller;
use App\Lease;
use App\Currencies;
use App\LeaseAccountingTreatment;
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\UseOfLeaseAsset;
use App\FairMarketValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssetPayments;
use Illuminate\Http\Request;
use Validator;

class FairMarketValueController extends Controller
{
    protected function validationRules(){
        return [
            'is_market_value_present'   => 'required',
            'currency' => 'required_if:is_market_value_present,yes',
            'similar_asset_items'   => 'required_if:is_market_value_present,yes',
            'unit'  => 'required_if:is_market_value_present,yes',
            'total_units'  => 'required_if:is_market_value_present,yes',
            'attachment'                  => 'file|mimes:jpeg,pdf,doc'
        ];
    }

    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){

        if(!checkPreviousSteps($id,'step3')){
           return redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
       }
            $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.fairmarketvalue.index',['id' => $id]),
                'title' => 'Fair Market Value'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.fair-market-value.index', compact('breadcrumbs',
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {
                $model = FairMarketValue::query()->where('asset_id', '=', $id)->first();
                $model = new FairMarketValue();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['attachment'] = "";
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->hasFile('attachment')){
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    $market_value = FairMarketValue::create($data);

                    if($market_value){

                        // complete Step
                        $lease_id = $lease->id;
                        $step= 'step4';
                        $complete_step4 = confirmSteps($lease_id,$step);

                        return redirect(route('addlease.fairmarketvalue.index',['id' => $lease->id]))->with('status', 'Fair Market has been added successfully.');
                    }
                }

                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if(collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.fair-market-value.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    /**
     * edit existing fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = FairMarketValue::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['attachment'] = "";
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->hasFile('attachment')){
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.fairmarketvalue.index',['id' => $lease->id]))->with('status', 'Fair Market has been updated successfully.');
                    }
                }

                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if(collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.fair-market-value.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
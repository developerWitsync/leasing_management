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
use App\LeaseResidualValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssetPaymentsNature;
use Illuminate\Http\Request;
use Validator;

class LeaseResidualController extends Controller
{
    protected function validationRules(){
        return [
            'lease_payemnt_nature_id'   => 'required',
            'amount_determinable'   => 'required',
            'similar_asset_items'   => 'required_if:any_residual_value_gurantee,yes',
            'residual_gurantee_value'  => 'required_if:any_residual_value_gurantee,yes',
            'total_residual_gurantee_value'  => 'required_if:any_residual_value_gurantee,yes',
            'attachment' => 'file|mimes:jpeg,pdf,doc'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.residual-value-gurantee.index', compact(
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

                $model = new LeaseResidualValue();
                //$model->any_residual_value_gurantee=$request->any_residual_value_gurantee;

                if($request->isMethod('post')) {

                   $validator = Validator::make($request->except('_token'), $this->validationRules());

                    /*if($validator->fails()){
                        return redirect()->back()->with(['class'=>'active','model' => $model])->withInput($request->except('_token'))->withErrors($validator->errors());
                    }*/
                    
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
                      
                    $residual_value = LeaseResidualValue::create($data);

                    if($residual_value){
                        return redirect(route('addlease.residual.index',['id' => $lease->id]))->with('status', 'Residual value Gurantee has been added successfully.');
                    }
                }
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            
                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if(collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.residual-value-gurantee.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_payments_nature',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * edit existing Residual value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseResidualValue::query()->where('asset_id', '=', $id)->first();

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
                        return redirect(route('addlease.residual.index',['id' => $lease->id]))->with('status', 'Residual Gurantee has been updated successfully.');
                    }
                }
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if(collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.residual-value-gurantee.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_payments_nature',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            //dd($e);
            dd($e->getMessage());
        }
    }
}
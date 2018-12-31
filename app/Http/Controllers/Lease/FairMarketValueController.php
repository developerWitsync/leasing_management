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
use Illuminate\Http\Request;
use Validator;

class FairMarketValueController extends Controller
{
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.fair-market-value.index', compact(
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new FairMarketValue();

                if($request->isMethod('post')) {
                    dd($request->all());
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

     public function store(Request $request) {
        try{
         $assets = new FairMarketValue;
          $rules = [
                'is_market_value_available'  => 'required',
            ];
         $validator = Validator::make($request->except('_token'), $rules);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
            }




        
        $assets->lease_id = $request->input('lease_id');
        $assets->asset_id = $request->input('asset_id');
        $assets->is_market_value_present = $request->input('is_market_value_available');
        $assets->unit = $request->input('unit');
        $assets->total_units = 3*($assets->unit);
        $assets->source = $request->input('source');
        $assets->save();
        return redirect('lease/fair-market-value/index/'.$assets->lease_id.'#step2');
    } catch (\Exception $e) {
            dd($e);
        }
}
}
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
use App\LeaseAccountingTreatment;
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\UseOfLeaseAsset;
use App\FairMarketValue;
use Illuminate\Http\Request;
use Validator;

class FairMarketValueController extends Controller
{
    /**
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

     public function store(Request $request)
    {

         $this->validate(request(),[
            //put fields to be validated here

            ]);


        $assets = new FairMarketValue;
        $assets->lease_id = $request->input('lease_id');
        $assets->asset_id = $request->input('asset_id');
        $assets->is_market_value_present = $request->input('variable_amount_determinable');
        $assets->unit = $request->input('unit');
        $assets->total_units = 3*($assets->unit);
        $assets->source = $request->input('source');
        $assets->save();
        return redirect('lease/fair-market-value/index/'.$assets->lease_id.'#step2');
    }
}
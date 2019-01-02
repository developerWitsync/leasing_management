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
use Illuminate\Http\Request;
use Validator;

class UnderlyingLeaseAssetController extends Controller
{
    /**
     * Renders the Underlying Lease asset form for the Lease with Primary key $id
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.leaseasset.index',['id' => $id]),
                'title' => 'Underlying Lease Assets'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
         
        if($lease) {

            $lease_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->get()->toArray();

            if($request->isMethod('post')) {

                $validator = Validator::make($request->except('_token'),[
                    'no_of_lease_assets' => 'requied|numeric',
                    'ula_code.*' => 'required',
                    'asset_category.*' => 'required|exists:lease_assets_categories,id',
                    'asset_sub_category.*'  => 'required|exists:lease_assets_sub_categories_settings,id',
                    'name.*' => 'required',
                    'similar_characteristic_items.*' => 'required|numeric'
                ], [
                    'no_of_lease_assets.required' => 'Number of Underlying lease assets cannot be blank.',
                    'no_of_lease_assets.numeric' => 'Number of Underlying lease assets must be a number.',
                    'ula_code.required' => 'Unique ULA code is required.',
                    'asset_category.*.required'   => 'Underlying Leased Asset Category cannot be blank.',
                    'asset_sub_category.*.required'   => 'Underlying Leased Asset Classification cannot be blank.',
                    'name.*.required'  => 'Name of the Underlying Lease Asset cannot be blank.',
                    'similar_characteristic_items.*.required' => 'Number of Units of Lease Assets of Similar Characteristics cannot be blank.',
                    'similar_characteristic_items.*.numeric' => 'Number of Units of Lease Assets of Similar Characteristics must be a number.'
                ]);

                if($validator->fails()) {
                    return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                }

                $created_lease_asset_ids = [];

                for($i = 0 ; $i < $lease->total_assets; $i++) {
                    $lease_asset = LeaseAssets::query()->where('uuid', '=', $request->ula_code[$i])->first();
                    if($lease_asset) {
                        $created_lease_asset_ids[$lease_asset->id] = $lease_asset->id;
                        $lease_asset->setRawAttributes([
                            'lease_id'      => $lease->id,
                            'uuid'          => $request->ula_code[$i],
                            'category_id'   => $request->asset_category[$i],
                            'sub_category_id'   => $request->asset_sub_category[$i],
                            'name'              => $request->name[$i],
                            'similar_asset_items'   => $request->similar_characteristic_items[$i]
                        ]);
                        $lease_asset->save();
                    } else {

                        $lease_asset = LeaseAssets::create([
                            'lease_id'          => $lease->id,
                            'uuid'              => $request->ula_code[$i],
                            'category_id'       => $request->asset_category[$i],
                            'sub_category_id'   => $request->asset_sub_category[$i],
                            'name'              => $request->name[$i],
                            'similar_asset_items'   => $request->similar_characteristic_items[$i]
                        ]);
                        $created_lease_asset_ids[$lease_asset->id] = $lease_asset->id;
                    }
                }

                //delete other lease_assets if exists for the current logged in business account
                LeaseAssets::query()->whereNotIn('id', $created_lease_asset_ids)->delete();
                return redirect()->back()->with('status', 'Lease Assets has been created successfully. Click on Complete Details button to complete other details for the lease assets.');
            }


            $numbers_of_lease_assets  = LeaseAssetsNumberSettings::query()->select('number')->whereIn('business_account_id', getDependentUserIds())->get();
            $lease_assets_categories  = LeaseAssetCategories::query()->with('subcategories')->get();
            $la_similar_charac_number = LeaseAssetSimilarCharacteristicSettings::query()
                ->select('number')
                ->whereIn('business_account_id', getDependentUserIds())
                ->get();
           return view('lease.lease-assets.index', compact('breadcrumbs',
                'lease',
                'numbers_of_lease_assets',
                'lease_assets_categories',
                'la_similar_charac_number',
                'lease_assets'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * Render form so that the user can complete the details for the lease asset one by one
     * @param $lease_id
     * @param $asset_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assetDetails($lease_id,$asset_id, Request $request){
        try{
            $lease = Lease::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('id', '=', $lease_id)
                ->with('leaseType')
                ->with('assets')
                ->first();

            $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();

            if($lease && $asset) {

                if($request->isMethod('post')) {

                    Validator::extend('required_if_prior_to_date', function ($attribute, $value, $parameters, $validator) {
                        if(date('Y-m-d',strtotime($parameters['0'])) < date('Y-m-d', strtotime('2019-01-01'))){
                            if(is_null($value)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    });

                    $validator = Validator::make($request->except('_token'),[
                        'other_details' => 'required',
                        'country_id'   => 'required|exists:countries,id',
                        'location'  => 'required',
                        'specific_use'  => 'required|exists:lease_asset_use_master,id',
                        'use_of_asset'  => 'required_if:specific_use,1',
                        'expected_life' => 'required|exists:expected_useful_life_of_asset,id',
                        'lease_start_date' => 'required|date',
                        'lease_free_period' => 'numeric',
                        'accural_period'    => 'required|date',
                        'lease_end_date'    => 'required|date|after:accural_period',
                        'lease_term'        => 'required',
                        'accounting_treatment' => 'required_if_prior_to_date:'.$request->accural_period
                    ],[
                        'country_id.required'          => 'The country field is required.',
                        'other_details.required'       =>   'The Other Details field is required.',
                        'specific_use.required'        =>   'The Specific use of asset field is required.',
                        'use_of_asset.required_if'     =>   'The Use of Asset is required if specific use of the Lease Asset is Own Use.',
                        'expected_life.required'       =>   'The expected life of asset field is required.',
                        'lease_start_date.required'    =>   'The Lease start date field is required.',
                        'lease_start_date.date'        =>   'The Lease start date field must be a valid date.',
                        'lease_free_period.numeric'    =>   'The Initial Lease Free Period field must be a numeric.',
                        'accural_period.required'      =>   'The Start Date of Lease Payment / Accrual Period field is required.',
                        'lease_end_date.required'      =>   'The Lease End Date field is required.',
                        'lease_end_date.date'          =>   'The Lease End Date field must be a valid date.',
                        'accounting_treatment.required_if_prior_to_date'   => 'The accounting period is required when Start Date of Lease Payment / Accrual Period is prior to Jan 01, 2019.'
                    ]);

                    if($validator->fails()) {
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                    }

                    $data = $request->except('_token');
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['accural_period'] = date('Y-m-d', strtotime($request->accural_period));
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));
                    $data['is_details_completed']  = '1';
                    $asset->setRawAttributes($data);
                    $asset->save();
                    
                    return redirect(
                        route('addlease.leaseasset.index', ['id' => $lease->id,'total_assets' => count($lease->assets)])
                    )->with('status', "Asset Details has been updated successfully.");

                }

                $countries  = Countries::query()->where('status', '=', '1')->get();
                $use_of_lease_asset = UseOfLeaseAsset::query()->where('status', '=', '1')->get();
                $expected_life_of_assets = ExpectedLifeOfAsset::query()->whereIn('business_account_id', getDependentUserIds())->get();
                $accounting_terms  = LeaseAccountingTreatment::query()->where('upto_year', '=', '2018')->get();
                return view('lease.lease-assets.completedetails', compact(
                    'lease',
                    'asset',
                    'countries',
                    'use_of_lease_asset',
                    'expected_life_of_assets',
                    'accounting_terms'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * fetch the subcategories for a particular selected category on NL2 sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchSubCategories($id, Request $request){
        if($request->ajax()) {
            $subcategories = LeaseAssetSubCategorySetting::query()->whereIn('business_account_id', getDependentUserIds())->where('category_id', '=', $id)->get();
            $html = "<option value=''>--Select--</option>";
            foreach ($subcategories as $subcategory) {
                $html .= '<option value="'.$subcategory->id.'">'.$subcategory->title.'</option>';
            }
            return response()->json([
                'html' => $html
            ], 200);
        } else {
            abort(404);
        }
    }
}
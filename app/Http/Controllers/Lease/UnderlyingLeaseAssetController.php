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
use App\LeaseAssetCountries;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\UseOfLeaseAsset;
use App\GeneralSettings;
use App\LeaseCompletedSteps;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class UnderlyingLeaseAssetController extends Controller
{
    private $current_step = 2; 
    /**
     * One Lease can have single lease asset hence show the form to provide all the details at once.
     * Create the lease asset form
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_V2($id){
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

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())
            ->where('id', '=', $id)
            ->with('leaseType')
            ->with('assets')
            ->where('status', '=', '0')
            ->first();

        if($lease) {
            if(count($lease->assets) > 0) {
                $asset = $lease->assets->first();
            } else {
                $asset = new LeaseAssets();
            }
            //check if the Subsequent Valuation is applied for the lease modification
            $subsequent_modify_required = $lease->isSubsequentModification();
            $lease_assets_categories  = LeaseAssetCategories::query()->with(['subcategories'=>function($query){
                $query->whereIn('business_account_id', getDependentUserIds());
            }])->get();
           
            $la_similar_charac_number = LeaseAssetSimilarCharacteristicSettings::query()
                ->select('number')
                ->whereIn('business_account_id', getDependentUserIds())
                ->get();

//            $countries  = Countries::query()
//                ->where('status', '=', '1')
//                ->get();

            $countries = LeaseAssetCountries::query()
                ->with('country')
                ->whereIn('business_account_id', getDependentUserIds())
                ->get();

            $use_of_lease_asset = UseOfLeaseAsset::query()->where('status', '=', '1')->get();
            $expected_life_of_assets = ExpectedLifeOfAsset::query()->whereIn('business_account_id', getDependentUserIds())->get();
            $accounting_terms  = LeaseAccountingTreatment::query()->where('upto_year', '=', '2018')->get();

            // get max previous year from general settings for lease start year which will be minimum year
            $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
            $get_steps= LeaseCompletedSteps::query()->where('lease_id','=',$id)->where('completed_step',2)->first();

             //to get current step for steps form
           // $current_step = isEnabled($id,2);
            $current_step = $this->current_step;
            
            $ulacode = createUlaCode();


            return view('lease.lease-assets.indexv2', compact('breadcrumbs',
                'lease',
                'lease_assets_categories',
                'la_similar_charac_number',
                'asset',
                'subsequent_modify_required',
                'countries',
                'use_of_lease_asset',
                'expected_life_of_assets',
                'accounting_terms',
                'settings',
                'get_steps',
                'current_step',
                'ulacode'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * create or update a new lease asset for the lease id within the parameter id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
            $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
            if($lease) {

                if($settings->date_of_initial_application == 2){
                    $base_date = Carbon::parse(getParentDetails()->baseDate->final_base_date)->subYear(1)->format('Y-m-d');
                } else {
                    $base_date = getParentDetails()->baseDate->final_base_date;
                }


                $base_date_formatted = Carbon::parse($base_date)->format('F d, Y');

                if(count($lease->assets) > 0) {
                    $asset = $lease->assets->first();
                } else {
                    $asset = new LeaseAssets();
                }

                Validator::extend('required_if_prior_to_date', function ($attribute, $value, $parameters, $validator) use ($base_date) {
                    if (date('Y-m-d', strtotime($parameters['0'])) < date('Y-m-d', strtotime($base_date))) {
                        if (is_null($value)) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }
                });

                $rules = [
                    'category_id' => 'required|exists:lease_assets_categories,id',
                    'sub_category_id'   => 'required|exists:lease_assets_sub_categories_settings,id',
                    'name'  => 'required',
                    'similar_asset_items'   =>'required|numeric',
                    'country_id' => 'required|exists:countries,id',
                    'location' => 'required',
                    'specific_use' => 'required|exists:lease_asset_use_master,id',
                    'use_of_asset' => 'required_if:specific_use,1',
                    'expected_life' => 'required|exists:expected_useful_life_of_asset,id',
                    'lease_start_date' => 'required|date_format:d-M-Y',
                    'lease_free_period' => 'numeric',
                    'accural_period' => 'required|date_format:d-M-Y',
                    'lease_end_date' => 'required|date_format:d-M-Y|after:accural_period',
                    'lease_term' => 'required',
                    'accounting_treatment' => 'required_if_prior_to_date:' . $request->accural_period,
                ];

                $messages = [
                    'country_id.required' => 'The country field is required.',
                    'specific_use.required' => 'The Specific use of asset field is required.',
                    'use_of_asset.required_if' => 'The Use of Asset is required if specific use of the Lease Asset is Own Use.',
                    'expected_life.required' => 'The expected life of asset field is required.',
                    'lease_start_date.required' => 'The Lease start date field is required.',
                    'lease_start_date.date' => 'The Lease start date field must be a valid date.',
                    'lease_free_period.numeric' => 'The Initial Lease Free Period field must be a numeric.',
                    'accural_period.required' => 'The Start Date of Lease Payment / Accrual Period field is required.',
                    'lease_end_date.required' => 'The Lease End Date field is required.',
                    'accounting_treatment.required_if_prior_to_date' => 'The accounting period is required when Start Date of Lease Payment / Accrual Period is prior to '.$base_date_formatted.'.'
                ];


                if (date('Y-m-d', strtotime($request->accural_period)) < date('Y-m-d', strtotime($base_date)) && $request->accounting_treatment == 1) {
                    $rules['using_lease_payment'] = 'required';
                }


                $validator = Validator::make($request->except('_token'), $rules, $messages);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                }
                $data = $request->except('_token', 'action');

                if($settings->date_of_initial_application == 2 && date('Y-m-d', strtotime($request->accural_period)) < date('Y-m-d', strtotime($base_date))){
                    $data['using_lease_payment'] = 2;
                } elseif($settings->date_of_initial_application == 2) {
                    $data['using_lease_payment'] = null;
                }

                $data['lease_id'] = $lease->id;
                $data['lease_end_date'] = Carbon::parse($request->lease_end_date);
                $data['accural_period'] = date('Y-m-d', strtotime($request->accural_period));
                $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                $data['is_details_completed'] = '1';
                $asset->setRawAttributes($data);
                $asset->save();

                // make the entry to the completed steps table so that the log can be created to check the completed steps
                confirmSteps($id, 2);

                if($request->has('action') && $request->action == "next") {
                    return redirect(
                        route('addlease.leaseterminationoption.index',['id' => $lease->id])
                    )->with('status', "Asset Details has been updated successfully.");

                } else {

                    return redirect(
                        route('addlease.leaseasset.index', ['id' => $lease->id])
                    )->with('status', "Asset Details has been updated successfully.");

                }

            }else{
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

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

            //check if the Subsequent Valuation is applied for the lease modification
            $subsequent_modify_required = $lease->isSubsequentModification();

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
                LeaseAssets::query()->where('lease_id', '=',$lease->id)->whereNotIn('id', $created_lease_asset_ids)->delete();
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
                'lease_assets',
                'subsequent_modify_required'
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
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.leaseasset.completedetails',['lease' => $lease_id, 'asset' => $asset_id]),
                    'title' => 'Complete Details for Underlying Lease Assets'
                ],
            ];

            $lease = Lease::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('id', '=', $lease_id)
                ->with('leaseType')
                ->with('assets')
                ->first();

            //check if the Subsequent Valuation is applied for the lease modification
            $subsequent_modify_required = $lease->isSubsequentModification();

            $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();
            if($lease && $asset) {

                $base_date =  getParentDetails()->baseDate->final_base_date;

                if($request->isMethod('post')) {

                    Validator::extend('required_if_prior_to_date', function ($attribute, $value, $parameters, $validator) use ($base_date) {
                        if(date('Y-m-d',strtotime($parameters['0'])) < date('Y-m-d', strtotime($base_date))){
                            if(is_null($value)) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    });

                    $rules = [
                        'country_id'   => 'required|exists:countries,id',
                        'location'  => 'required',
                        'specific_use'  => 'required|exists:lease_asset_use_master,id',
                        'use_of_asset'  => 'required_if:specific_use,1',
                        'expected_life' => 'required|exists:expected_useful_life_of_asset,id',
                        'lease_start_date' => 'required|date|date_format:d-M-Y',
                        'lease_free_period' => 'numeric',
                        'accural_period'    => 'required|date|date_format:d-M-Y',
                        'lease_end_date'    => 'required|date|date_format:d-M-Y|after:accural_period',
                        'lease_term'        => 'required',
                        'accounting_treatment' => 'required_if_prior_to_date:'.$request->accural_period,
                    ];

                    $messages =  [
                        'country_id.required'          => 'The country field is required.',
                        'specific_use.required'        =>   'The Specific use of asset field is required.',
                        'use_of_asset.required_if'     =>   'The Use of Asset is required if specific use of the Lease Asset is Own Use.',
                        'expected_life.required'       =>   'The expected life of asset field is required.',
                        'lease_start_date.required'    =>   'The Lease start date field is required.',
                        'lease_start_date.date'        =>   'The Lease start date field must be a valid date.',
                        'lease_free_period.numeric'    =>   'The Initial Lease Free Period field must be a numeric.',
                        'accural_period.required'      =>   'The Start Date of Lease Payment / Accrual Period field is required.',
                        'lease_end_date.required'      =>   'The Lease End Date field is required.',
                        'lease_end_date.date'          =>   'The Lease End Date field must be a valid date.',
                        'accounting_treatment.required_if_prior_to_date'   => 'The accounting period is required when Start Date of Lease Payment / Accrual Period is prior to '.Carbon::parse($base_date)->format('F d, Y').'.'
                    ];

                    if(date('Y-m-d',strtotime($request->accural_period)) < date('Y-m-d', strtotime($base_date))){
                        $rules['using_lease_payment'] = 'required';
                    }

                    $validator = Validator::make($request->except('_token'),$rules, $messages);

                    if($validator->fails()) {  
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                    }
                    $data = $request->except('_token');
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));
                    $data['accural_period'] = date('Y-m-d', strtotime($request->accural_period));
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['is_details_completed']  = '1';
                    $asset->setRawAttributes($data);
                    $asset->save();

                    // make the entry to the completed steps table so that the log can be created to check the completed steps
                    confirmSteps($lease_id,2);
                    
                    return redirect(
                        route('addlease.leaseasset.index', ['id' => $lease->id,'total_assets' => count($lease->assets)])
                    )->with('status', "Asset Details has been updated successfully.");

                }

                $countries  = Countries::query()->where('status', '=', '1')->get();
                $use_of_lease_asset = UseOfLeaseAsset::query()->where('status', '=', '1')->get();
                $expected_life_of_assets = ExpectedLifeOfAsset::query()->whereIn('business_account_id', getDependentUserIds())->get();
                $accounting_terms  = LeaseAccountingTreatment::query()->where('upto_year', '=', '2018')->get();

                // get max previous year from general settings for lease start year which will be minimum year
                $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                return view('lease.lease-assets.completedetails', compact(
                    'lease',
                    'asset',
                    'countries',
                    'use_of_lease_asset',
                    'expected_life_of_assets',
                    'accounting_terms',
                    'settings',
                    'breadcrumbs',
                    'subsequent_modify_required'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
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

    /**
     * fetch the subcategories for a particular selected category on NL2 sheet
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDateDifference(Request $request){
        if($request->ajax()) {

            $lease_start_date = Carbon::parse($request->lease_start_date);
            $lease_end_date = Carbon::parse($request->lease_end_date)->addDay(1);
           
            $date_diff = $lease_end_date->diffForHumans($lease_start_date, true, false, 4);
            return response()->json([
                'html' => $date_diff
            ], 200);
        } else {
            abort(404);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:36 AM
 */

namespace App\Http\Controllers\Settings;


use App\CategoriesLeaseAssetExcluded;
use App\DepreciationMethod;
use App\ExpectedLifeOfAsset;
use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\InitialValuationModels;
use App\LeaseAssetCategories;
use App\LeaseAssetSubCategorySetting;
use App\Ledgers;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Session;

class LeaseAssetsController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.leaseassets'),
                'title' => 'Lease Assets Settings'
            ]
        ];
        $expected_life_of_assets = ExpectedLifeOfAsset::query()->where('business_account_id', '=', auth()->user()->id)->get();

        $lease_assets_categories = LeaseAssetCategories::query()->where('status', '=', '1')->get();

        $general_settings = GeneralSettings::query()
          ->whereIn('business_account_id', getDependentUserIds())
          ->first();

        if(is_null($general_settings)){
          return redirect('/settings/general')->with('error', 'Please complete the General Settings first.');
        }

        return view('settings.leaseassets.index', compact(
            'breadcrumbs',
            'expected_life_of_assets',
            'lease_assets_categories',
            'general_settings'
        ));
    }

    /**
     * add the useful life of asset in years setting for the current logged in business account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLife(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'expected_life_number' => [
                        'required',
                        'numeric',
                        Rule::unique('expected_useful_life_of_asset', 'years')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'expected_life_number.unique' => 'This number has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = ExpectedLifeOfAsset::create([
                    'years' => $request->expected_life_number,
                    'business_account_id' => auth()->user()->id
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Expected useful life of asset has been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Edit an existing useful life of asset for the curent logged in business account
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editlife($id, Request $request){
        try{
            if($request->ajax()){
                $life_of_asset = ExpectedLifeOfAsset::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('expected_useful_life_of_asset', 'years')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $life_of_asset->years = $request->title;
                    $life_of_asset->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.leaseassets._edit_useful_life_of_asset', compact('life_of_asset'));
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * delete a particular life of asset for the current logged in business account
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLife($id, Request $request){
        try{
            if($request->ajax()) {
                $life_of_asset = ExpectedLifeOfAsset::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($life_of_asset) {
                    $life_of_asset->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Create the Lease Asset Type settings for the current logged in user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function addCategorySettings($id, Request $request){
        try{
            $lease_asset_category = LeaseAssetCategories::query()->findOrFail($id);
            $is_excluded = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->where('category_id', '=', $id)
                ->count();
            if($request->isMethod('post')) {
                $validator =  Validator::make($request->except('_token'),[
                    'title' => [
                        'required',
                        Rule::unique('lease_assets_sub_categories_settings', 'title')->where(function ($query) use ($id) {
                            return $query->whereIn('business_account_id', getDependentUserIds())->where('category_id','=', $id);
                        })
                    ],
                    'depreciation_method_id' => 'required_if:is_excluded,1',
                    'initial_valuation_model_id' => 'required_if:is_excluded,1'
                ], [
                    'depreciation_method_id.required_if' => 'Depreciation Method field is required.',
                    'initial_valuation_model_id.required_if' => 'Initial Valuation Model field is required.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                }

                $request->request->add(['business_account_id' => auth()->user()->id, 'category_id' => $id]);

                $setting = LeaseAssetSubCategorySetting::create($request->except('_token', 'is_excluded'));

                if($setting) {
                    return redirect(route('settings.leaseassets'))->with('status', 'Lease Asset Type setting has been saved successfully.');
                }
            }

            $depreciation_method = DepreciationMethod::query()->get();
            $initial_valuation_model = InitialValuationModels::query()->get();
            return view('settings.leaseassets.addcategorysettings', compact(
                'lease_asset_category',
                'depreciation_method',
                'is_excluded',
                'initial_valuation_model'
            ));
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Fetch the Lease assets category settings for the different categories created by the current logged in user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchCategorySettings($id, Request $request){
        try{
            if ($request->ajax()) {

                $model = LeaseAssetSubCategorySetting::query()
                    ->where('business_account_id', '=', auth()->user()->id)
                    ->where('category_id', '=', $id)
                    ->with('depreciationMethod');

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('title', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('created_at', function($data){
                        return date('jS F Y h:i a', strtotime($data->created_at));
                    })
                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * update an already existing Lease Asset Type Setting for the logged in user.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editCategorySetting($id, Request $request){
        try{
            $setting = LeaseAssetSubCategorySetting::query()
                ->with('depreciationMethod')
                ->with('category')
                ->where('business_account_id', '=', auth()->user()->id)
                ->where('id', '=', $id)
                ->first();

            $is_excluded = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->where('category_id', '=', $id)
                ->count();

            if($setting) {
                if($request->isMethod('post')) {

                    $validator =  Validator::make($request->except('_token'),[
                        'title' => [
                            'required',
                            Rule::unique('lease_assets_sub_categories_settings', 'title')->where(function ($query) use ($setting) {
                                return $query->whereIn('business_account_id', getDependentUserIds())->where('category_id','=', $setting->category_id);
                            })->ignore($id)
                        ],
                        'depreciation_method_id' => 'required_if:is_excluded,1',
                        'initial_valuation_model_id' => 'required_if:is_excluded,1'
                    ], [
                        'depreciation_method_id.required_if' => 'Depreciation Method field is required.',
                        'initial_valuation_model_id.required_if' => 'Initial Valuation Model field is required.'
                    ]);

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }
                    $request->request->add(['business_account_id' => auth()->user()->id, 'category_id' => $setting->category_id]);

                    $setting->setRawAttributes($request->except('_token', 'is_excluded'));
                    if($setting->save()) {
                        return redirect(route('settings.leaseassets'))->with('status', 'Lease Asset Type setting has been updated successfully.');
                    }
                }
                $depreciation_method = DepreciationMethod::query()->get();
                $initial_valuation_model = InitialValuationModels::query()->get();
                $ledgers_data = getLedgersData($id);
                $category_id = $id;
                $ledger_level = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                $ledger_level = $ledger_level->ledger_level;
                $ledger_disabled = false;
                if($ledger_level == 1){
                  $ledger_disabled = true;
                }
                return view('settings.leaseassets.editcategorysettings', compact(
                    'setting',
                    'depreciation_method',
                    'is_excluded',
                    'initial_valuation_model',
                    'ledger_level',
                    'ledgers_data',
                    'category_id',
                    'ledger_disabled'
                ));
            } else {
                abort(404);
            }

        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }

    /**
     * delete a particular created Lease Asset Type Setting for the current logged in user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteCategorySetting($id, Request $request){
        try{
            if($request->ajax()) {
                $setting = LeaseAssetSubCategorySetting::query()
                    ->where('business_account_id', '=', auth()->user()->id)
                    ->where('id', '=', $id)
                    ->first();

                if($setting) {
                    $setting->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

  /**
   * Updates the Ledger level options for the users.
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
    public function saveLedgerOptions(Request $request)
    {
      try{
        $validator = Validator::make($request->except('_token'), [
          'ledger_level' => 'required|numeric'
        ],[
          'ledger_level.required' => 'Please select any one option as your ledger level.'
        ]);

        if($validator->fails()){
          return redirect()->back()->withErrors($validator->errors());
        }

        $general_settings = GeneralSettings::query()->whereIn('business_account_id',getDependentUserIds())
          ->first();

        if($general_settings){
          Ledgers::query()->whereIn('business_account_id', getDependentUserIds())->delete();
          $general_settings->setAttribute('ledger_level', $request->get('ledger_level'));
          $general_settings->save();
          return redirect()->back()->with('status', 'Ledger Level has been updated successfully.');
        } else {
          return redirect()->back()->with('failure', 'Please complete the General Settings first.');
        }

      } catch (\Exception $e){
        abort(404);
      }
    }
}
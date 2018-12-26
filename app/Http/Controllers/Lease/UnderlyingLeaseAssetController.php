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
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
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
        $total_number_of_assets = $request->has('total_assets')?$request->total_assets:1;
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->first();
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

                for($i = 0 ; $i < $request->total_lease_assets; $i++) {
                    $lease_asset = LeaseAssets::query()->where('uuid', '=', $request->ula_code[$i])->first();
                    if($lease_asset) {
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
                            'lease_id'      => $lease->id,
                            'uuid'          => $request->ula_code[$i],
                            'category_id'   => $request->asset_category[$i],
                            'sub_category_id'   => $request->asset_sub_category[$i],
                            'name'              => $request->name[$i],
                            'similar_asset_items'   => $request->similar_characteristic_items[$i]
                        ]);
                    }

                    $created_lease_asset_ids[] = $lease_asset->id;
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
            return view('lease.lease-assets.index', compact(
                'lease',
                'numbers_of_lease_assets',
                'total_number_of_assets',
                'lease_assets_categories',
                'la_similar_charac_number',
                'lease_assets'
            ));
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
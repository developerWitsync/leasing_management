<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 30/01/19
 * Time: 11:37 AM
 */

namespace App\Http\Controllers\Leasevaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lease;
use App\ModifyLeaseApplication;
use App\LeaseAssets;
use App\LeaseAssetCategories;
use Validator;


class LeaseValuationController extends Controller
{
     
	/**
     * Render the table for all the leases
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
     // dd($request->all());
         $leases = Lease::query()->whereIn('business_account_id', getDependentUserIds());

         if($request->has('id')) {
            $category_id = $request->id;
            $leases = $leases->whereHas('assets' , function($query) use ($category_id){
                $query->where('category_id',$category_id);
            });
             //dd($leases->category_id);
         }

         //dd($leases->get());
        if($leases) {
            //Load the assets only which will  not in is_classify_under_low_value = Yes in NL10 (Lease Select Low Value)and will not in very short tem/short term lease in NL 8.1(lease_contract_duration table) and not in intengible under license arrangements and biological assets (lease asset categories)
            //Capitalized Lease Assets
            if(!$request->has('id')){
               foreach ($leases as $key => $lease) {
               $own_assets_capitalized = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('specific_use',1)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->whereNotIn('category_id',[5,8])->get();


                    $sublease_assets_capitalized = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('specific_use',2)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->whereNotIn('category_id',[5,8])->get(); 
                }
            }
            else{

                $category_id = $request->id;
                foreach ($leases as $key => $lease) {

                    $own_assets_capitalized = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('specific_use',1)
                    ->where('category_id',$category_id)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->get();

                    $sublease_assets_capitalized = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('specific_use',2)
                    ->where('category_id',$category_id)
                    ->whereHas('leaseSelectLowValue',  function($query){
                    $query->where('is_classify_under_low_value', '=', 'no');
                    })->whereHas('leaseDurationClassified',  function($query){
                    $query->where('lease_contract_duration_id', '=', '3');
                    })->get();
                
                }
                 //dd($own_assets_capitalized); 
            }
           $lease_asset_categories = LeaseAssetCategories::query()->where('is_capitalized', '=', '1')->get();
        }
    
        return view('leasevaluation.index',compact('own_assets_capitalized',
        'sublease_assets_capitalized','lease_asset_categories'
     ));
    }

    /**
     * fetch the lease Valuation table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function noncapitalized(Request $request){
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->get();
         //dd($lease->id);
        if($lease) {
         //NOn Capitalized Lease Assets
              foreach ($lease as $key => $lease) {

               //Non Capitalized Lease Assets
            
                $own_assets_non = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                ->where('specific_use',1)
                ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
                })->get();


                $sublease_assets_non = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                ->where('specific_use',2)
                ->whereHas('leaseSelectLowValue',  function($query){
                $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
                })->get();
            }
        }
            return view('leasevaluation.noncapitalized',compact('own_assets_non','sublease_assets_non'
     ));
    }
   
 }

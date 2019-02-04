<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lease;
use App\LeaseAssets;
use App\LeaseAssetCategories;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_active_lease_asset = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1')->with('assets')->count();
        
        $leases = Lease::query()->where('status','=','1')->whereIn('business_account_id', getDependentUserIds());
        $lease_id = $leases->get()->pluck('id')->toArray();

        //Tengible Properties -Land
        $total_land = LeaseAssets::query()->whereHas('category', function ($query) {
               $query->where('id',1);
             })->whereIn('lease_id', $lease_id)->count();
       

        $lease_asset_categories = LeaseAssetCategories::query()->where('is_capitalized', '=', '1')->get();
        $category_id = $lease_asset_categories->pluck('id')->toArray();
        
        
        $own_assets_capitalized = LeaseAssets::query()->whereIn('lease_id', $lease_id)
        ->where('specific_use',1)
        ->where('category_id',$category_id)
        ->whereHas('leaseSelectLowValue',  function($query){
          $query->where('is_classify_under_low_value', '=', 'no');
        })->whereHas('leaseDurationClassified',  function($query){
          $query->where('lease_contract_duration_id', '=', '3');
        })->count();

        $sublease_assets_capitalized = LeaseAssets::query()->where('lease_id', $lease_id)
         ->where('specific_use',2)
         ->where('category_id',$category_id)
         ->whereHas('leaseSelectLowValue',  function($query){
          $query->where('is_classify_under_low_value', '=', 'no');
        })->whereHas('leaseDurationClassified',  function($query){
          $query->where('lease_contract_duration_id', '=', '3');
        })->count();

         //Tangible Properties - Other than Land
        $total_other_land = LeaseAssets::query()->whereHas('category', function ($query) {
               $query->where('id',2);
             })->whereIn('lease_id', $lease_id)->count();

        //Plant & Equipments
        $total_plant = LeaseAssets::query()->whereHas('category', function ($query) {
               $query->where('id',3);
             })->whereIn('lease_id', $lease_id)->count();

         //Investment Properties
        $total_investment = LeaseAssets::query()->whereHas('category', function ($query) {
               $query->where('id',6);
             })->whereIn('lease_id', $lease_id)->count();

        //Short Term Lease
        $total_short_term_lease = LeaseAssets::query()->whereIn('lease_id', $lease_id)
        ->whereHas('leaseDurationClassified',  function($query){
          $query->where('lease_contract_duration_id', '=', '2');
        })->count();

        //Low Value lease assets
        $total_low_value_asset = LeaseAssets::query()->whereIn('lease_id', $lease_id)
        ->whereHas('leaseSelectLowValue',  function($query){
          $query->where('is_classify_under_low_value', '=', 'yes');
        })->count();
        
        //other lease asset
        $total_other_lease_asset = LeaseAssets::query()->whereIn('lease_id', $lease_id)
        ->whereHas('leaseSelectLowValue',  function($query){
          $query->where('is_classify_under_low_value', '=', 'no');
        })->count();

        //undiscounted lease assets
        $total_undiscounted_capitalized = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id',[5,8])->count();
               // dd( $total_undiscounted_capitalized);
        return view('home',compact('own_assets_capitalized','sublease_assets_capitalized','total_active_lease_asset','total_land','total_short_term_lease','total_low_value_asset','total_other_lease_asset','total_other_land','total_plant','total_investment'));
    }

    public function fetchDetails(Request $request)
    {
        try {
            if ($request->ajax()) {

                $leases = Lease::query()->where('status','=','1')->whereIn('business_account_id', getDependentUserIds());

                $lease_id = $leases->get()->pluck('id')->toArray();

                $data = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->with('leaseDurationClassified')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->whereNotIn('category_id',[5,8])->get()->toArray();
                
                $total_undiscounted_value = 0;
                $total_present_value_lease_asset = 0;

                foreach ($data as $key => $asset) {
                    $total_undiscounted_value+= $asset['lease_select_low_value']['undiscounted_lease_payment'];
                    $total_present_value_lease_asset+= $asset['value_of_lease_asset'];

                }
                //Tengible Properties in Land
                 $data = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->with('leaseDurationClassified')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->where('category_id',1)->get()->toArray();
                
                $total_tengible_undiscounted_value = 0;
                $total_tengible_present_value_lease_asset = 0;

                foreach ($data as $key => $asset) {

                    $total_tengible_undiscounted_value+= $asset['lease_select_low_value']['undiscounted_lease_payment'];
                    $total_tengible_present_value_lease_asset+= $asset['value_of_lease_asset'];

                }

                 //Tangible Properties - Other than Land
                 $data = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->with('leaseDurationClassified')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->where('category_id',2)->get()->toArray();
                
                $total_tengible_other_undiscounted_value = 0;
                $total_tengible_other_present_value_lease_asset = 0;

                foreach ($data as $key => $asset) {

                    $total_tengible_other_undiscounted_value+= $asset['lease_select_low_value']['undiscounted_lease_payment'];
                    $total_tengible_other_present_value_lease_asset+= $asset['value_of_lease_asset'];

                }


                 //Plants & Equipments
                 $data = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->with('leaseDurationClassified')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->where('category_id',3)->get()->toArray();
                
                $total_plants_undiscounted = 0;
                $total_plants_present_value = 0;

                foreach ($data as $key => $asset) {

                    $total_plants_undiscounted+= $asset['lease_select_low_value']['undiscounted_lease_payment'];
                    $total_plants_present_value+= $asset['value_of_lease_asset'];

                }
                //Investment & Properties
                 $data = LeaseAssets::query()->whereIn('lease_id', $lease_id)
                ->where('specific_use',1)
                ->with('leaseDurationClassified')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue',  function($query){
                  $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified',  function($query){
                  $query->where('lease_contract_duration_id', '=', '3');
                })->where('category_id',6)->get()->toArray();
                
                $total_invest_undiscounted = 0;
                $total_invest_present_value = 0;

                foreach ($data as $key => $asset) {

                    $total_invest_undiscounted+= $asset['lease_select_low_value']['undiscounted_lease_payment'];
                    $total_invest_present_value+= $asset['value_of_lease_asset'];

                }
              
                return response()->json([ 'status' => 1,'total_undiscounted_value' => $total_undiscounted_value,'total_present_value_lease_asset' => $total_present_value_lease_asset,'total_tengible_undiscounted_value' => $total_tengible_undiscounted_value, 'total_tengible_present_value_lease_asset' => $total_tengible_present_value_lease_asset,'total_tengible_other_undiscounted_value' => $total_tengible_other_undiscounted_value, 'total_tengible_other_present_value_lease_asset' => $total_tengible_other_present_value_lease_asset,'total_plants_undiscounted' => $total_plants_undiscounted, 'total_plants_present_value' => $total_plants_present_value,'total_invest_undiscounted'=>$total_invest_undiscounted,'total_invest_present_value' => $total_invest_present_value]); } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }
}

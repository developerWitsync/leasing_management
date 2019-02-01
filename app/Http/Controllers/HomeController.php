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
        
        $leases = Lease::query()->whereIn('business_account_id', getDependentUserIds());
        $lease_id = $leases->get()->pluck('id')->toArray();

        $total_land = LeaseAssets::query()->whereHas('subcategory', function ($query) {
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

        return view('home',compact('own_assets_capitalized','sublease_assets_capitalized','total_active_lease_asset','total_land'));
    }
}

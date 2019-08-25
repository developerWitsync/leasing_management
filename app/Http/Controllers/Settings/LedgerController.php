<?php
/**
 * Created by PhpStorm.
 * User: Himanshu Rajput
 * Date: 8/25/2019
 * Time: 4:25 PM
 */

namespace App\Http\Controllers\Settings;

use App\Ledgers;
use DB;
use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\LeaseAssetCategories;
use App\LeaseAssetSubCategorySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LedgerController extends Controller
{

  /**
   * renders the view for the form to get the Ledgers..
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Request $request)
  {
    try{
      $category_id = $request->get('category_id');

      $general_settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
      if($general_settings->ledger_level == 1){
        $category = LeaseAssetCategories::query()->find($request->category_id);
      } elseif($general_settings->ledger_level == 2){
        $category = LeaseAssetSubCategorySetting::query()->whereIn('business_account_id', getDependentUserIds())
          ->where('id', '=', $request->category_id)->first();
      }

      $ledgers = Ledgers::query()->whereIn('business_account_id', getDependentUserIds())
        ->where('category_id', '=', $category->id)
        ->where('ledger_level','=', $general_settings->ledger_level)
        ->get();
      $ledgers_data = [];
      if(count($ledgers) > 0) {
        foreach ($ledgers as $ledger){
          $ledgers_data[$ledger->type]['account_name'] = $ledger->account_name;
          $ledgers_data[$ledger->type]['account_code'] = $ledger->account_code;
        }
      }

      return view('settings.ledger.index', compact(
        'category_id',
        'ledgers_data'
      ));
    } catch (\Exception $e){
      abort(404);
    }
  }

  /**
   * saves or update the ledgers data for the logged in user....
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function save(Request $request)
  {
    try{
      $general_settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
      if($general_settings->ledger_level == 1){
        $category = LeaseAssetCategories::query()->find($request->category_id);
      } elseif($general_settings->ledger_level == 2){
        $category = LeaseAssetSubCategorySetting::query()->whereIn('business_account_id', getDependentUserIds())
          ->where('id', '=', $request->category_id)->first();
      }

      $data = [];
      foreach ($request->ledger as $key=>$item){
        $internal = [];
        $internal['business_account_id'] = getParentDetails()->id;
        $internal['ledger_level'] = $general_settings->ledger_level;
        $internal['category_id'] =  $category->id;
        $internal['type'] = $key;
        $internal['account_name'] = $item['account_name'];
        $internal['account_code'] = $item['account_code'];
        $internal['created_at'] = date('Y-m-d H:i:s');
        $internal['updated_at'] = date('Y-m-d H:i:s');
        $data[] = $internal;
      }
      DB::transaction(function () use ($data, $general_settings, $category) {
        DB::table('ledgers')->whereIn('business_account_id', getDependentUserIds())
          ->where('ledger_level', '=',$general_settings->ledger_level)
          ->where('category_id', '=', $category->id)
          ->delete();
        DB::table('ledgers')->insert($data);
      });

      Session::flash('status', 'Ledgers data has been saved successfully.');
      return response()->json([
        'status' => true
      ], 200);

    } catch (\Exception $e){
      return response()->json([
        'status' => false
      ], 200);
    }
  }
}
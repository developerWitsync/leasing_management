<?php

namespace App\Http\Controllers;

use App\ExchangeRates;
use App\ForeignCurrencyTransactionSettings;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Lease;
use App\LeaseAssets;
use App\LeaseAssetCategories;
use Validator;

class HomeController extends Controller
{
  public $breadcrumbs;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->breadcrumbs = [
      [
        'link' => route('home'),
        'title' => 'Dashboard'
      ]
    ];
    $this->middleware('auth');
  }

  /**
   * returns the exchange rate from the exchange rates saved by the user under settings...
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getExchangeRate(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'base' => 'required',
        'target' => 'required',
        'date' => 'required|date'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status' => false,
          'message' => $validator->errors()
        ], 200);
      }

      if ($request->base == $request->target) {
        return response()->json([
          'status' => true,
          'rate' => 1
        ], 200);
      } else {
        $model = ForeignCurrencyTransactionSettings::query()
          ->whereIn('business_account_id', getDependentUserIds())
          ->where('base_currency', '=', $request->base)
          ->where('foreign_exchange_currency', '=', $request->target)
          ->first();

        if ($model) {
          $exchangeRates = ExchangeRates::query()->where('foreign_currency_id', '=', $model->id)
            ->where('date', '=', Carbon::parse($request->date)->format('Y-m-d'))
            ->first();
          if ($exchangeRates) {
            return response()->json([
              'status' => true,
              'rate' => $exchangeRates->rate
            ], 200);
          }
        } else {
          $model = ForeignCurrencyTransactionSettings::query()
            ->whereIn('business_account_id', getDependentUserIds())
            ->where('base_currency', '=', $request->target)
            ->where('foreign_exchange_currency', '=', $request->base)
            ->first();

          $exchangeRates = ExchangeRates::query()->where('foreign_currency_id', '=', $model->id)
            ->where('date', '=', Carbon::parse($request->date)->format('Y-m-d'))
            ->first();
          if ($exchangeRates) {
            return response()->json([
              'status' => true,
              'rate' => round(1/(float)$exchangeRates->rate, 2) //dividing by 1 as we have now changed the exchange rate
            ], 200);
          }
        }
        return response()->json([
          'status' => true,
          'rate' => 1
        ], 200);

      }
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => $e->getMessage()
      ], 200);
    }
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $breadcrumbs = $this->breadcrumbs;
    $total_active_lease_asset = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1')->with('assets')->count();

    $leases = Lease::query()->where('status', '=', '1')->whereIn('business_account_id', getDependentUserIds());
    $lease_id = $leases->get()->pluck('id')->toArray();

    //Tengible Properties -Land
    $total_land = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 1);
    })->whereIn('lease_id', $lease_id)->count();

    $lease_asset_categories = LeaseAssetCategories::query()->where('is_capitalized', '=', '1')->get();
    $category_id = $lease_asset_categories->pluck('id')->toArray();

    $own_assets_capitalized = LeaseAssets::query()->whereIn('lease_id', $lease_id)
      ->where('specific_use', '=', 1)
      ->whereIn('category_id', $category_id)
      ->whereHas('leaseSelectLowValue', function ($query) {
        $query->where('is_classify_under_low_value', '=', 'no');
      })->whereHas('leaseDurationClassified', function ($query) {
        $query->where('lease_contract_duration_id', '=', '3');
      })->count();


    $sublease_assets_capitalized = LeaseAssets::query()->whereIn('lease_id', $lease_id)
      ->where('specific_use', '=', 2)
      ->whereIn('category_id', $category_id)
      ->whereHas('leaseSelectLowValue', function ($query) {
        $query->where('is_classify_under_low_value', '=', 'no');
      })->whereHas('leaseDurationClassified', function ($query) {
        $query->where('lease_contract_duration_id', '=', '3');
      })->count();

    //Tangible Properties - Other than Land
    $total_other_land = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 2);
    })->whereIn('lease_id', $lease_id)->count();

    //Plant & Equipments
    $total_plant = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 3);
    })->whereIn('lease_id', $lease_id)->count();

    //Investment Properties
    $total_investment = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 6);
    })->whereIn('lease_id', $lease_id)->count();

    //Intangible Assets
    $total_intangible = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 7);
    })->whereIn('lease_id', $lease_id)->count();

    //Agricultural Assets
    $total_agricultural = LeaseAssets::query()->whereHas('category', function ($query) {
      $query->where('id', 4);
    })->whereIn('lease_id', $lease_id)->count();

    //Short Term Lease
    $total_short_term_lease = LeaseAssets::query()->whereIn('lease_id', $lease_id)
      ->whereHas('leaseDurationClassified', function ($query) {
        $query->where('lease_contract_duration_id', '=', '2');
      })->count();

    //Low Value lease assets
    $total_low_value_asset = LeaseAssets::query()->whereIn('lease_id', $lease_id)
      ->whereHas('leaseSelectLowValue', function ($query) {
        $query->where('is_classify_under_low_value', '=', 'yes');
      })->count();

    $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
      ->whereIn('business_account_id', getDependentUserIds())
      ->where('status', '=', '0')
      ->get();

    $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

    //other lease asset combining together intangible and biological
    $total_other_lease_asset = LeaseAssets::query()->whereHas('category', function ($query) use ($category_excluded_id) {
      $query->whereIn('category_id', $category_excluded_id);
    })->whereIn('lease_id', $lease_id)->count();

    //undiscounted lease assets
    $total_undiscounted_capitalized = LeaseAssets::query()->whereIn('lease_id', $lease_id)
      ->where('specific_use', 1)
      ->whereHas('leaseSelectLowValue', function ($query) {
        $query->where('is_classify_under_low_value', '=', 'no');
      })->whereHas('leaseDurationClassified', function ($query) {
        $query->where('lease_contract_duration_id', '=', '3');
      })->whereNotIn('category_id', [5, 8])->count();
    return view('home', compact(
      'own_assets_capitalized',
      'sublease_assets_capitalized',
      'total_active_lease_asset',
      'total_land',
      'total_short_term_lease',
      'total_low_value_asset',
      'total_other_lease_asset',
      'total_other_land',
      'total_plant',
      'total_investment',
      'total_undiscounted_capitalized',
      'total_intangible',
      'total_agricultural',
      'breadcrumbs'
    ));
  }

  /**
   * Show the consolidated chart for the lease assets...
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   */
  public function consolidatedChart(Request $request)
  {
    try {
      if ($request->ajax()) {

        $ids = implode(',', getDependentUserIds());

        //and `json_data_steps`->'$."underlying_asset"."specific_use"' = 1
        $sql = "SELECT 
                            SUM(`json_data_steps`->>'$.\"underlying_asset\".\"lease_liablity_value\"') AS `value_of_lease_asset`,
                            SUM(`json_data_steps`->>'$.\"underlying_asset\".\"undiscounted_value\"') AS `undiscounted_lease_payment`
                        FROM
                            lease_history
                                JOIN
                            lease ON lease.id = lease_history.lease_id
                                JOIN
                            lease_assets ON (lease_assets.lease_id = lease.id)
                                JOIN
                            lease_assets_categories ON (lease_assets.category_id = lease_assets_categories.id)
                        WHERE
                            lease.business_account_id IN ($ids)
                            and lease_history.modify_id is NULL
                            and lease_assets.category_id NOT IN(5,8)
                            and lease.status = '1'
                            and `json_data_steps`->'$.\"duration_classified\".\"lease_contract_duration_id\"' = 3";

        $total_undiscounted_value = 0;

        $total_present_value_lease_asset = 0;

        $data = DB::select($sql);

        foreach ($data as $key => $total) {
          $total_undiscounted_value += $total->undiscounted_lease_payment;
          $total_present_value_lease_asset += $total->value_of_lease_asset;
        }

        return response()->json(['status' => 1, 'total_undiscounted_value' => $total_undiscounted_value, 'total_present_value_lease_asset' => $total_present_value_lease_asset]);
      } else {
        return redirect()->back();
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * fetches the data for the categorised lease assets
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function categorisedChart(Request $request)
  {
    try {
      if ($request->ajax()) {

        $ids = implode(',', getDependentUserIds());

        //and `json_data_steps`->'$."underlying_asset"."specific_use"' = 1
        $sql = "SELECT 
                            SUM(`json_data_steps`->>'$.\"underlying_asset\".\"lease_liablity_value\"') AS `value_of_lease_asset`,
                            SUM(`json_data_steps`->>'$.\"underlying_asset\".\"undiscounted_value\"') AS `undiscounted_lease_payment`,
                            lease_assets.category_id,
                            lease_assets_categories.title
                        FROM
                            lease_history
                                JOIN
                            lease ON lease.id = lease_history.lease_id
                                JOIN
                            lease_assets ON (lease_assets.lease_id = lease.id)
                            JOIN
                            lease_assets_categories ON (lease_assets.category_id = lease_assets_categories.id)
                        WHERE
                            lease.business_account_id IN ($ids)
                            and lease_history.modify_id is NULL
                            and lease_assets.category_id NOT IN(5,8)
                            and lease.status = '1'
                            and `json_data_steps`->'$.\"duration_classified\".\"lease_contract_duration_id\"' = 3
                        GROUP BY lease_assets.category_id";

        $data = DB::select($sql);
        $resultData = [];
        foreach ($data as $category) {
          $undiscounted_array = [];
          $undiscounted_array["label"] = $category->title;
          $undiscounted_array["value"] = $category->undiscounted_lease_payment;

          $value_of_lease_asset_array = [];
          $value_of_lease_asset_array["label"] = $category->title;
          $value_of_lease_asset_array["value"] = $category->value_of_lease_asset;

          $resultData["undiscounted_value"][$category->category_id] = $undiscounted_array;
          $resultData["present_value"][$category->category_id] = $value_of_lease_asset_array;
        }

        if (empty($resultData)) {
          return response()->json(['status' => false], 200);
        } else {
          return response()->json(['status' => true, 'data' => $resultData], 200);
        }

      } else {
        abort(404);
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

}

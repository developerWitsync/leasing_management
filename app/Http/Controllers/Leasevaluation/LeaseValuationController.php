<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 31/01/19
 * Time: 11:37 AM
 */

namespace App\Http\Controllers\Leasevaluation;

use App\Http\Controllers\Controller;
use App\LeaseHistory;
use App\ReportingCurrencySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lease;
use App\ModifyLeaseApplication;
use App\LeaseAssets;
use App\LeaseAssetCategories;
use Validator;


class LeaseValuationController extends Controller
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Dashboard'
            ],
            [
                'link' => route('leasevaluation.index'),
                'title' => 'Lease Valuation'
            ]
        ];
    }

    /**
     * fetch all the lease assets as per the input category id and for the input capitalized or non-capitalized
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $breadcrumbs = $this->breadcrumbs;

            if ($request->has('capitalized')) {
                $capitalized = $request->capitalized;
            } else {
                $capitalized = '1'; //set capitalized = 1
            }

            $categories = LeaseAssetCategories::query()->where('is_capitalized', '=', $capitalized)->get();

            return view('leasevaluation.index', compact(
                 'capitalized',
                'categories',
                'breadcrumbs'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetch the lease assets for the lease valuation
     * @param Request $request
     * @param null $category_id
     * @param int $capitalized
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fetchAssets(Request $request, $category_id = null, $capitalized = 1)
    {
        try {
            if ($request->ajax()) {
                $leases = Lease::query()
                    ->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1');

                //fetch the data that needs to be listed on the Lease Valuation
                $assets = LeaseAssets::query()
                    ->select('*')
                    ->selectRaw('IF(current_date() > lease_end_date, datediff(current_date(), lease_end_date), datediff(lease_end_date, current_date())) as remaining_term')
                    ->whereIn('lease_id', $leases->get()->pluck('id')->toArray())
                    ->with('lease')
                    ->with('category')
                    ->with('specificUse')
                    ->with('country')
                    ->with('leaseSelectDiscountRate')
                    ->with('leaseSelectLowValue');

                if ($category_id && $category_id!="all") {
                    $assets = $assets->where('category_id', '=', $category_id);
                }

                $assets = $assets->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->whereHas('category', function ($query) use ($capitalized) {
                    $query->where('is_capitalized', '=', $capitalized);
                });

                $reporting_currency = '';
                $is_foreign_currency_applied = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                if($is_foreign_currency_applied) {
                    $reporting_currency = $is_foreign_currency_applied->internal_company_financial_reporting_currency;
                    $is_foreign_currency_applied = $is_foreign_currency_applied->is_foreign_transaction_involved;
                } else {
                    $is_foreign_currency_applied = 'no';
                }



                return datatables()->eloquent($assets)

                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('is_foreign_transaction_involved', function() use ($is_foreign_currency_applied){
                        return $is_foreign_currency_applied;
                    })
                    ->addColumn('foreign_initial_lease_currency', function($data) use ($is_foreign_currency_applied){
                        if($is_foreign_currency_applied == "yes"){
                            $initial_currency = LeaseHistory::query()
                                ->select('json_data_steps->lessor_details->lease_contract_id as initial_lease_currency')
                                ->where('lease_id', '=', $data->lease->id)
                                ->whereRaw('modify_id IS NULL')
                                ->first();
                            return str_replace('"', '', $initial_currency->initial_lease_currency);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->addColumn('foreign_initial_undiscounted_lease_liability', function($data) use ($is_foreign_currency_applied){
                        if($is_foreign_currency_applied == "yes"){
                            $initial_undiscounted_lease_liability = LeaseHistory::query()
                                ->select('json_data_steps->low_value->undiscounted_lease_payment as initial_undiscounted_lease_liability')
                                ->where('lease_id', '=', $data->lease->id)
                                ->whereRaw('modify_id IS NULL')
                                ->first();
                            return str_replace('"', '', $initial_undiscounted_lease_liability->initial_undiscounted_lease_liability);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->addColumn('foreign_initial_present_value_of_lease_liability', function($data) use ($is_foreign_currency_applied){
                        if($is_foreign_currency_applied == "yes"){
                            $initial_present_value_of_lease_liability = LeaseHistory::query()
                                ->select('json_data_steps->underlying_asset->lease_liablity_value as initial_present_value_of_lease_liability')
                                ->where('lease_id', '=', $data->lease->id)
                                ->whereRaw('modify_id IS NULL')
                                ->first();
                            return str_replace('"', '', $initial_present_value_of_lease_liability->initial_present_value_of_lease_liability);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->addColumn('foreign_initial_value_of_lease_asset', function($data) use ($is_foreign_currency_applied){
                        if($is_foreign_currency_applied == "yes"){
                            $initial_value_of_lease_asset = LeaseHistory::query()
                                ->select('json_data_steps->underlying_asset->value_of_lease_asset as initial_value_of_lease_asset')
                                ->where('lease_id', '=', $data->lease->id)
                                ->whereRaw('modify_id IS NULL')
                                ->first();
                            return str_replace('"', '', $initial_value_of_lease_asset->initial_value_of_lease_asset);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->addColumn('start_date', function ($data) {
                        if (Carbon::parse($data->accural_period)->lessThanOrEqualTo(Carbon::create(2019, 1, 1))) {
                            return Carbon::create(2019, 1, 1)->format(config('settings.date_format'));
                        } else {
                            return Carbon::parse($data->accural_period)->format(config('settings.date_format'));
                        }
                    })
                    ->addColumn('initial_lease_currency', function($data){
                        $initial_currency = LeaseHistory::query()
                            ->select('json_data_steps->lessor_details->lease_contract_id as initial_lease_currency')
                            ->where('lease_id', '=', $data->lease->id)
                            ->whereRaw('modify_id IS NULL')
                            ->first();
                        return str_replace('"', '', $initial_currency->initial_lease_currency);
                    })
                    ->addColumn('initial_undiscounted_lease_liability', function($data){
                        $initial_undiscounted_lease_liability = LeaseHistory::query()
                            ->select('json_data_steps->low_value->undiscounted_lease_payment as initial_undiscounted_lease_liability')
                            ->where('lease_id', '=', $data->lease->id)
                            ->whereRaw('modify_id IS NULL')
                            ->first();
                        return str_replace('"', '', $initial_undiscounted_lease_liability->initial_undiscounted_lease_liability);
                    })
                    ->addColumn('initial_present_value_of_lease_liability', function($data){
                        $initial_present_value_of_lease_liability = LeaseHistory::query()
                            ->select('json_data_steps->underlying_asset->lease_liablity_value as initial_present_value_of_lease_liability')
                            ->where('lease_id', '=', $data->lease->id)
                            ->whereRaw('modify_id IS NULL')
                            ->first();
                        return str_replace('"', '', $initial_present_value_of_lease_liability->initial_present_value_of_lease_liability);
                    })
                    ->addColumn('initial_value_of_lease_asset', function($data){
                        $initial_value_of_lease_asset = LeaseHistory::query()
                            ->select('json_data_steps->underlying_asset->value_of_lease_asset as initial_value_of_lease_asset')
                            ->where('lease_id', '=', $data->lease->id)
                            ->whereRaw('modify_id IS NULL')
                            ->first();
                        return str_replace('"', '', $initial_value_of_lease_asset->initial_value_of_lease_asset);
                    })
                    ->addColumn('has_subsequent_modifications', function($data){
                        $subsequent_modifications_count = ModifyLeaseApplication::query()
                            ->where('lease_id', '=', $data->lease->id)
                            ->where('valuation', '=', 'Subsequent Valuation')
                            ->count();
                        return ($subsequent_modifications_count > 0);
                    })
                    ->addColumn('exchange_rate', function($data) use ($is_foreign_currency_applied, $reporting_currency){
                        if($is_foreign_currency_applied == "yes") {
                            if(Carbon::parse($data->accural_period)->greaterThan(Carbon::create(2019,1,1))){
                                $date = Carbon::parse($data->accural_period)->format('Y-m-d');
                            } else {
                                $date = '2019-01-01';
                            }
                            $initial_currency = LeaseHistory::query()
                                ->select('json_data_steps->lessor_details->lease_contract_id as initial_currency')
                                ->where('lease_id', '=', $data->lease->id)
                                ->whereRaw('modify_id IS NULL')
                                ->first();

                            $source = str_replace('"', '', $initial_currency->initial_currency);
                            return fetchCurrencyExchangeRate($date, $source, $reporting_currency);
                        } else {
                            return 1;
                        }
                    })
                    ->addColumn('subsequent_modification_effective_date', function($data){
                        $subsequent_modification = ModifyLeaseApplication::query()
                            ->where('lease_id', '=', $data->lease->id)
                            ->where('valuation', '=', 'Subsequent Valuation')
                            ->orderBy('created_at', 'desc')->first();
                        if($subsequent_modification) {
                            return $subsequent_modification->effective_from;
                        } else {
                            return "N/A";
                        }

                    })
                    ->addColumn('subsequent_modification_exchange_rate', function($data) use ($reporting_currency){

                        $subsequent_modification = ModifyLeaseApplication::query()
                            ->where('lease_id', '=', $data->lease->id)
                            ->where('valuation', '=', 'Subsequent Valuation')
                            ->orderBy('created_at', 'desc')->first();
                        if($subsequent_modification) {
                            $date = Carbon::parse($subsequent_modification->effective_from)->format('Y-m-d');
                            $source = $source = $data->lease->lease_contract_id;
                            return fetchCurrencyExchangeRate($date, $source, $reporting_currency);
                        } else {
                            return "N/A";
                        }

                    })
                    ->toJson();

            } else {
                return redirect(route('leasevaluation.index'));
            }
        } catch (\Exception $exception) {
            abort(404);
        }
    }
}

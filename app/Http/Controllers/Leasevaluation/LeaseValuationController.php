<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 31/01/19
 * Time: 11:37 AM
 */

namespace App\Http\Controllers\Leasevaluation;

use App\CategoriesLeaseAssetExcluded;
use App\DiscountRateChartView;
use App\Exports\InterestAndDepreciationExport;
use App\Http\Controllers\Controller;
use App\InterestAndDepreciation;
use App\LeaseAssetPayments;
use App\LeaseHistory;
use App\PaymentEscalationDetails;
use App\ReportingCurrencySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lease;
use App\LeaseAssets;
use App\LeaseAssetCategories;
use Validator;
use Maatwebsite\Excel\Facades\Excel;


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
                'link' => route('leasevaluation.cap.index'),
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

            $excluded_categories = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->pluck('category_id')->toArray();

            if ($capitalized == '1') {

                $categories = LeaseAssetCategories::query()
                    ->whereNotIn('id', $excluded_categories)
                    ->get();

            } else {

                $categories = LeaseAssetCategories::query()
                    ->whereIn('id', $excluded_categories)
                    ->get();
            }

            return view('leasevaluation.index', compact(
                'capitalized',
                'categories',
                'breadcrumbs',
                'included_categories'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * show the card pages for all the categories and list down the assets for the capitalised and non capitalised
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function capitalised(Request $request)
    {
        try {

            $excluded_categories = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->pluck('category_id')->toArray();

            if ($request->segment('2') == 'valuation-non-capitalised') {
                $categories = LeaseAssetCategories::query()->select('title', 'id')
                    ->whereIn('id', $excluded_categories)
                    ->get();

            } else {
                $categories = LeaseAssetCategories::query()->select('title', 'id')
                    ->whereNotIn('id', $excluded_categories)
                    ->get();
            }

            return view('leasevaluation.capitalised', compact(
                'categories'
            ));

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * capitalised lease assets listing on the Lease Valuation CAP page.
     * @param $category_id
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function fetchCategoryAsset($category_id, Request $request)
    {
        try {

            $category = LeaseAssetCategories::query()->findOrFail($category_id);

            $leases = Lease::query()
                ->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1');

            //fetch the data that needs to be listed on the Lease Valuation
            $assets = LeaseAssets::query()
                ->select('*')
                ->selectRaw('IF(current_date() > lease_end_date, datediff(current_date(), lease_end_date), datediff(lease_end_date, current_date())) as remaining_term')
                ->whereIn('lease_id', $leases->get()->pluck('id')->toArray())
                ->with('lease')
                ->with('category')
                ->with('leaseSelectLowValue');

            $capitalized = false;
            if ($request->segment('2') == "valuation-capitalised") {
                $assets = $assets->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                });
                $capitalized = true;
            }

            if ($category_id) {
                $assets = $assets->where('category_id', '=', $category_id);
            }

            $assets = $assets->paginate(4);

            return view('leasevaluation.partials._category_card', compact(
                'assets',
                'category',
                'capitalized'
            ))->render();

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetches and renders the short term lease on the overview tab for the non-capitalised only..
     * @return string
     * @throws \Throwable
     */
    public function fetchShortTermLeaseAssets()
    {
        try {
            $leases = Lease::query()
                ->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1');

            $category_excluded = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->get();

            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

            //fetch the data that needs to be listed on the Lease Valuation
            $assets = LeaseAssets::query()
                ->select('*')
                ->selectRaw('IF(current_date() > lease_end_date, datediff(current_date(), lease_end_date), datediff(lease_end_date, current_date())) as remaining_term')
                ->whereIn('lease_id', $leases->get()->pluck('id')->toArray())
                ->with('lease')
                ->with('category')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseDurationClassified', function ($query) {
                    $query->whereIn('lease_contract_duration_id', [1, 2]);
                })->whereNotIn('category_id', $category_excluded_id);

            $assets = $assets->paginate(4);

            $capitalized = false;

            $title = "Short Term Lease Assets";
            $title_id = "short_term";

            return view('leasevaluation.partials.short_term_asset_card', compact(
                'assets',
                'capitalized',
                'title',
                'title_id'
            ))->render();


        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetch low value lease assets for the non-capitalised lease assets
     * @return string
     * @throws \Throwable
     */
    public function fetchLowValueLeaseAssets()
    {
        try {
            $leases = Lease::query()
                ->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1');

            $category_excluded = CategoriesLeaseAssetExcluded::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '0')
                ->get();

            $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

            //fetch the data that needs to be listed on the Lease Valuation
            $assets = LeaseAssets::query()
                ->select('*')
                ->selectRaw('IF(current_date() > lease_end_date, datediff(current_date(), lease_end_date), datediff(lease_end_date, current_date())) as remaining_term')
                ->whereIn('lease_id', $leases->get()->pluck('id')->toArray())
                ->with('lease')
                ->with('category')
                ->with('leaseSelectLowValue')
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'yes');
                })->whereNotIn('category_id', $category_excluded_id);

            $assets = $assets->paginate(4);

            $capitalized = false;

            $title = "Low Value Lease Assets";
            $title_id = "low_value";
            return view('leasevaluation.partials.short_term_asset_card', compact(
                'assets',
                'capitalized',
                'title',
                'title_id'
            ))->render();


        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * Renders the lease valuation overview Tab for the lease
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leaseOverview($id, Request $request)
    {
        try {
            $lease = Lease::query()
                ->where('id', '=', $id)
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '1')->firstOrFail();
            $asset = $lease->assets()->first(); //there will be only one lease asset for a lease...
            $subsequent_modified = $lease->isSubsequentModification();
            $subsequent = null;
            if ($subsequent_modified) {
                $subsequent = $lease->modifyLeaseApplication->last();
            }

            return view('leasevaluation.overview', compact(
                'lease',
                'asset',
                'subsequent_modified',
                'subsequent'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * renders the valuation tab UI for the lease
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leaseValuation($id)
    {
        try {
            $lease = Lease::query()
                ->where('id', '=', $id)
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '1')->firstOrFail();
            $asset = $lease->assets()->first(); //there will be only one lease asset for a lease...
            $subsequent_modified = $lease->isSubsequentModification();
            $subsequent = null;
            if ($subsequent_modified) {
                $subsequent = $lease->modifyLeaseApplication->last();
            }
            $show_statutory_columns = false;
            $statutory_currency = $lease->lease_contract_id;
            $currecy_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
            if ($currecy_settings && ($currecy_settings->statutory_financial_reporting_currency != $lease->lease_contract_id)) {
                $show_statutory_columns = true;
                $statutory_currency = $currecy_settings->statutory_financial_reporting_currency;
            }
            return view('leasevaluation.valuation', compact(
                'lease',
                'asset',
                'subsequent_modified',
                'subsequent',
                'show_statutory_columns',
                'statutory_currency'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * fetch the complete lease valuation list for the valuation tab...
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchCompletedLeaseValuation($id)
    {
        try {

            $base_date = getParentDetails()->accountingStandard->base_date;

            $lease = Lease::query()
                ->where('id', '=', $id)
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '1')->firstOrFail();

            $asset = $lease->assets()->first(); //there will be only one lease asset for a lease...

            $lease_history = LeaseHistory::query()
                ->selectRaw('json_data_steps->>"$.lessor_details.lease_contract_id" as source_currency')
                ->selectRaw('lease_history.id as history_id')
                ->selectRaw('lease_history.modify_id as modify_id')
                ->selectRaw("IF(ISNULL(lease_history.modify_id),'Initial Valuation',modify_lease_application.valuation) AS valuation_type")
                ->selectRaw('json_data_steps->>"$.discount_rate.daily_discount_rate" as daily_discount_rate')
                ->selectRaw("IF(ISNULL(lease_history.modify_id),json_data_steps->>\"$.underlying_asset.accural_period\",modify_lease_application.effective_from) as effective_date")
                ->selectRaw('json_data_steps->>"$.low_value.undiscounted_lease_payment" as undiscounted_value')
                ->selectRaw('json_data_steps->>"$.underlying_asset.lease_liablity_value" as present_value')
                ->selectRaw('json_data_steps->>"$.underlying_asset.value_of_lease_asset" as value_of_lease_asset')
                ->selectRaw('json_data_steps->>"$.fair_market.total_units" as fair_market_value')
                ->selectRaw('json_data_steps->>"$.underlying_asset.impairment_test_value" as impairment_value')
                ->join('lease', 'lease.id', '=', 'lease_history.lease_id')
                ->leftJoin('modify_lease_application', 'lease_history.modify_id', '=', 'modify_lease_application.id')
                ->with('leaseModification')
                ->where('lease_history.lease_id', '=', $id);

            $datatable = datatables()->eloquent($lease_history);

            $currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

            $datatable =  $datatable->addColumn('undiscounted_value', function($data){
                return number_format($data->undiscounted_value, 2);
            })->addColumn('present_value', function($data){
                return number_format($data->present_value, 2);
            })->addColumn('value_of_lease_asset', function($data){
                return number_format($data->value_of_lease_asset, 2);
            })->addColumn('daily_discount_rate', function($data){
                return number_format($data->daily_discount_rate, 2);
            })->addColumn('effective_date', function($data) use ($base_date){
                if(Carbon::parse($data->effective_date)->lessThan($base_date)){
                    return Carbon::parse($base_date)->format('Y-m-d');
                } else {
                    return $data->effective_date;
                }
            });

            if ($currency_settings && ($currency_settings->statutory_financial_reporting_currency != $lease->lease_contract_id)) {
                //add columns for the statutory currency here as well....
                $destination = $currency_settings->statutory_financial_reporting_currency;

                $datatable->addColumn('exchange_rate', function ($data) use ($destination, $base_date, $asset) {
                    $source = $data->source_currency;
                    if (Carbon::parse($asset->accural_period)->greaterThan(Carbon::parse($base_date))) {
                        $date = Carbon::parse($asset->accural_period)->format('Y-m-d');
                    } else {
                        $date = $base_date;
                    }
                    //check for the subsequent and fetch the exchange rate for the effective date in that case...
                    if ($data->valuation_type == "Subsequent Valuation") {
                        $date = $data->effective_date;
                    }

                    $exchange_rate = fetchCurrencyExchangeRate($date, $source, $destination);
                    return $exchange_rate;
                })

                    ->addColumn('statutory_undiscounted_value', function ($data) {
                        return $data->undiscounted_value;
                    })
                    ->addColumn('statutory_present_value', function ($data) {
                        return $data->present_value;
                    })
                    ->addColumn('statutory_value_of_lease_asset', function ($data) {
                        return $data->value_of_lease_asset;
                    })
                    ->addColumn('statutory_fair_market_value', function ($data) {
                        if ($data->fair_market_value != "null") {
                            return $data->fair_market_value;
                        } else {
                            return 0;
                        }
                    })
                    ->addColumn('statutory_impairment_value', function ($data) {
                        if ($data->impairment_value != "null") {
                            return $data->impairment_value;
                        } else {
                            return 0;
                        }
                    });
            }
            return $datatable->toJson();

        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * see details for the lease valuation on the valuation tab for the initial or subsequent valuation..
     * @todo lease_end_date needs to be calculated on the basis of termination and other perspective..
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeDetails($id)
    {
        try {
            $history = LeaseHistory::query()->findOrFail($id);

            $lease = $history->lease;

            $json_step_data = collect(json_decode($history->json_data_steps, true));
            $final_data = [];
            $final_data['valuation_type'] = is_null($history->modify_id) ? "Initial Valuation" : $history->leaseModification->valuation;

            $base_date = $account_base_date = getParentDetails()->accountingStandard->base_date;

            $start_date = Carbon::parse(is_null($history->modify_id) ? $json_step_data['underlying_asset']['accural_period'] : $history->leaseModification->effective_from);

            $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;

            $final_data['effective_date'] = $base_date;

            $final_data["value_of_lease_asset"] = $json_step_data['underlying_asset']['value_of_lease_asset'];

            $source = $json_step_data["lessor_details"]["lease_contract_id"];


            $payments = $json_step_data['lease_payments'];
            foreach ($payments as $payment) {
                // No need to show the Non Lease Component Payments...
                if($payment['type'] != '2'){
                    $payment_details = [];
                    $payment_details['payment_name'] = $payment['name'];
                    $payment_details['effective_lease_start_date'] = $final_data['effective_date'];
                    $payment_details['lease_end_date'] = $json_step_data['underlying_asset']['lease_end_date'];
                    $payment_details['undiscounted_lease_liability'] = $payment['undiscounted_value'];
                    $payment_details['present_value'] = $payment['present_value'];
                    $final_data['payments'][] = $payment_details;
                }
            }

            //termination option from the json data...
            if ($json_step_data['termination_option']['lease_termination_option_available'] == "yes" && $json_step_data['termination_option']['exercise_termination_option_available'] == "yes") {
                $final_data['payments'][] = [
                    'payment_name' => 'Termination Penalty',
                    'effective_lease_start_date' => $final_data['effective_date'],
                    'lease_end_date' => $json_step_data['termination_option']['lease_end_date'],
                    'undiscounted_lease_liability' => $json_step_data['termination_option']['undiscounted_value'],
                    'present_value' => $json_step_data['termination_option']['present_value']
                ];
            }

            //residual value guarantee
            if ($json_step_data['residual_value']['any_residual_value_gurantee'] == "yes") {
                $final_data['payments'][] = [
                    'payment_name' => 'Residual Value Guarantee',
                    'effective_lease_start_date' => $final_data['effective_date'],
                    'lease_end_date' => $json_step_data['underlying_asset']['lease_end_date'],
                    'undiscounted_lease_liability' => $json_step_data['residual_value']['undiscounted_value'],
                    'present_value' => $json_step_data['residual_value']['present_value']
                ];
            }

            //purchase option
            if (isset($json_step_data['purchase_option']) && $json_step_data['purchase_option']['purchase_option_clause'] == 'yes' && $json_step_data['purchase_option']['purchase_option_exerecisable'] == "yes") {
                $final_data['payments'][] = [
                    'payment_name' => 'Purchase Option',
                    'effective_lease_start_date' => $final_data['effective_date'],
                    'lease_end_date' => $json_step_data['purchase_option']['expected_lease_end_date'],
                    'undiscounted_lease_liability' => $json_step_data['purchase_option']['undiscounted_value'],
                    'present_value' => $json_step_data['purchase_option']['present_value']
                ];
            }


            $currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

            $show_statutory = false;
            $exchange_rate = 1;
            $statutory_currency = $currency_settings->statutory_financial_reporting_currency;
            if ($currency_settings && ($currency_settings->statutory_financial_reporting_currency != $lease->lease_contract_id)) {
                $destination = $currency_settings->statutory_financial_reporting_currency;
                $show_statutory = true;
                if (Carbon::parse($json_step_data['underlying_asset']['accural_period'])->greaterThan(Carbon::parse($base_date))) {
                    $date = Carbon::parse($json_step_data['underlying_asset']['accural_period'])->format('Y-m-d');
                } else {
                    $date = $base_date;
                }
                //check for the subsequent and fetch the exchange rate for the effective date in that case...
                if ($final_data['valuation_type'] == "Subsequent Valuation") {
                    $date = $final_data['effective_date'];
                }
                $exchange_rate = fetchCurrencyExchangeRate($date, $source, $destination);
            }

            $final_data['lease_currency'] = $source;

            $final_data['statutory_currency'] = $currency_settings->statutory_financial_reporting_currency;

            $final_data['lease_balances'] = is_array($json_step_data['lease_balance']) ? $json_step_data['lease_balance'] : [];

            $final_data['initial_direct_cost'] = (count($json_step_data['initial_direct_cost']) > 1) ? $json_step_data['initial_direct_cost'] : [];

            $final_data['lease_incentives'] = (count($json_step_data['lease_incentives']) > 1) ? $json_step_data['lease_incentives'] : [];

            $final_data['dismantling_cost'] = (count($json_step_data['dismantling_cost']) > 1) ? $json_step_data['dismantling_cost'] : [];

            return view('leasevaluation.partials._see_details_valuation', compact(
                'final_data',
                'show_statutory',
                'exchange_rate',
                'statutory_currency',
                'account_base_date'
            ));


        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * generate the discount rate chart json and return the same to the UI
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateDiscountRateChart($id)
    {
        $lease = $lease = Lease::query()
            ->where('id', '=', $id)
            ->whereIn('business_account_id', getDependentUserIds())
            ->where('status', '=', '1')->firstOrFail();

        $asset = $lease->assets()->first(); //there will be only one lease asset for a lease...

        $chartData = DiscountRateChartView::query()
            ->where('lease_id', '=', $lease->id)
            ->orderBy('history_id', 'desc')
            ->get()
            ->toArray();

        $start_date = $asset->lease_start_date;
        $end_date = $asset->lease_end_date;

        $lower_limit = $start_date;
        $data = [];
        while (strtotime($lower_limit) <= strtotime($end_date)) {
            $filtered_object = collect($chartData)
                ->where('effective_date', '<=', $lower_limit)
                ->first();
            if (!empty($filtered_object)) {
                $data['x'][] = date('M - Y', strtotime($lower_limit));
                $data['y'][] = (float)$filtered_object['discount_rate_to_use'];
            }
            $lower_limit = date("Y-m-d", strtotime("+1 month", strtotime($lower_limit)));
        }
        return response()->json($data, 200);
    }

    /**
     * interest and depreciation Tab on the Lease Valuation...
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function interestDepreciation($id)
    {
        try {
            $lease = Lease::query()
                ->where('id', '=', $id)
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '1')->firstOrFail();

            $asset = $lease->assets()->first();

            $interest_depreciation = InterestAndDepreciation::query()->where('asset_id', '=', $lease->assets()->first()->id)->get();
            $interest_depreciation = collect($interest_depreciation)->groupBy('modify_id');

            return view('leasevaluation.interest_depreciation', compact(
                'interest_depreciation',
                'lease',
                'asset'
            ));

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * exports the interest and depreciation for a particular lease asset..
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportInterestDepreciation($id){
        try{
            $lease = Lease::query()
                ->where('id', '=', $id)
                ->whereIn('business_account_id', getDependentUserIds())
                ->where('status', '=', '1')->firstOrFail();

            $asset = $lease->assets()->first();

            return Excel::download(new InterestAndDepreciationExport($asset->id), 'interest_and_depreciation.xlsx');
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * Show escalation chart for the latest updated data into the database...
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEscalationChart($id)
    {
        try {
            $payment = LeaseAssetPayments::query()->findOrFail($id);
            //need to make the call to the generateEscalationChart function from here....
            $asset = $payment->asset;
            $lease = $asset->lease;
            $data = PaymentEscalationDetails::query()
                ->where('payment_id', '=', $payment->id)
                ->first()
                ->toArray();

            $escalation = generateEsclationChart($data, $payment, $lease, $asset);

            $years = $escalation['years'];
            $months = $escalation['months'];
            $escalations = $escalation['escalations'];

            $errors = [];

            $requestData = $data;

            return view('lease.escalation._chart', compact(
                'errors',
                'lease',
                'asset',
                'years',
                'months',
                'escalations',
                'requestData'
            ));

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * generate the pv calculus from the lease history
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pvCalculus($id)
    {
        try {
            $history = LeaseHistory::query()->findOrFail($id);
            $calculus = json_decode($history->pv_calculus, true);

            $years = $calculus['years'];
            $months = $calculus['months'];
            $liability_caclulus_data = $calculus['present_value_data'];
            $payments = json_decode($history->json_data_steps, true); //need to take out the payments only where the due dates exists...
            $payments = $payments['lease_payments'];
            return view('leasevaluation.partials._pv_calculus', compact(
                'years',
                'months',
                'liability_caclulus_data',
                'payments'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }
}

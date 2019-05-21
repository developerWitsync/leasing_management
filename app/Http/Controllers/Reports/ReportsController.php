<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 15/5/19
 * Time: 1:00 PM
 */

namespace App\Http\Controllers\Reports;


use App\Http\Controllers\Controller;
use App\InterestAndDepreciation;
use App\Lease;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ReportsController extends Controller
{
    /**
     * renders the view for the reports page.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * renders the view for the lease liability contractual payments reports.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leaseLiabilityContractual()
    {
        return view('reports.leasereports.contractual');
    }

    /**
     * returns the JSON for the datatable for the Contractual Report Generation
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchLeaseLiabilityContractual(Request $request)
    {
        try {

            $model = Lease::query()
                ->selectRaw('lease.*')
                ->selectRaw('lease_assets.id as asset_id')
                ->selectRaw('lease_history.json_data_steps->>"$.underlying_asset.lease_liablity_value" as initial_present_value')
                ->selectRaw('lease_history.json_data_steps->>"$.underlying_asset.value_of_lease_asset" as initial_value_of_lease_asset')
                ->selectRaw('IFNULL(lease_history.json_data_steps->>"$.underlying_asset.adjustment_to_equity", 0) as adjustment_to_equity')
                ->selectRaw('SUM(DISTINCT(interest_and_depreciation.change)) as increase_decrease')
                ->selectRaw('SUM(DISTINCT(interest_and_depreciation.charge_to_pl)) as charge_to_pl')
                ->selectRaw('SUM(interest_and_depreciation.interest_expense) as lease_interest')
                ->selectRaw('SUM(interest_and_depreciation.lease_payment) as contractual_lease_payment')
                ->with('singleAsset')
                ->with('singleAsset.subcategory')
                ->with('singleAsset.country')
                ->with('singleAsset.specificUse')
                ->join('lease_assets', 'lease.id', 'lease_assets.lease_id')
                ->join('lease_history', 'lease_history.lease_id', '=', 'lease.id')
                ->groupBy('lease.id')
                ->whereIn('lease.business_account_id', getDependentUserIds())
                ->where('lease.status', '=', '1')
                ->whereRaw('lease_history.modify_id IS NULL');


            if($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
                $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
                $model->leftJoin('interest_and_depreciation', function ($join) use ($start_date , $end_date) {
                    $join->on('lease_assets.id', '=', 'interest_and_depreciation.asset_id')
                        ->whereBetween('interest_and_depreciation.date', [$start_date, $end_date]);
                });
                /**
                 * conditions to include the active leases
                 */
                $model->where(function($query) use ($start_date, $end_date){
                    $query->whereRaw("'{$start_date}' between lease_assets.lease_start_date and lease_assets.lease_end_date");
                    $query->whereRaw("'{$end_date}' between lease_assets.lease_start_date and lease_assets.lease_end_date");
                });

                $model->orWhere(function($query) use ($start_date, $end_date){
                    $query->whereRaw("lease_assets.lease_start_date < '{$start_date}' AND  lease_assets.lease_end_date Between '{$start_date}' and '{$end_date}'");
                });

                $model->orWhere(function($query) use ($start_date, $end_date){
                    $query->whereRaw("lease_assets.lease_start_date Between '{$start_date}' and '{$end_date}' AND '{$end_date}' < lease_assets.lease_end_date");
                });
                /**
                 * Conditions end here
                 */

                $model->selectRaw('(SELECT SUM(`depreciation`) FROM `interest_and_depreciation` where last_day(`date`) = `date` and asset_id = lease_assets.id AND `date` between '.$start_date.' AND '.$end_date.') as depreciation');
            } else {
                $model->leftJoin('interest_and_depreciation', 'interest_and_depreciation.asset_id', '=', 'lease_assets.id');
                $model->selectRaw('(SELECT SUM(`depreciation`) FROM `interest_and_depreciation` where last_day(`date`) = `date` and asset_id = lease_assets.id) as depreciation');
            }

            $datatable = datatables()->eloquent($model);

            $datatable->filter(function ($query) use ($request){
                if ($request->has('search') && trim($request->search["value"])!="") {
                    $query->where(function($query) use ($request){
                        $query->where('lease.lessor_name', 'like', "%" . $request->search["value"] . "%");
                        $query->orWhere('lease_assets.name', 'like', "%" . $request->search["value"] . "%");
                    });
                }
            });

            $datatable->addColumn('closing_value_lease_liability', function ($data) use ($request) {
                $row = InterestAndDepreciation::query()
                    ->where('asset_id', '=', $data->asset_id);

                if($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
                    $row->where('date' ,'<=' , $end_date);
                }

                $row->orderBy('id', 'desc');
                $row = $row->first();

                return ($row) ? $row->closing_lease_liability : 0;
            });

            $datatable->addColumn('carrying_value_of_lease_asset', function ($data) use ($request) {
                $row = InterestAndDepreciation::query()
                    ->where('asset_id', '=', $data->asset_id);

                if($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
                    $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
                    $row->where('date' ,'<=' , $end_date);
                }

                $row->orderBy('id', 'desc');
                $row = $row->first();
                return ($row) ? $row->carrying_value_of_lease_asset : 0;
            });

            return $datatable->toJson();
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
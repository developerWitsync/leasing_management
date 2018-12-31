<?php
/**
 * Created by Sublime.
 * User: Jyoti
 * Date: 28/12/18
 * Time: 4:10 PM
 */
namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssetPaymentsNature;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssets;
use App\LeasePaymentsFrequency;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Illuminate\Http\Request;

class LeaseResidualController extends Controller
{
     /**
     * Render the table for all the lease assets so that the user can complete steps for the residual value gurantee
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                ->with('assets.category')
                ->with('assets.subcategory')
                ->where('id', '=', $id)
                ->first();

           // $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
            $foreign_currency_if_yes = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

             if($lease) {
                return view('lease.residual-value-gurantee.index', compact(
                    'lease','lease_payments_nature','reporting_currency_settings','foreign_currency_if_yes'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

    public function create($lease_id, $asset_id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
            if($lease){
                $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();
                if($asset) {
                    $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id','number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    $lease_payments_types = LeasePaymentComponents::query()->get();
                    $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                    $payments_frequencies =   LeasePaymentsFrequency::query()->get();
                    $total_payments = $request->has('total_payments')?$request->total_payments:0;
                    return view('lease.residual-value-gurantee.create', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments',
                        'total_payments',
                        'lease_payments_types',
                        'lease_payments_nature',
                        'payments_frequencies'
                    ));
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404);
        }
    }
}

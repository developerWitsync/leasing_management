<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 27/12/18
 * Time: 2:28 PM
 */

namespace App\Http\Controllers\Lease;


use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssets;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Illuminate\Http\Request;

class LeasePaymentsController extends Controller
{
    /**
     * Render the table for all the lease assets so that the user can complete steps for the payments
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

            if($lease) {
                return view('lease.payments.index', compact(
                    'lease'
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
                    $total_payments = $request->has('total_payments')?$request->total_payments:0;
                    return view('lease.payments.create', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments',
                        'total_payments',
                        'lease_payments_types'
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
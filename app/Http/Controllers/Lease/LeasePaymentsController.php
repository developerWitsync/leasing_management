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
use App\LeaseAssetPayments;
use App\LeaseAssetPaymentsNature;
use App\LeaseAssets;
use App\LeasePaymentsFrequency;
use App\LeasePaymentsInterval;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

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

    /**
     * Renders the form for creating the payments for all the assets one-by-one on the step forms
     * @param $lease_id
     * @param $asset_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($lease_id, $asset_id, $payment_id = null, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
            if($lease){
                $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();
                if($asset) {
                    $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id','number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    $lease_payments_types = LeasePaymentComponents::query()->get();
                    $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                    $payments_frequencies =   LeasePaymentsFrequency::query()->get();
                    $payments_payout_times = LeasePaymentsInterval::query()->get();
                    $total_payments = $request->has('total_payments')?$request->total_payments:0;
                    $payment = new LeaseAssetPayments();

                    if($payment_id) {
                        $payment = LeaseAssetPayments::query()->where('asset_id', '=', $asset->id)->where('id', '=', $payment_id)->first();
                        if(is_null($payment)) {
                            abort(404);
                        }
                    }

                    return view('lease.payments.create', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments',
                        'total_payments',
                        'lease_payments_types',
                        'lease_payments_nature',
                        'payments_frequencies',
                        'payments_payout_times',
                        'payment'
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

    /**
     * Create the Asset Payment for the asset and redirects the user based upon the action button that has been pressed
     * @param $id Asset Id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveAssetPayments($id, $payment_id = null, Request $request){
        try{
            $asset =  LeaseAssets::query()->findOrFail($id);

            $payment = new LeaseAssetPayments();
            if($payment_id){
                $payment = LeaseAssetPayments::query()->findOrFail($payment_id);
            }

            $rules = [
                'name'                          => 'required',
                'type'                          => 'required',
                'nature'                        => 'required',
                'variable_basis'                => 'required_if:nature,2',
                'variable_amount_determinable'  => 'required_if:nature,2',
                'payment_interval'              => 'required',
                'payout_time'                   => 'required',
                'first_payment_start_date'      => 'required|date',
                'last_payment_end_date'         => 'required|date',
                'payment_currency'              => 'required',
                'similar_chateristics_assets'   => 'required|numeric',
                'payment_per_interval_per_unit' => 'required|numeric',
                'total_amount_per_interval'     => 'required|numeric',
                'attachment'                  => 'file|mimes:jpeg,pdf,doc'
            ];

            $validator = Validator::make($request->except('_token'), $rules);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
            }

            $data = $request->except('_token', 'similar_chateristics_assets', 'total_payments');

            $data['first_payment_start_date'] = Carbon::parse($request->first_payment_start_date)->format('Y-m-d');
            $data['last_payment_end_date'] = Carbon::parse($request->last_payment_end_date)->format('Y-m-d');
            $data['attachment'] = "";
            $data['asset_id'] = $asset->id;
            if($request->hasFile('attachment')){
                $file = $request->file('attachment');
                $uniqueFileName = uniqid() . $file->getClientOriginalName();
                $request->file('attachment')->move('uploads', $uniqueFileName);
                $data['attachment'] = $uniqueFileName;
            }
            $payment->setRawAttributes($data);
            if($payment->save()) {
                return redirect(route('lease.payments.add', [
                    'lease_id' => $asset->lease->id,
                    'asset_id' => $asset->id,
                    'payment_id' => $payment->id,
                    'total_payments'    => $request->has('total_payments')?$request->total_payments:1
                ]))->with('status', 'Lease asset payment has been added successfully.');
            } else {
                return redirect()->back()->with('error','Something went wrong. Please try again.')->withInput($request->except('_token'));
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
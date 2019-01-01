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
     * validation rules for the create and update payments
     * @return array
     */
    protected function validationRules(){
        return [
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
    }

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
                $show_next = false;
                $completed_payments = 0;
                $required_payments = 0;
                foreach ($lease->assets as $asset){
                    $required_payments += $asset->total_payments;
                    if($asset->total_payments >  0 && count($asset->payments) > 0) {
                        $completed_payments += count($asset->payments);
                    } else {
                        $required_payments += 1; //incrementing by one to not show the next button
                        break;
                    }
                }

                $show_next = $required_payments == $completed_payments;

                return view('lease.payments.index', compact(
                    'lease',
                    'show_next'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * Renders the form to select the total number of the payments for the asset and renders the table to list all the asset payments as well.
     * @param $lease_id
     * @param $asset_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($lease_id, $asset_id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
            if($lease){
                $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();
                if($asset) {
                    $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id','number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    return view('lease.payments.create', compact(
                        'lease',
                        'asset',
                        'lease_asset_number_of_payments'
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
    public function createAssetPayments($id, Request $request){
        try{
            $asset =  LeaseAssets::query()->findOrFail($id);
            $lease = $asset->lease;
            $payment = new LeaseAssetPayments();

            //check if the already created payments are less than the total payments.
            if(!(count($asset->payments) < $asset->total_payments)) {
                return redirect()->back()->with('error', 'You cannot add more payments to this asset.');
            }

            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), $this->validationRules());
                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                }
                $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit');
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
                    return redirect(route('lease.payments.add', ['lease_id' => $asset->lease->id, 'asset_id' => $asset->id]))->with('status', 'Lease Asset Payments has been added successfully.');
                } else {
                    return redirect()->back()->with('error','Something went wrong. Please try again.')->withInput($request->except('_token'));
                }
            }

            $lease_payments_types = LeasePaymentComponents::query()->get();
            $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $payments_frequencies =   LeasePaymentsFrequency::query()->get();
            $payments_payout_times = LeasePaymentsInterval::query()->get();

            return view('lease.payments.createpayment', compact(
                'asset',
                'lease',
                'payment',
                'lease_payments_nature',
                'lease_payments_types',
                'payments_frequencies',
                'payments_payout_times'
            ));

        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * update a particular already created asset payment
     * @param $id
     * @param $payment_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateAssetPayments($id, $payment_id, Request $request){
        try{
            $asset =  LeaseAssets::query()->findOrFail($id);
            $lease = $asset->lease;
            $payment = LeaseAssetPayments::query()->where('asset_id', '=', $id)->where('id', '=', $payment_id)->first();
            if($payment) {
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());
                    if($validator->fails()){
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except('_token'));
                    }
                    $data = $request->except('_token', 'similar_chateristics_assets', 'step', 'submit');
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
                        return redirect(route('lease.payments.add', ['lease_id' => $asset->lease->id, 'asset_id' => $asset->id]))->with('status', 'Lease Asset Payments has been updated successfully.');
                    } else {
                        return redirect()->back()->with('error','Something went wrong. Please try again.')->withInput($request->except('_token'));
                    }
                }

                $lease_payments_types = LeasePaymentComponents::query()->get();
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                $payments_frequencies =   LeasePaymentsFrequency::query()->get();
                $payments_payout_times = LeasePaymentsInterval::query()->get();

                return view('lease.payments.updatepayment', compact(
                    'asset',
                    'lease',
                    'payment',
                    'lease_payments_nature',
                    'lease_payments_types',
                    'payments_frequencies',
                    'payments_payout_times'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Save the Total Payments number to the lease asset
     * @param $id Asset ID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTotalPayments($id, Request $request){
        try{
            $asset =  LeaseAssets::query()->findOrFail($id);
            $asset->total_payments = $request->has('no_of_lease_payments')?$request->no_of_lease_payments:0;
            $asset->save();
            return redirect()->back();
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * fetch the lease asset payments for a single asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchAssetPayments($id, Request $request){
        try{
            if ($request->ajax()) {
                $model = LeaseAssetPayments::query()->where('asset_id', '=', $id)->with('category');
                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
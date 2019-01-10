<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 03/01/19
 * Time: 3:24 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseBalanceAsOnDec;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class LeaseBalanceAsOnDecController extends Controller
{
    protected function validationRules(){
        return [
            'reporting_currency' => 'required',
            /*'carrying_amount'=>'required_if:accounting_treatment,2',
            'liability_balance'=>'required_if:accounting_treatment,2',
            */'prepaid_lease_payment_balance'   => 'required|numeric',
            'accrued_lease_payment_balance'   => 'required|numeric',
            'outstanding_lease_payment_balance'   => 'required|numeric',
            'any_provision_for_onerous_lease'   => 'required|numeric'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){

        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.balanceasondec.index',['id' => $id]),
                'title' => 'Lease Balance as on 31 Dec 2018'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
            //Load the assets only lease start prior to Dec 31 2018
             
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date','<','2019-01-01')->get();

           return view('lease.lease-balnce-as-on-dec.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add lease balance as on 31 Dec 2018 for an asset in NL-12
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseBalanceAsOnDec();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                   

                    $select_discount_value = LeaseBalanceAsOnDec::create($data);
                    if($select_discount_value){
                        return redirect(route('addlease.balanceasondec.index',['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been added successfully.');
                    }
                }
                return view('lease.lease-balnce-as-on-dec.create', compact(
                    'model',
                    'lease',
                    'asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * edit existing lease balance as on 31 dec 2018 details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseBalanceAsOnDec::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                   
                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.balanceasondec.index',['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been updated successfully.');
                    }
                }
                return view('lease.lease-balnce-as-on-dec.update', compact(
                    'model',
                    'lease',
                    'asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
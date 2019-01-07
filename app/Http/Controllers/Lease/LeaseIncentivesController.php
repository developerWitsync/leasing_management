<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 04/01/19
 * Time: 9:43 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseIncentives;
use App\ForeignCurrencyTransactionSettings;
use App\CustomerIncentives;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class LeaseIncentivesController extends Controller
{
    protected function validationRules(){
        return [
            'is_any_lease_incentives_receivable' => 'required',
            'total_lease_incentives'   => 'required|numeric',
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
                'link' => route('addlease.leaseincentives.index',['id' => $id]),
                'title' => 'Lease Incentives'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
            //Load the assets only lease start on or after jan 01 2019
             
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date','>=','2019-01-01')->get();
            
            return view('lease.lease-incentives.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add lease incentives for an asset in NL-14
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currency = ForeignCurrencyTransactionSettings::where('business_account_id', '=', $lease->business_account_id)->get();
             $customer_details = CustomerIncentives::query()->get();
           
            
            if($lease) {

                $model = new LeaseIncentives();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                   
              
                    $select_discount_value = LeaseIncentives::create($data);
                    if($select_discount_value){
                        return redirect(route('addlease.leaseincentives.index',['id' => $lease->id]))->with('status', 'Lease Incentives has been added successfully.');
                    }
                }
                return view('lease.lease-incentives.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'currency',
                    'customer_details'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * edit existing lease incentives details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currency = ForeignCurrencyTransactionSettings::where('business_account_id', '=', $lease->business_account_id)->get();
            $customer_details = CustomerIncentives::query()->get();

            if($lease) {

                $model = LeaseIncentives::query()->where('asset_id', '=', $id)->first();

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
                        return redirect(route('addlease.leaseincentives.index',['id' => $lease->id]))->with('status', 'Lease Incentives has been updated successfully.');
                    }
                }
                return view('lease.lease-incentives.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'currency',
                    'customer_details'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

      /**
     * Add Customer Details for lease incentives
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCustomerDetails(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'customer_name' => 'required',
                    'description' => 'required',
                    'currency_id' => 'required',
                    'amount' => 'required|numeric'
              ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }
                $data = $request->except('_token', 'submit');
                   
                 $model = CustomerIncentives::create($data);
                
                if($model){
                    return redirect()->back()->with('status', 'Customer Details has been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(404,$e->getMessage());
        }
    }
    /**
     * Delete Customer Details from lease incentives
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deletecustomerdetails($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_lock_year = CustomerIncentives::query()->where('id', $id);
                if($lease_lock_year) {
                    $lease_lock_year->delete();
                    Session::flash('status', 'Customer Details has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
              dd($e->getMessage());
            abort(404);
        }
    }
}
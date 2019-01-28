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
use App\ReportingCurrencySettings;
use App\Currencies;
use App\CustomerDetails;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class LeaseIncentivesController extends Controller
{
    protected function validationRules(){
        return [
            'is_any_lease_incentives_receivable' => 'required',
            'total_lease_incentives'  => 'required_if:is_any_lease_incentives_receivable,yes'
          ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){
        if(!checkPreviousSteps($id,'step14')){
                return  redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
        }
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
     * add Lease incentive details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
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
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseIncentives();

                $customer_model = new CustomerDetails();

                if($request->isMethod('post')) {

                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        dd($validator->errors());
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    $lease_incentive_cost = LeaseIncentives::create($data);

                    if($lease_incentive_cost){

                        $customer_details = Session::get('customer_details');

                        foreach ($customer_details as $customer_detail){
                            CustomerDetails::create([
                                'lease_incentive_id' => $lease_incentive_cost->id,
                                'customer_name' => $customer_detail['customer_name'],
                                'description'   => $customer_detail['description'],
                                'incentive_date'  => date('Y-m-d', strtotime($customer_detail['incentive_date'])),
                                'currency_id'  => $customer_detail['currency_id'],
                                'amount'  => $customer_detail['amount'],
                                'exchange_rate'  => $customer_detail['exchange_rate']
                            ]);
                        }

                        Session::forget('customer_details');

                         // complete Step
                        $lease_id = $lease->id;
                        $step= 'step15';
                        $complete_step15 = confirmSteps($lease_id,$step);

                        return redirect(route('addlease.leaseincentives.index',['id' => $lease->id]))->with('status', 'Lease incentive cost has been added successfully.');
                    }
                }

                $customer_details = [];

                Session::put('customer_details', $customer_details);

                return view('lease.lease-incentives.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'customer_model',
                    'customer_details',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
    /**
     * edit existing lease incentives value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){

        try{
            Session::forget('customer_details');
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseIncentives::query()->where('asset_id', '=', $id)->first();
                $lease_incentive_id = $model->id;
                
                if($request->isMethod('post')) {

                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }
                    $data = $request->except('_token');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->is_any_lease_incentives_receivable == 'no')
                     {
                        CustomerDetails::query()->where('lease_incentive_id', '=', $lease_incentive_id)->delete();
                        $data['total_lease_incentives'] = 0;
                     }
                  

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.leaseincentives.index',['id' => $lease->id]))->with('status', 'Lease Incentives has been updated successfully.');
                    }
                }
                return view('lease.lease-incentives.update', compact(
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
     * Add customers to the session variables so that those can be saved
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function addCustomer(Request $request){
        $currencies = Currencies::query()->where('status', '=', '1')->get();
        try{
            $customer_details = Session::get('customer_details');
            if($request->ajax()) {
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
                        'customer_name' => 'required',
                        'description' => 'required',
                        'incentive_date' => 'required',
                        'currency_id'  => 'required',
                        'amount'    => 'required|numeric',
                        'exchange_rate'    => 'required|numeric'
                     ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ], 200);
                    }


                    //save to the session variable
                    $customer_details = Session::get('customer_details');
                     
                    array_push($customer_details, $request->except('_token'));

                    Session::put('customer_details', $customer_details);

                    
                  
        return view('lease.lease-incentives._customer_details_form', compact(
                        'customer_details','currencies'
                    ));

                }
                return view('lease.lease-incentives._customer_details_form',compact(
                    'customer_details','currencies'
                ));
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

    public function updateCustomer($id, Request $request){
       try{
        $directCost = LeaseIncentives::query()->findOrFail($id);
        $currencies = Currencies::query()->where('status', '=', '1')->get();

            return view('lease.lease-incentives._customer_details_update_form',compact(
                'directCost','currencies'
            ));
           
        } catch (\Exception $e) {
          dd($e);

        }
    }

    /**
     * create customer to the database for any lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function createCustomer(Request $request){
         try{
            if($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'customer_name' => 'required',
                    'description' => 'required',
                    'incentive_date' => 'required|date',
                    'currency_id'  => 'required',
                    'amount'    => 'required|numeric',
                    'exchange_rate'    => 'required|numeric'
                ]);

                if($validator->fails()){
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 200);
                }
                $data = $request->except('_token');
                $data['incentive_date']  = date('Y-m-d', strtotime($request->incentive_date));

                 CustomerDetails::create($data);

                Session::flash('status', 'Customer Details has been updated successfully.');

                return response()->json([
                    'status' => true
                ], 200);
            }
        } catch (\Exception $e){
            dd($e->getMessage());
            dd($e);
        }
    }

    /**
     * Delete a particular Customer Details from the update popup
     * @param $id CustomerDetails id
     * @param $lease_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCustomer($id, $lease_id){
        try {
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
            if($lease){
                CustomerDetails::query()->where('id', '=', $id)->delete();
                return response()->json([
                    'status' => true
                ], 200);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
    /**
     * Delete a Create Customer Details from the Key in Pop Up
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCreateCustomer($key){
        try {
          Session::forget("customer_details.{$key}");
          $customer_details = Session::get("customer_details");
          unset($customer_details[$key]);
          Session::put("customer_details", $customer_details);
          return response()->json([
            'status' => true
          ], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    
    }

}
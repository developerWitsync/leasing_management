<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;


use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssets;
use App\InitialDirectCost;
use App\SupplierDetails;
use App\Currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class InitialDirectCostController extends Controller
{

    protected function validationRules(){
        return [
            'initial_direct_cost_involved'   => 'required',
            'currency' => 'required_if:initial_direct_cost_involved,yes',
            'total_initial_direct_cost'  => 'required_if:initial_direct_cost_involved,yes'
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.initialdirectcost.index',['id' => $id]),
                'title' => 'Initial Direct Cost'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.initial-direct-cost.index', compact('breadcrumbs',
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            if($lease) {

                $model = new InitialDirectCost();

                $supplier_model = new SupplierDetails();

                if($request->isMethod('post')) {

                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    $initial_direct_cost = InitialDirectCost::create($data);

                    if($initial_direct_cost){

                        $supplier_details = Session::get('supplier_details');
                        foreach ($supplier_details as $supplier_detail){
                            SupplierDetails::create([
                                'initial_direct_cost_id' => $initial_direct_cost->id,
                                'supplier_name' => $supplier_detail['supplier_name'],
                                'direct_cost_description'   => $supplier_detail['direct_cost_description'],
                                'expense_date'  => date('Y-m-d', strtotime($supplier_detail['expense_date'])),
                                'supplier_currency'  => $supplier_detail['supplier_currency'],
                                'amount'    => $supplier_detail['amount'],
                                'rate'  => $supplier_detail['rate']
                            ]);
                        }

                        Session::forget('supplier_details');

                        return redirect(route('addlease.initialdirectcost.index',['id' => $lease->id]))->with('status', 'Initial Direct Cost has been added successfully.');
                    }
                }

                $supplier_details = [];

                Session::put('supplier_details', $supplier_details);

                return view('lease.initial-direct-cost.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'supplier_model',
                    'supplier_details'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    /**
     * edit existing fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            Session::forget('supplier_details');
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = InitialDirectCost::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.initialdirectcost.index',['id' => $lease->id]))->with('status', 'Initial Direct Cost has been updated successfully.');
                    }
                }
                return view('lease.initial-direct-cost.update', compact(
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
     * Add suppliers to the session variables so that those can be saved
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function addSupplier(Request $request){
        try{
            $supplier_details = Session::get('supplier_details');
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            if($request->ajax()) {
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
                        'supplier_name' => 'required',
                        'direct_cost_description' => 'required',
                        'expense_date'  => 'required|date',
                        'supplier_currency'  => 'required',
                        'amount'    => 'required|numeric',
                        'rate'  => 'required|numeric'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ], 200);
                    }

                    //save to the session variable
                    $supplier_details = Session::get('supplier_details');
                    $currencies = Currencies::query()->where('status', '=', '1')->get();
                    array_push($supplier_details, $request->except('_token'));

                    Session::put('supplier_details', $supplier_details);

                    return view('lease.initial-direct-cost._supplier_details_form', compact(
                        'supplier_details', 'currencies'
                    ));

                }
                return view('lease.initial-direct-cost._supplier_details_form',compact(
                    'supplier_details', 'currencies'
                ));
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

    public function updateSupplier($id, Request $request){
        try{
            $directCost = InitialDirectCost::query()->findOrFail($id);
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            return view('lease.initial-direct-cost._supplier_details_update_form',compact(
                'directCost', 'currencies'
            ));
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * create supplier to the database for any
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function createSupplier(Request $request){
        try{
            if($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'supplier_name' => 'required',
                    'direct_cost_description' => 'required',
                    'expense_date'  => 'required|date',
                    'supplier_currency'  => 'required',
                    'amount'    => 'required|numeric',
                    'rate'  => 'required|numeric'
                ]);

                if($validator->fails()){
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 200);
                }
                $data = $request->except('_token');
                $data['expense_date'] = date('Y-m-d', strtotime($request->expense_date));
                SupplierDetails::create($data);

                Session::flash('status', 'Supplier Details has been updated successfully.');

                return response()->json([
                    'status' => true
                ], 200);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * Delete a particular Supplier Details from the update popup
     * @param $id SupplierDetails id
     * @param $lease_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSupplier($id, $lease_id){
        try {
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
            if($lease){
                SupplierDetails::query()->where('id', '=', $id)->delete();
                return response()->json([
                    'status' => true
                ], 200);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Delete a Create Supplier Details from the Key in Pop Up
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCreateSupplier($key){
        try {
          Session::forget("supplier_details.{$key}");
          $supplier_details = Session::get("supplier_details");
          unset($supplier_details[$key]);
          Session::put("supplier_details", $supplier_details);
          return response()->json([
            'status' => true
          ], 200);
        } catch (\Exception $e) {
            dd($e);
        }
   }

}


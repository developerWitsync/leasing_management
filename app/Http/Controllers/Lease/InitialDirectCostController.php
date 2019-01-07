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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class InitialDirectCostController extends Controller
{

    protected function validationRules(){
        return [
            'initial_direct_cost_involved'   => 'required',
            'currency' => 'required_if:initial_direct_cost_involved,yes',
            'similar_asset_items'   => 'required_if:initial_direct_cost_involved,yes',
            'total_initial_direct_cost'  => 'required_if:is_market_value_present,yes'
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
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
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

    public function addSupplier(Request $request){
        try{
            $supplier_details = Session::get('supplier_details');
            if($request->ajax()) {
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->all(), [
                        'supplier_name' => 'required',
                        'direct_cost' => 'required',
                        'expense_date'  => 'required|date',
                        'currency'  => 'required',
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

                    array_push($supplier_details, $request->except('_token'));

                    Session::put('supplier_details', $supplier_details);

                    return view('lease.initial-direct-cost._supplier_details_form', compact(
                        'supplier_details'
                    ));

                }
                return view('lease.initial-direct-cost._supplier_details_form',compact(
                    'supplier_details'
                ));
            }
        } catch (\Exception $e){
            dd($e);
        }
    }
}
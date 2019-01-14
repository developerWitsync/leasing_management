<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 03/01/19
 * Time: 12:08 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\LeaseDurationClassified;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class LeaseValuationController extends Controller
{
    protected function validationRules(){
        return [
            'interest_rate'   => 'required',
            'annual_average_esclation_rate' => 'required',
            'discount_rate_to_use' => 'required|numeric|min:2'
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
                'link' => route('addlease.discountrate.index',['id' => $id]),
                'title' => 'Select Discount Rate'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
            //Load the assets only which is not in very short tem/short term lease in NL 8.1(lease_contract_duration table) and not in intengible under license arrangements and biological assets (lease asset categories)
             
             $own_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('specific_use',1)->whereNotIn('category_id',[8,5])->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
            })->get();

             $sublease_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('specific_use',2)->whereNotIn('category_id',[8,5])->whereHas('leaseDurationClassified',  function($query){
                $query->where('lease_contract_duration_id', '=', '3');
            })->get();
           
            return view('lease.lease-valuation.index', compact(
                'lease',
                'own_assets',
                'sublease_assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add select discount rate details for an asset in NL-10
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseSelectDiscountRate();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $select_discount_value = LeaseSelectDiscountRate::create($data);
                    if($select_discount_value){
                        return redirect(route('addlease.discountrate.index',['id' => $lease->id]))->with('status', 'Select Discount Rate has been added successfully.');
                    }
                }
                return view('lease.lease-valuation.create', compact(
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
     * edit existing select discount rate details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseSelectDiscountRate::query()->where('asset_id', '=', $id)->first();

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
                        return redirect(route('addlease.discountrate.index',['id' => $lease->id]))->with('status', 'Select Discount Rate has been updated successfully.');
                    }
                }
                return view('lease.lease-valuation.update', compact(
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
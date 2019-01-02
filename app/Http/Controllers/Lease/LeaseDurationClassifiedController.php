<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 01/01/19
 * Time: 4:24 PM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseDurationClassified;
use App\LeaseRenewableOption;
use App\LeaseTerminationOption;
use App\PurchaseOption;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class LeaseDurationClassifiedController extends Controller
{
    protected function validationRules(){
        return [
            'lease_start_date'   => 'required',
            'lease_end_date'   => 'required'
            
        ];
    }
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.lease-duration-classified.index', compact(
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add lease duration classifed value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $renewal_asset = LeaseRenewableOption::query()->where('asset_id', '=', $id)->get();
            $termination_asset = LeaseTerminationOption::query()->where('asset_id', '=', $id)->get();
            $purchase_option_asset = PurchaseOption::query()->where('asset_id', '=', $id)->get();

            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseDurationClassified();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    $data['expected_lease_end_Date'] = date('Y-m-d', strtotime($request->expected_lease_end_Date));
                    

                    $duration_classified_value = LeaseDurationClassified::create($data);

                    if($duration_classified_value){
                        return redirect(route('addlease.durationclassified.index',['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been added successfully.');
                    }
                }
               return view('lease.lease-duration-classified.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'renewal_asset',
                    'termination_asset',
                    'purchase_option_asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    /**
     * edit existing lease duration classified option value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);

            $renewal_asset = LeaseRenewableOption::query()->where('asset_id', '=', $id)->get();
            $termination_asset = LeaseTerminationOption::query()->where('asset_id', '=', $id)->get();
            $purchase_option_asset = PurchaseOption::query()->where('asset_id', '=', $id)->get();

            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseDurationClassified::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));

                    $model->setRawAttributes($data);
                    if($model->save()){
                        return redirect(route('addlease.durationclassified.index',['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been updated successfully.');
                    }
                }

              
                return view('lease.lease-duration-classified.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'renewal_asset',
                    'termination_asset',
                    'purchase_option_asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
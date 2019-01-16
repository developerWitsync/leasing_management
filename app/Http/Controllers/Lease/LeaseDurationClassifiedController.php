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
use App\LeaseContractDuration;
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
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.durationclassified.index',['id' => $id]),
                'title' => 'Lease Duration Classified'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            $back_button = route('addlease.purchaseoption.index', ['id' => $lease->id]);

            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereHas('terminationOption',  function($query){
                $query->where('exercise_termination_option_available', '=', 'no');
            })->get();

            if(count($assets) == 0) {
                $back_button = route('addlease.leaseterminationoption.index', ['id' => $lease->id]);
            }
            return view('lease.lease-duration-classified.index', compact('breadcrumbs',
                'lease',
                'back_button'
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
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));
                    $data['lease_contract_duration_id'] =$request->lease_contract_duration_id;
                    $data['expected_lease_end_Date'] = date('Y-m-d', strtotime($request->expected_lease_end_Date));
                    

                    $duration_classified_value = LeaseDurationClassified::create($data);

                    if($duration_classified_value){
                        return redirect(route('addlease.durationclassified.index',['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been added successfully.');
                    }
                }

                //find the expected values for the end date, lease classification
                $model->lease_end_date                 = $model->getExpectedLeaseEndDate($asset);
                $model->lease_contract_duration_id               = $model->getLeaseAssetClassification($asset);

                $lease_contract_duration = LeaseContractDuration::query()->get();

                return view('lease.lease-duration-classified.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'expected_lease_classification',
                    'lease_contract_duration'
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
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
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
                $model->lease_end_date                 = $model->getExpectedLeaseEndDate($asset);
                $model->lease_contract_duration_id               = $model->getLeaseAssetClassification($asset);

                $lease_contract_duration = LeaseContractDuration::query()->get();
              
                return view('lease.lease-duration-classified.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_contract_duration'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
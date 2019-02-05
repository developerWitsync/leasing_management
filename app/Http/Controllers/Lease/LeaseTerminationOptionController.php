<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;


use App\Countries;
use App\ExpectedLifeOfAsset;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAccountingTreatment;
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\UseOfLeaseAsset;
use App\LeaseTerminationOption;
use App\FairMarketValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssetPayments;
use App\LeaseResidualValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class LeaseTerminationOptionController extends Controller
{
    protected function validationRules(){
        return [
            'lease_termination_option_available'   => 'required',
            'exercise_termination_option_available'   => 'required_if:lease_termination_option_available,yes',
            'lease_end_date'  => 'required_if:exercise_termination_option_available,yes|nullable|date',
            'termination_penalty_applicable'  => 'required_if:exercise_termination_option_available,yes',
            'currency' => 'required_if:termination_penalty_applicable,yes',
            'termination_penalty'  => 'required_if:termination_penalty_applicable,yes|nullable|numeric',
        ];
    }

    /**
     * create or update the lease asset termination option , the form will appear directly to the  users since they can now add only a single lease asset per lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index_V2($id, Request $request){
        try{
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.leaseterminationoption.index',['id' => $id]),
                    'title' => 'Termination Option'
                ],
            ];
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if($lease) {

                $asset = $lease->assets->first(); //users will have only lease asset from now.
                if($asset->terminationOption){
                    $model = $asset->terminationOption;
                } else {
                    $model = new LeaseTerminationOption();
                }

                if($request->isMethod('post') ) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'uuid', 'asset_name', 'asset_category');
                    if($request->lease_end_date!=""){
                        $data['lease_end_date']  = Carbon::parse($request->lease_end_date)->format('Y-m-d');
                    }
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $model->setRawAttributes($data);

                    if($model->save()){
                        // complete Step
                        confirmSteps($lease->id,'step6');
                        return redirect(route('addlease.leaseterminationoption.index',['id' => $lease->id]))->with('status', 'Lease Termination Option Details has been added successfully.');
                    }
                }
                return view('lease.lease-termination-option.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id){
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.leaseterminationoption.index',['id' => $id]),
                'title' => 'Termination Option'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if($lease) {
            return view('lease.lease-termination-option.index', compact('breadcrumbs',
                'lease'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add Lease Termination Options details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseTerminationOption();

                if($request->isMethod('post') ) {
                    //dd($request->all());
                    if($request->lease_termination_option_available == 'yes'){
                        $validator = Validator::make($request->except('_token'), $this->validationRules());

                        if($validator->fails()){
                           // dd($validator->errors());
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }
                    }

                    $data = $request->except('_token');
                    if($request->lease_end_date!=""){
                        $data['lease_end_date']  = Carbon::parse($request->lease_end_date)->format('Y-m-d');
                    }
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $lease_termination_option = LeaseTerminationOption::create($data);

                    if($lease_termination_option){
                         // complete Step
                        confirmSteps($lease->id,'step6');
                        return redirect(route('addlease.leaseterminationoption.index',['id' => $lease->id]))->with('status', 'Lease Termination Option Details has been added successfully.');
                    }
                }
                return view('lease.lease-termination-option.create', compact(
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
     * edit existing Lease Termination option details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseTerminationOption::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {

                    if($request->lease_termination_option_available == 'yes')
                {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }
                }   
                    $data = $request->except('_token');
                    if($request->lease_end_date!=""){
                        $data['lease_end_date']  = Carbon::parse($request->lease_end_date)->format('Y-m-d');
                    }
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.leaseterminationoption.index',['id' => $lease->id]))->with('status', 'Lease Termination Option Details has been updated successfully.');
                    }
                }
                return view('lease.lease-termination-option.update', compact(
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
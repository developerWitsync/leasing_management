<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 01/01/19
 * Time: 12:24 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseRenewableOption;
use App\LeaseAssets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class LeaseRenewableOptionController extends Controller
{
    protected function validationRules(){
        return [
            'is_renewal_option_under_contract'   => 'required',
            'renewal_option_not_available_reason'   => 'required_if:is_renewal_option_under_contract,no',
            'is_reasonable_certainity_option' => 'required_if:is_renewal_option_under_contract,yes',
            'expected_lease_end_Date'   => 'required_if:is_reasonable_certainity_option,yes'
        ];
    }

    /**
     * create or update the lease asset renewal options for a single lease asset functionality
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
                    'link' => route('addlease.renewable.create', ['id' => $id]),
                    'title' => 'Create Renewal Option'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if($lease) {

                $asset = $lease->assets->first(); //since there will only be one lease asset per lease from now.

                if($asset->renewableOptionValue) {
                    $model  = $asset->renewableOptionValue;
                } else {
                    $model = new LeaseRenewableOption();
                }

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->is_reasonable_certainity_option == "yes") {
                        $data['expected_lease_end_Date'] = Carbon::parse($request->expected_lease_end_Date)->format('Y-m-d');
                    } else {
                        $data['expected_lease_end_Date']  = null;
                    }

                    $model->setRawAttributes($data);

                    if($model->save()){
                        // complete Step
                        confirmSteps($lease->id,'step7');
                        return redirect(route('addlease.renewable.index',['id' => $lease->id]))->with('status', 'Renewable Option has been added successfully.');
                    }
                }

                return view('lease.lease-renewable-option.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'breadcrumbs'
                ));

            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404, $e->getMessage());
        }
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
                'link' => route('addlease.renewable.index', ['id' => $id]),
                'title' => 'Renewal Option'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

        if($lease) {
            //Load the assets only for the assets where no selected at `exercise_termination_option_available` on lease termination
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereHas('terminationOption',  function($query){
                $query->where('lease_termination_option_available', '=', 'yes');
                $query->where('exercise_termination_option_available', '=', 'no');
            })->get();
       
            if(count($assets) > 0) {
                
            }
            else{
                return redirect(route('addlease.durationclassified.index', ['id' => $id]));
            }
           

            return view('lease.lease-renewable-option.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add renewable option value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.renewable.create', ['id' => $id]),
                    'title' => 'Create Renewal Option'
                ],
            ];

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseRenewableOption();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->is_reasonable_certainity_option == "yes") {
                        $data['expected_lease_end_Date'] = Carbon::parse($request->expected_lease_end_Date)->format('Y-m-d');
                    } else {
                        $data['expected_lease_end_Date']  = null;
                    }

                    $renewable_value = LeaseRenewableOption::create($data);

                    if($renewable_value){

                         // complete Step
                         confirmSteps($lease->id,'step7');
                        
                        return redirect(route('addlease.renewable.index',['id' => $lease->id]))->with('status', 'Renewable Option has been added successfully.');
                    }
                }
                return view('lease.lease-renewable-option.create', compact(
                    'model',
                    'lease',
                    'asset',
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
     * edit existing renewable option value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{

            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.renewable.update', ['id' => $id]),
                    'title' => 'Update Renewal Option'
                ],
            ];

            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseRenewableOption::query()->where('asset_id', '=', $id)->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;
                    if($request->is_reasonable_certainity_option == "yes") {
                        $data['expected_lease_end_Date'] = Carbon::parse($request->expected_lease_end_Date)->format('Y-m-d');
                    } else {
                        $data['expected_lease_end_Date']  = null;
                    }

                    $model->setRawAttributes($data);

                    if($model->save()){
                        return redirect(route('addlease.renewable.index',['id' => $lease->id]))->with('status', 'Renewable Option Value has been updated successfully.');
                    }
                }

              
                return view('lease.lease-renewable-option.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 03/01/19
 * Time: 9:24 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectLowValue;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class SelectLowValueController extends Controller
{
    protected function validationRules(){
        return [
            'undiscounted_lease_payment'   => 'required',
            'is_classify_under_low_value' => 'required'
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
                'link' => route('addlease.lowvalue.index',['id' => $id]),
                'title' => 'Select Low Value'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if($lease) {
            //Load the assets only for the assets where specific use  is equal to owe use(id=1) not availble for specifiec use(id=2)
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('specific_use', '=', '1')->get();
            if(count($assets) == 0) {
                return redirect(route('addlease.leaseasset.index', ['id' => $id]));
            }
            return view('lease.select-low-value.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add select low value details for an asset in NL-10
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = new LeaseSelectLowValue();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id']   = $asset->lease->id;
                    $data['asset_id']   = $asset->id;

                    $select_low_value = LeaseSelectLowValue::create($data);
                    if($select_low_value){
                        return redirect(route('addlease.lowvalue.index',['id' => $lease->id]))->with('status', 'Select Low Value has been added successfully.');
                    }
                }
                return view('lease.select-low-value.create', compact(
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
     * edit existing select low value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if($lease) {

                $model = LeaseSelectLowValue::query()->where('asset_id', '=', $id)->first();

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
                        return redirect(route('addlease.lowvalue.index',['id' => $lease->id]))->with('status', 'Select Low Value has been updated successfully.');
                    }
                }
                return view('lease.select-low-value.update', compact(
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
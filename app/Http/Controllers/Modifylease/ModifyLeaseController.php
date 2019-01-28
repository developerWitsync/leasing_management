<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 9/01/19
 * Time: 09:37 AM
 */

namespace App\Http\Controllers\Modifylease;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lease;
use App\ModifyLeaseApplication;
use App\LeaseModificationReason;
use Validator;


class ModifyLeaseController extends Controller
{
     protected function validationRules(){
        return [
            'valuation'   => 'required',
            'effective_from' => 'required_if:valuation,Subsequent Valuation',
            'reason'   => 'required'
        ];
    }
	/**
     * Render the table for all the leases
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('modifylease.index');
    }

    /**
     * fetch the lease details table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function fetchLeaseDetails(Request $request){
        try{
            if ($request->ajax()) {
              return datatables()->eloquent(Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '1')->with('leaseType'))->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
     /**
     * Create  modify lease application
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
             
            $lase_modification = LeaseModificationReason::query()->get();
            if($lease) {

                $model = Lease::query()->where('id', '=', $id)->first();
                

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if($validator->fails()){
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id']   = $id;
                   if($request->effective_from)
                   {
                     $data['effective_from'] = date('Y-m-d', strtotime($request->effective_from));
                   }
                    $modify_lease = ModifyLeaseApplication::create($data);

                    $data1['status'] = '0';
                    $model->setRawAttributes($data1);
                    $model->save();

                    return redirect(route('modifylease.create',['id' => $id]))->with('status', 'Modify Lease has been Created successfully.');
                    
                }
                return view('modifylease.create',compact('lease','lase_modification'));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
           abort(404);
        }
    }
 }

<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LeaseLockYear;
use Validator;
use Session;

class LeaseLockYearController extends Controller
{
      /**
     * Add   Lease Lock Year for the current authenticated user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeaseLockYear(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'audit_year1_ended_on' => 'required|date',
                    'audit_year2_ended_on' => 'required|date'
              ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeaseLockYear::create([
                    'business_account_id'   =>  auth()->user()->id,
                    'audit_year1_ended_on' => date('Y-m-d', strtotime($request->audit_year1_ended_on)),
                    'audit_year2_ended_on' => date('Y-m-d', strtotime($request->audit_year2_ended_on))
                ]);
                if($model){
                    return redirect()->back()->with('status', 'Lease Lock Year has been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            abort(404,$e->getMessage());
        }
    }
    /**
     * update an Lease Lock Year for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeaseLockYear($id, Request $request){
        try{
            if($request->ajax()){
                $lease_lock_year = LeaseLockYear::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                //dd($lease_lock_year);
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                    'audit_year1_ended_on1' => 'required|date',
                    'audit_year2_ended_on1' => 'required|date'
              		]);
                     
                    
                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
                    $lease_lock_year->business_account_id = auth()->user()->id;
                    $lease_lock_year->audit_year1_ended_on = date('Y-m-d', strtotime($request->audit_year1_ended_on1));
                    $lease_lock_year->audit_year2_ended_on = date('Y-m-d', strtotime($request->audit_year2_ended_on1));
                 // dd($lease_lock_year);
                    $lease_lock_year->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Lease Lock Year has been Updated successfully.'
                    ]);
                }

                return view('settings.general._edit_lease_lock_year',compact(
                    'lease_lock_year'
                ));
            }
        } catch (\Exception $e){
           dd($e->getMessage());
            abort(404);
        }
    }

    /**
     * Delete aan Number of Lease Payments for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseLockYear($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_lock_year = LeaseLockYear::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($lease_lock_year) {
                    $lease_lock_year->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }
}

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
     * Add title of Lease Lock  Year
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeaseLockYear(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'start_date' => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeaseLockYear::create([
                    'business_account_id'   =>  auth()->user()->id,
                    'start_date' => date('Y-m-d', strtotime($request->start_date))
                 ]);

                if($model){
                    return redirect()->back()->with('status', 'Lease Lock Year been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
             abort(404);
        }
    }


    /**
     * update Title of Lease Lock Year
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeaseLockYear($id, Request $request){
        try{
            if($request->ajax()){
                $lease_lock_year = LeaseLockYear::query()->where('id', $id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'start_date' => ['required',]
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_lock_year->start_date = date('Y-m-d', strtotime($request->start_date));
                    $lease_lock_year->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.general._edit_lease_lock_year', compact('lease_lock_year'));
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }

    /**
     * Delete a Title of Lease Lock Year
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseLockYear($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_lock_year = LeaseLockYear::query()->where('id', $id);
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
    /**
     * updates the status of the Lease Lock year and returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request){
       
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'id'    => 'required'
                ]);

                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $lease_lock_year = LeaseLockYear::query()->findOrFail($request->id);
                $lease_lock_year->status = $request->status;
                $lease_lock_year->save();
                return response()->json(['status' => true], 200);
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
         // abort('404');
            dd($e);
        }
    }
}

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
     * lock or unlock a particular year with a start date
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request){
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'start_date'    => 'required|date'
                ]);

                if($validator->fails()) {
                    Session::flash('error', 'Please select a date for locking.');
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $lease_lock_year = LeaseLockYear::query()
                    ->whereYear('start_date', '=', date('Y', strtotime($request->start_date)))
                    ->first();

                if(is_null($lease_lock_year)){
                    $lease_lock_year = new LeaseLockYear();
                }
                $lease_lock_year->start_date = date('Y-m-d', strtotime($request->start_date));
                $lease_lock_year->status = $request->status;
                $lease_lock_year->business_account_id = getParentDetails()->id;
                $lease_lock_year->save();
                Session::flash('status', 'Audit year data has been saved successfully.');
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

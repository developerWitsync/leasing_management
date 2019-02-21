<?php

namespace App\Http\Controllers\Lease;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function checkLockPeriodDate(Request $request)
    {
       try{
            if($request->ajax()){
                $modify_date = date('Y-m-d',strtotime($request->date));
                $lock_date = getLockYearDetails();
                $lock_start_date = $lock_date->start_date;
                 if($lock_start_date >=$modify_date){
                     return response()->json(['status' => 'This period is already locked as per your settings, please make sure that you select a date on or after  '. $lock_start_date], 200);
                 }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
         abort('404');
            //dd($e);
        }

    }
}
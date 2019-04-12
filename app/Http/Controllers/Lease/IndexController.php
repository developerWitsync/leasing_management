<?php

namespace App\Http\Controllers\Lease;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    /**
     * check for the lock period as per the settings
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function checkLockPeriodDate(Request $request)
    {
        try {
            if ($request->ajax()) {
                $modify_date = date('Y-m-d', strtotime($request->date));
                $locked = getLockYearDetails($modify_date);
                if ($locked) {
                    return response()->json(['status' => false, 'message' => 'This period is already locked as per your settings, please make sure that you select a date on or after  ' . $locked->start_date], 200);
                } else {
                    return response()->json(['status' => true], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
            abort('404');
        }
    }
}

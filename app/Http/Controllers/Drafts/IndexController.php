<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 2/1/19
 * Time: 11:03 AM
 */
namespace App\Http\Controllers\Drafts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lease;

// Using Eloquent


class IndexController extends Controller
{
	/**
     * Render the table for all the leases
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
            return view('drafts.index');
    }

    /**
     * fetch the lease details table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function fetchLeaseDetails(Request $request){
        try{
            if ($request->ajax()) {
            	return datatables()->eloquent(Lease::query())->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

     /**
     * deletes a particular lease from the database
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseDetails($id, Request $request) {
        try{
            if($request->ajax()) {
                $lease = Lease::query()->find($id);
                if($lease) {
                    $lease->delete();
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }
   }

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
use App\LeaseAssets;
// Using Eloquent


class IndexController extends Controller
{
    public $breadcrumbs;
    public function __construct()
    {
        $this->breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Dashboard'
            ],
            [
                'link' => route('drafts.index'),
                'title' => 'Drafts'
            ]
        ];
    }
	/**
     * Render the table for all the leases
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $breadcrumbs = $this->breadcrumbs; 
        return view('drafts.index',compact('breadcrumbs'));
    }

    /**
     * fetch the lease details table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function fetchLeaseDetails(Request $request){
        try{
            if ($request->ajax()) {
            	return datatables()->eloquent(Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('status', '=', '0')->with('assets'))->toJson();
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
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id',$id)->first();
                if($lease){
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

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

class IndexController extends Controller
{
    public function index(){
            return view('drafts.index');
    }

    public function fetchLeaseDetails(Request $request){
        try{
            if ($request->ajax()) {
                $lease = Lease::query()->get();
                $lease1= collect($lease);
                // dd($lease);

                return datatables()->eloquent($lease1)->toJson();
                // return datatables()->eloquent($lease)
                //     ->filter(function ($query) use ($request){
                //         if ($request->has('search') && trim($request->search["value"])!="") {
                //             $query->where('name', 'like', "%" . $request->search["value"] . "%");
                //         }
                //     })
                    // ->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
   }

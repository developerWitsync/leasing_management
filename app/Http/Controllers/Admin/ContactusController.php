<?php
/**
 * Created by PhpStorm.
 * User: Jyoti
 * Date: 16/12/18
 * Time: 5:40 PM
 */

namespace App\Http\Controllers\Admin;


use App\ContactUs;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactusController extends Controller
{
    public function index(){
        return view('admin.contactus.index');
    }

    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = ContactUs::query();

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {

                                $query->where('first_name', 'like', "%" . $request->search["value"] . "%")->orwhere('last_name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('created_at', function($data){
                        return date('jS F Y h:i a', strtotime($data->created_at));
                    })

                    ->addColumn('name', function($data){
                        return ucwords($data->first_name." ".$data->last_name);
                    }) 
                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            return redirect()->back();
        }
    }
  
    /**
     * fetches the template and renders the view with the preview
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function preview($id, Request $request){
        $template = ContactUs::query()->where("id", '=', $id)->first();
        return view('admin.contactus.preview',['template'=> $template]);
    }
}

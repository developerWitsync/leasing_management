<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 15/02/19
 * Time: 03:22 AM
 */

namespace App\Http\Controllers\Admin;


use App\Cms;
use Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function index(){
        return view('admin.cms.index');
    }

    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = Cms::query();

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('title', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('created_at', function($data){
                        return date('jS F Y h:i a', strtotime($data->created_at));
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
     * Update a particular Cms and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $cms = Cms::query()->findOrFail($id);
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'title' => 'required|unique:cms,title,'.$id.',id',
                    'description' => 'required',
                    'meta_title' => 'required',
                    'meta_description' => 'required',
                    'meta_keyword' => 'required',
                    'status'    => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $request->request->add(['slug' => str_slug($request->title)]);

                $cms->setRawAttributes($request->except('_token'));
                $cms->save();
                return redirect(route('admin.cms.index'))->with('success', 'Cms has been updated successfully.');
            }
            return view('admin.cms.update', compact('cms'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * add a new cms to the database
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'title' => 'required|unique:cms,title',
                    'description' => 'required',
                    'meta_title' => 'required',
                    'meta_description' => 'required',
                    'meta_keyword' => 'required',
                    'status'    => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $request->request->add(['slug' => str_slug($request->title)]);

                Cms::create($request->except('_token'));
                return redirect(route('admin.cms.index'))->with('success', 'Cms has been added successfully.');
            }
            return view('admin.cms.create');
        } catch (\Exception $e) {
            dd($e->getMessage());
            abort(404);
        }
    }

    /**
     * deletes a particular cms from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        try{
            if($request->ajax()) {
                $country = Cms::query()->findOrFail($id);
                if($country) {
                    $country->delete();
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

    /**
     * updates the status of the cms and returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request){
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'id'    => 'required|exists:cms,id'
                ]);

                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $country = Cms::query()->findOrFail($request->id);
                $country->status = $request->status;
                $country->save();
                return response()->json(['status' => true], 200);
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }
}
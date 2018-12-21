<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 13/12/18
 * Time: 10:47 AM
 */

namespace App\Http\Controllers\Admin;


use App\Countries;
use Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index(){
        return view('admin.countries.index');
    }

    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = Countries::query();

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
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
     * Update a particular country and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $country = Countries::query()->findOrFail($id);
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'name' => 'required|unique:countries,name,'.$id.',id',
                    'iso_code' => 'required|unique:countries,iso_code,'.$id.',id',
                    'status'    => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $country->setRawAttributes($request->except('_token'));
                $country->save();
                return redirect(route('admin.countries.index'))->with('success', 'Country has been updated successfully.');
            }
            return view('admin.countries.update', compact('country'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * add a new country to the database
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'name' => 'required|unique:countries,name',
                    'iso_code' => 'required|unique:countries,iso_code',
                    'status'    => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                Countries::create($request->except('_token'));
                return redirect(route('admin.countries.index'))->with('success', 'Country has been added successfully.');
            }
            return view('admin.countries.create');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * deletes a particular country from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        try{
            if($request->ajax()) {
                $country = Countries::query()->findOrFail($id);
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
     * updates the status of the country and returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request){
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'id'    => 'required|exists:countries,id'
                ]);

                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $country = Countries::query()->findOrFail($request->id);
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
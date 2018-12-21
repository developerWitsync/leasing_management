<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 13/11/18
 * Time: 3:56 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Countries;
use App\Currencies;
use App\IndustryTypes;
use Validator;
use Image;
use File;

class UserController extends Controller
{
    public function index(){
        return view('admin.users.index');
    }

    /**
     * Fetches and returns the json to be rendered on the datatable.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = User::query()->where('type', '=','0');

                return datatables()->eloquent($model)

                    ->addColumn('full_name',function($data){
                        return ucwords($data->first_name." ".$data->last_name);
                    })

                    ->addColumn('profile_pic',function($data){
                            $userImagePath = getUserProfileImageSrc($data->id, $data->profile_pic,true);
                            return $userImagePath;
                    })

                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->whereRaw('concat(first_name," ",last_name) Like "%'.$request->search["value"].'%"' );
                        }
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
     * deletes a particular user from the database, please note that there is no soft delete for the users, and hence all the details for the user will be removed from the system
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        try{
            if($request->ajax()) {
                $user = User::query()->find($id);
                if($user) {
                    $user->delete();
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
     * if the request method is get than in that case renders the form for the create user profile.
     * if the request method is post than in that case create the user profile and save the changes to the database as well.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function add( Request $request){
        try{
            $countries = Countries::query()->where('status','=', '1')->where('trash', '=', '0')->get();
            $industry_types = IndustryTypes::query()->where('status', '=', '1')->get();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
             return view('admin.users.add-user',compact('countries', 'industry_types', 'currencies'));
        } catch (\Exception $e) {
            dd($e);
            abort('404');
        }
    }

    /**
     * if the request method is get than in that case renders the form for the update user profile.
     * if the request method is post than in that case update the user profile and save the changes to the database as well.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        try{
            $user = User::query()->find($id);
            if($user) {
                if($request->isMethod('post')) {

                    $file_name = '';

                    $request->request->add(['password' => ($request->has('password') && trim($request->password)!= "")?bcrypt($request->password):$user->password]);

                    $validator = Validator::make($request->all(), [
                        'first_name'        => 'required',
                        'last_name'         => 'required',
                        'email'             =>  "required|unique:users,email,{$id},id",
                        'mobile'            => "required|unique:users,mobile,{$id},id",
                        'address'           => 'required',
                        'city'              => 'required',
                        'country'           => 'required',
                        'dob'               => 'required|date',
                        'state'             => 'required',
                        'security_question' => 'required',
                        'security_answer'   => 'required',
                        'profile_pic'       => 'image'
                    ]);

                    if($validator->fails()) {
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                    }

                    $dob = Carbon::parse($request->dob)->format('Y-m-d');

                    $request->request->add(['dob' => $dob]);

                    $originalImage= $request->file('profile_pic');

                    if($originalImage) {

                        $originalPath   = public_path()."/user/{$id}/profile_pic/";

                        $file_name = uploadImage($originalImage, $originalPath, true, true);

                    }

                    $user->setRawAttributes($request->except("_token"));

                    if($file_name) {
                        $user->setAttribute('profile_pic', $file_name);
                    }

                    $user->save();

                    return redirect(route("admin.users.index"))->with('success', 'User details has been updated successfully.');
                }


                return view('admin.users.update', compact('user'));
            } else {
                abort('404');
            }
        } catch (\Exception $e) {
            dd($e);
            abort('404');
        }
    }

    /**
     * updates the verified status of the user and  returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request){
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'is_verified' => 'required',
                    'id'    => 'required|exists:users,id'
                ]);

                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $question = User::query()->find($request->id);
                $question->is_verified = $request->is_verified;
                $question->save();
                return response()->json(['status' => true], 200);
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }
}
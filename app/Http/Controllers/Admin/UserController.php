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
use App\AccountingStandards;
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

                $model = User::query()->where('type', '=','0')->where('parent_id', '=','0');
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
            $states = [];
            $user   =  new User();
            if($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'country' => 'required|exists:countries,name',
                    'state' => 'required_if:country,India|exists:states,state_name|nullable',
                    'gstin' => 'required_if:coun0try,India|min:15|nullable',
                    'username' => 'required|string|max:255|unique:users',
                    'password' => 'min:6|confirmed|nullable',
                    'applicable_gaap' => 'required',
                    'legal_entity_name' => 'required',
                    'authorised_person_name' => 'required|string|max:255',
                    'authorised_person_dob' => 'required|date|before:-18 years',
                    'gender' => 'required',
                    'authorised_person_designation' => 'required',
                    'email' => 'required|string|email|max:255|unique:users'
                ], [
                    'authorised_person_dob.before' => 'The authorised person must be atleast 18 years old.'
                ]);

                if($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $data = $request->except('_token', 'password_confirmation');
                $data['raw_password']   = $request->password;
                $data['password']   = bcrypt($request->password); 
                $data['authorised_person_dob'] = date('Y-m-d', strtotime($request->authorised_person_dob));
                $data['parent_id'] = 0;
                $data['email_verification_code'] = md5(time());

                $user->setRawAttributes($data);
                if($user->save()){
                     return redirect(route("admin.users.index"))->with('success', 'User has been added successfully.');
                }

            }
            $accounting_standards = AccountingStandards::query()->get();
            return view('admin.users.add-user',compact(
                'countries',
                'industry_types',
                'currencies',
                'user',
                'states',
                'accounting_standards'
            ));
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
       
            $countries = Countries::query()->where('status','=', '1')->where('trash', '=', '0')->get();
            $industry_types = IndustryTypes::query()->where('status', '=', '1')->get();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            $user = User::query()->findOrFail($id);
            $user_country = Countries::query()->where('name', '=', $user->country)->first();
            $states = $user_country->states()->get();
            if($user) {
                if($request->isMethod('post')) {

                    $validator = Validator::make($request->all(), [
                        'country' => 'required|exists:countries,name',
                        'state' => 'required_if:country,India|exists:states,state_name|nullable',
                        'gstin' => 'required_if:coun0try,India|min:15|nullable',
                        'username' => 'required|string|max:255|unique:users,id,'.$user->id,
                        'password' => 'min:6|confirmed|nullable',
                        'applicable_gaap' => 'required',
                        'legal_entity_name' => 'required',
                        'authorised_person_name' => 'required|string|max:255',
                        'authorised_person_dob' => 'required|date|before:-18 years',
                        'gender' => 'required',
                        'authorised_person_designation' => 'required',
                        'email' => 'required|string|email|max:255|unique:users,id,'.$user->id
                    ], [
                        'authorised_person_dob.before' => 'The authorised person must be atleast 18 years old.'
                    ]);

                    if($validator->fails()) {
                        dd($validator->errors());
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                    }

                    $request->request->add(['password' => ($request->has('password') && trim($request->password)!= "")?bcrypt($request->password):$user->password , 'authorised_person_dob' =>  date('Y-m-d', strtotime($request->authorised_person_dob))]);

                    $user->setRawAttributes($request->except(['_token', 'password_confirmation']));
                    $user->save();
                    return redirect(route("admin.users.index"))->with('success', 'User details has been updated successfully.');
                    }
                $accounting_standards = AccountingStandards::query()->get();
                return view('admin.users.update', compact('user','countries', 'industry_types', 'currencies', 'states', 'accounting_standards'));
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
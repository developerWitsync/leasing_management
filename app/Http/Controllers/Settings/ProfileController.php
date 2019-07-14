<?php
/**
 * Created by PhpStorm.
 * User: Jyoti
 * Date: 19/12/18
 * Time: 3:00 PM
 */

namespace App\Http\Controllers\Settings;

use App\States;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\UserCreateConfirmation;
use App\Countries;
use App\IndustryTypes;
use Validator;
use App\User;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\RoleUser;

class ProfileController extends Controller
{

    /**
     * Update a particular User and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try{
            $id = auth()->user()->id;
            $breadcrumbs = [
                [
                    'link' => route('settings.index'),
                    'title' => 'Settings'
                ],
                [
                    'link' => route('settings.profile.index'),
                    'title' => 'My Profile'
                ],
            ];
            $user = User::query()->findOrFail($id);
            $countries = Countries::query()->where('status', '=', '1')->get();
            $states = [];
            if($user->country == "India"){
                $country = Countries::query()->where('name', '=', $user->country)->first();
                $states = $country->states;
            }
            if ($request->isMethod('post')) {

                $rules = [
                    'authorised_person_name' => 'required|string|max:255',
                    'authorised_person_dob' => 'required|date',
                    'gender' => 'required',
                    'authorised_person_designation' => 'required',
                    //'username' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'nullable|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?!.*[\s])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'phone' => 'required'
                ];

                if($user->parent_id == 0){
                    $rules['address'] = 'required';
                    $rules['state'] = 'required_if:country,India|exists:states,state_name|nullable';
                    $rules['gstin'] =   'required_if:country,India|min:15|nullable';
                    $rules['legal_entity_name'] = 'required';
                    $rules['date_of_incorporation'] = 'required|date';
                    $rules['certificates'] = config('settings.file_size_limits.file_rule');
                }

                $messages = [
                    'password.regex' => 'Password must have one letter, one capital letter, one number as well.'
                ];

                $validator = Validator::make($request->except('_token'),$rules,$messages);

                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $userdata = User::findOrFail($id);
                $userdata->setRawAttributes($request->except('_token', 'certificates', 'password_confirmation'));
                $userdata->authorised_person_name = $request->authorised_person_name;
                $userdata->authorised_person_designation = $request->authorised_person_designation;
                //$userdata->username = $request->username;
                $userdata->phone = $request->phone;
                $userdata->email = $request->email;
                $userdata->type = '0';
                $userdata->password = bcrypt($request->password);
                $userdata->authorised_person_dob = date('Y-m-d', strtotime($request->authorised_person_dob));
                if($user->parent_id == 0){
                    $userdata->date_of_incorporation = date('Y-m-d', strtotime($request->date_of_incorporation));
                }

                if($request->hasFile('certificates')){
                    $file = $request->file('certificates');
                    $uniqueFileName = uniqid() . $file->getClientOriginalName();
                    $request->file('certificates')->move('uploads', $uniqueFileName);
                    $userdata->certificates = $uniqueFileName;
                }


                $userdata->save();
                if ($userdata) {
                    \Mail::to($userdata)->queue(new UserCreateConfirmation($userdata));
                    return redirect(route('settings.profile.index'))->with('status', 'Profile has been updated successfully.');
                }

            }
            return view('settings.profile.index', ['breadcrumbs' => $breadcrumbs, 'user' => $user, 'id' => $id, 'countries' => $countries, 'states' => $states]);
        } catch (\Exception $e){
            dd($e);
        }

    }

}
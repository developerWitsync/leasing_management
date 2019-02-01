<?php
/**
 * Created by PhpStorm.
 * User: Jyoti
 * Date: 19/12/18
 * Time: 3:00 PM
 */

namespace App\Http\Controllers\Settings;

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
    public function index(Request $request){
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
            if($request->isMethod('post')) {
               $validator = Validator::make($request->except('_token'), [
                    'authorised_person_name' => 'required|string|max:255',
                    'authorised_person_dob'     => 'required|date',
                    'gender'    => 'required',
                    'authorised_person_designation' => 'required',
                    'username' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'nullable|min:6|confirmed',
                    'phone' => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $userdata = User::findOrFail($id);
                $userdata->authorised_person_name = $request->authorised_person_name;
                $userdata->authorised_person_designation = $request->authorised_person_designation;
                $userdata->username = $request->username;
                $userdata->phone = $request->phone;
                $userdata->email = $request->email;
                $userdata->type = '0';
                $userdata->password = bcrypt($request->password);
                $userdata->authorised_person_dob = date('Y-m-d', strtotime($request->authorised_person_dob));
                $userdata->parent_id = auth()->user()->id;
                $userdata->save();
                if($userdata){
                    \Mail::to($userdata)->queue(new UserCreateConfirmation($userdata));
                    return redirect(route('settings.profile.index'))->with('status', 'Profile has been updated successfully.');
                }

         }
            return view('settings.profile.index', ['breadcrumbs'=> $breadcrumbs,'user'=>$user, 'id'=>$id]);
    }

}
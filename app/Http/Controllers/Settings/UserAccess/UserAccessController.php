<?php
/**
 * Created by PhpStorm.
 * User: Jyoti
 * Date: 19/12/18
 * Time: 3:00 PM
 */

namespace App\Http\Controllers\Settings\UserAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\UserCreateConfirmation;
use Validator;
use App\User;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\RoleUser;

class UserAccessController extends Controller
{
    public $breadcrumbs;
    public function __construct()
    {
        $this->breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.useraccess'),
                'title' => 'User Access Settings'
            ],
            [
                'link' => route('settings.user'),
                'title' => 'Manage User'
            ],
        ];
    }

    public function index(){
       return view('settings.useraccess.index', ['breadcrumbs'=> $this->breadcrumbs]);
    }
    /**
     * Fetches and returns the user 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function listing(){
         return view('settings.useraccess.user.index', ['breadcrumbs'=> $this->breadcrumbs]);
    }
    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = User::query()->where('parent_id',auth()->user()->id);
                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('roles', function($data){
                      return implode(' | ', $data->roles->pluck('name')->toArray());
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
     * add a new User to the database
     * @param Request $request
     */
    public function create(Request $request){
        if($request->isMethod('post')) {
            $validator = Validator::make($request->except('_token'), [
                'authorised_person_name' => 'required|string|max:255',
                'authorised_person_dob'     => 'required|date',
                'gender'    => 'required',
                'authorised_person_designation' => 'required',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'phone' => 'required'
            ]);
            if($validator->fails()){
                return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
            }

            $data = $request->except('_token');
            $data['type'] = '0';
            $data['password']   = bcrypt($request->password); 
            $data['authorised_person_dob'] = date('Y-m-d', strtotime($request->authorised_person_dob));
            $data['email_verification_code'] = md5(time());
            $data['is_verified'] = '0';
            $user = User::create($data);
            if($user){
              \Mail::to($user)->queue(new UserCreateConfirmation($user));
              return redirect(route('settings.user'))->with('status', 'User has been added successfully.');
            }
        }
        return view('settings.useraccess.user.create',['breadcrumbs'=> $this->breadcrumbs]);
    }
    /**
     * Update a particular User and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
          $user = User::query()->findOrFail($id);
            if($request->isMethod('post')) {
               $validator = Validator::make($request->except('_token'), [
                    'authorised_person_name' => 'required|string|max:255',
                    'authorised_person_dob'     => 'required|date',
                    'gender'    => 'required',
                    'authorised_person_designation' => 'required',
                    'username' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'required|string|min:6|confirmed',
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
                $userdata->type -'0';
                $userdata->password =bcrypt($request->password);
                $userdata->authorised_person_dob=date('Y-m-d', strtotime($request->authorised_person_dob));
                $userdata->parent_id=auth()->user()->id;
                $userdata->save($request->except('_token'));

                if($userdata){
                \Mail::to($userdata)->queue(new UserCreateConfirmation($userdata));
                return redirect(route('settings.user'))->with('status', 'User has been updated successfully.');
                }

         }
            return view('settings.useraccess.user.update', ['breadcrumbs'=> $this->breadcrumbs,'user'=>$user]);
    }

    /**
     * deletes a particular user from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
         try{
             if($request->ajax()) {
                $user = User::query()->findOrFail($id);
                
                if($user) {
                    $user->delete();
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return response()->json(['error'    =>  '']);
            }
        } catch (\Exception $e) {
            return response()->json(['error'    =>  $e->getMessage()]);
        }
    }
    /**
     * AssignPermissionToRole User will assign permission to role
     * @param string $value [AssignPermission]
     */
    public function assignPermissionToRole($id,Request $request)
    {
      $role=Role::where('id',$id)->get();
      $PermissionRole= PermissionRole::where('role_id',$id)->get();
      $permission = Permission::all();
      $PermissionRoleId = $PermissionRole->pluck('permission_id')->toArray();
      $role =Role::all();
      if($request->isMethod('post')) {
             $validator = Validator::make($request->except('_token'), [
                'permission'  =>  'required',
                
                ], [
                'permission.required'  =>  'Please select at least one Permission',
            ]);
            if($validator->fails()){
                return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
            }
            $role = Role::query()->find($request->role);
            $role->perms()->sync([]);
            foreach ($request->permission as $key => $permission) {
              if (!$role->perms()->get()->contains('id', $permission)) {
                $role->attachPermission($permission);
              }
            }
            return redirect('settings/user-access/role')->with('status', 'Selected permissions have been assigned to the role.');
      }
      return view('settings.useraccess.user.permission_to_role', ['breadcrumbs'=> $this->breadcrumbs,'role'=>$role,'permission'=>$permission,'PermissionRoleId'=>$PermissionRoleId]);
    }
    /**
     * [AssignRoleToUser User will assign role to permission]
     * @param string $value [assign role]
    */
    public function assignRoleToUser($id,Request $request)
    {
      $role = Role::all();
      $RoleUser =  RoleUser::where('user_id',$id)->get();
      $RoleUserId = $RoleUser->pluck('role_id')->toArray();
      $user=User::where('id',$id)->get();
      if($request->isMethod('post')) {
            $validator = Validator::make($request->except('_token'), [
                'role'  =>  'required',
                
                ], [
                'role.required'  =>  'Please select at least one role',
            ]);
              if($validator->fails()){
                 
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }
                $user = User::query()->find($request->user);
                $user->roles()->sync([]);
                foreach ($request->role as $key => $role) {
                  if (!$user->roles()->get()->contains('id', $role)) {
                   $user->attachRoles($request->role);
                  }
                }
              return redirect('settings/user-access/listing')->with('status', 'Selected Users have been assigned to the role.');  
      }
      return view('settings.useraccess.user.role_to_user', ['breadcrumbs'=> $this->breadcrumbs,'role'=>$role,'user'=>$user,'RoleUserId'=>$RoleUserId]);
    }

}
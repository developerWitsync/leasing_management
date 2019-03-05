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

    public function index()
    {
        return view('settings.useraccess.index', ['breadcrumbs' => $this->breadcrumbs]);
    }

    /**
     * Fetches and returns the user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function listing()
    {
        return view('settings.useraccess.user.index', ['breadcrumbs' => $this->breadcrumbs]);
    }

    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request)
    {
        try {
            if ($request->ajax()) {

                $model = User::query()->where('parent_id', auth()->user()->id);
                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && trim($request->search["value"]) != "") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('roles', function ($data) {
                        return implode(' | ', $data->roles->pluck('name')->toArray());
                    })
                    ->addColumn('created_at', function ($data) {
                        return date('jS F Y h:i a', strtotime($data->created_at));
                    })
                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * add sub users for the current logged in business account...
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        try{

            $user = new User();
            $roles = Role::query()->whereIn('business_account_id', getDependentUserIds())->get();
            $assignedRole = [];
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'authorised_person_name' => 'required|string|max:255',
                    'authorised_person_dob' => 'required|date',
                    'gender' => 'required',
                    'authorised_person_designation' => 'required',
                    'username' => 'required|string|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                    'role' =>'required|exists:roles,id'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $data = $request->except('_token');
                $data['type'] = '0';
                $data['raw_password'] = $request->password;
                $data['password'] = bcrypt($request->password);
                $data['authorised_person_dob'] = date('Y-m-d', strtotime($request->authorised_person_dob));
                $data['email_verification_code'] = md5(time());
                $data['is_verified'] = '0';
                $data['parent_id'] = auth()->user()->id;
                $user = User::create($data);
                if ($user) {
                    $user = User::query()->find($user->id);
                    $user->roles()->sync([]);
                    if (!$user->roles()->get()->contains('id', $request->role)) {
                        $user->attachRole($request->role);
                    }
                    return redirect(route('settings.useraccess'))->with('status', 'User has been added successfully.');
                }
            }

            return view('settings.useraccess.user.create', [
                'breadcrumbs' => $this->breadcrumbs,
                'user' => $user,
                'roles' => $roles,
                'assignedRole' => $assignedRole
            ]);

        } catch (\Exception $e){
            dd($e);
        }

    }

    /**
     * Update a particular User and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        $user = User::query()->findOrFail($id);
        $roles = Role::query()->whereIn('business_account_id', getDependentUserIds())->get();
        $assignedRole = RoleUser::where('user_id', $id)->get()->pluck('role_id')->toArray();
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->except('_token'), [
                'authorised_person_name' => 'required|string|max:255',
                'authorised_person_dob' => 'required|date',
                'gender' => 'required',
                'authorised_person_designation' => 'required',
                'username' => 'required|string|max:255|unique:users,id,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,id,' . $user->id,
                'password' => 'string|min:6|confirmed|nullable',
                'role' =>'required|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
            }

            $userdata = User::findOrFail($id);
            $userdata->authorised_person_name = $request->authorised_person_name;
            $userdata->authorised_person_designation = $request->authorised_person_designation;
            $userdata->username = $request->username;
            $userdata->phone = $request->phone;
            $userdata->email = $request->email;
            $userdata->type = '0';
            if ($request->password != "") {
                $userdata->password = bcrypt($request->password);
            }
            $userdata->authorised_person_dob = date('Y-m-d', strtotime($request->authorised_person_dob));
            $userdata->parent_id = auth()->user()->id;
            $userdata->save();
            if ($userdata) {

                $user = User::query()->find($id);
                $user->roles()->sync([]);
                if (!$user->roles()->get()->contains('id', $request->role)) {
                    $user->attachRole($request->role);
                }

                return redirect(route('settings.useraccess'))->with('status', 'User has been updated successfully.');
            }
        }

        return view('settings.useraccess.user.update', [
            'breadcrumbs' => $this->breadcrumbs,
            'user' => $user,
            'roles' => $roles,
            'assignedRole' => $assignedRole
        ]);
    }

    /**
     * deletes a particular user from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $user = User::query()->findOrFail($id);

                if ($user) {
                    $user->delete();
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return response()->json(['error' => '']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * updates the verified status of the user and  returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'is_verified' => 'required',
                    'id' => 'required|exists:users,id'
                ]);

                if ($validator->fails()) {
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
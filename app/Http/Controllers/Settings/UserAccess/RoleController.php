<?php

namespace App\Http\Controllers\Settings\UserAccess;

use App\Permission;
use App\PermissionRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Role;

class RoleController extends Controller
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
                'link' => route('settings.role'),
                'title' => 'Manage Roles'
            ],
        ];
    }

    /**
     * Renders the view to list all the roles for the logged in
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $role = Role::all();
        return view('settings.useraccess.role.index', ['breadcrumbs' => $this->breadcrumbs, 'role' => $role]);
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

                $model = Role::query()->with('perms')->whereIn('business_account_id', getDependentUserIds());

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && trim($request->search["value"]) != "") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('permissions', function ($data) {
                        return implode(' | ', $data->perms->pluck('display_name')->toArray());
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
     * add a new Role to the database
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $model = new Role();
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->except('_token'), [
                'display_name' => 'required|unique:roles',
                'permission' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
            }

            $request->request->add(['business_account_id' => auth()->user()->id, 'name' => str_slug($request->display_name)]);
            $role = Role::create($request->except('_token'));
            if ($role) {

                $role = Role::query()->find($role->id);
                $role->perms()->sync([]);
                foreach ($request->permission as $key => $permission) {
                    if (!$role->perms()->get()->contains('id', $permission)) {
                        $role->attachPermission($permission);
                    }
                }

                return redirect(route('settings.useraccess'))->with('status', 'Role has been added successfully.');
            }
        }
        $permissions = Permission::all();
        return view('settings.useraccess.role.create', [
            'breadcrumbs' => $this->breadcrumbs,
            'model' => $model,
            'permissions' => $permissions,
            'assignedPermissions' => []
        ]);
    }

    /**
     * Update a particular role and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        try{
            $model = Role::query()->findOrFail($id);
            $assignedPermissions = PermissionRole::where('role_id',$id)->get()->pluck('permission_id')->toArray();
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'display_name' => 'required',
                    'permission' => 'required'
                ], [
                    'permission.required' => 'Please select at lease one permission.'
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $request->request->add(['name' => str_slug($request->display_name)]);

                $model->setRawAttributes($request->except('_token', 'permission'));

                $model->save();

                $role = Role::query()->find($id);
                $role->perms()->sync([]);
                foreach ($request->permission as $key => $permission) {
                    if (!$role->perms()->get()->contains('id', $permission)) {
                        $role->attachPermission($permission);
                    }
                }

                return redirect(route('settings.useraccess'))->with('status', 'Role has been updated successfully.');
            }
            $permissions = Permission::all();
            return view('settings.useraccess.role.update', [
                'breadcrumbs' => $this->breadcrumbs,
                'model' => $model,
                'permissions' => $permissions,
                'assignedPermissions' => $assignedPermissions
            ]);
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * deletes a particular role from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request)
    {
        try {
            if ($request->ajax()) {

                $role = Role::query()->findOrFail($id);
                if ($role) {
                    $role->delete();
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

}

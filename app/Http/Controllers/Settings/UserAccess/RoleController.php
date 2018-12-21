<?php

namespace App\Http\Controllers\Settings\UserAccess;

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
   public function index(){
        
        $role = Role::all();
        return view('settings.useraccess.role.index', ['breadcrumbs'=> $this->breadcrumbs,'role'=>$role]);
    }
    /**
     * Fetches and returns the json to be rendered on the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = Role::query()->with('perms');

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->addColumn('permissions', function($data){
                      return implode(' | ', $data->perms->pluck('name')->toArray());
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
     * add a new Role to the database
     * @param Request $request
     */
    public function create(Request $request){
	 	if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), [
                    'name' => 'required|unique:roles',
                    'display_name' => 'required|unique:roles',
                ]);

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }
                $request->request->add(['business_account_id' => auth()->user()->id]);
                $role= Role::create($request->except('_token'));
                if($role){
                return redirect(route('settings.role'))->with('status', 'Role has been added successfully.');
                }
        }
        return view('settings.useraccess.role.create',['breadcrumbs'=> $this->breadcrumbs]);
    }
    /**
     * Update a particular role and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
          $role = Role::query()->findOrFail($id);
            if($request->isMethod('post')) {
               $validator = Validator::make($request->except('_token'), [
                    'name' => 'required|unique:roles,name,'.$id,
                    'display_name' => 'required',
                ]);

				if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

				$role->setRawAttributes($request->except('_token'));

                $role->save();

                return redirect(route('settings.role'))->with('status', 'Role has been updated successfully.');
            }
            return view('settings.useraccess.role.update', ['breadcrumbs'=> $this->breadcrumbs,'role'=>$role]);
    }
    /**
     * deletes a particular role from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
    	 try{
    	 	 if($request->ajax()) {
                
                $role = Role::query()->findOrFail($id);
                if($role) {
                    $role->delete();
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
            	return response()->json(['error'	=>	'']);
            }
        } catch (\Exception $e) {
            return response()->json(['error'	=>	$e->getMessage()]);
        }
    }

}

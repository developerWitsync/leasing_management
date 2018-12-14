<?php
/**
 * Created by PhpStorm.
 * User: flexsin
 * Date: 24/10/18
 * Time: 3:37 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AuthController extends Controller
{
    /**
     * load the view to login the admin user and if the request method is post than login the admin user as well
     * if the admin is logged in successfully than redirects the admin user back to the admin dashboard
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request){
        if($request->isMethod('post')){

            $validator = Validator::make($request->except("_token", "submit"), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if($validator->fails()){
                return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors($validator->errors());
            }

            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => '1'], $request->input('remember'))) {
                // if successful, then redirect to their intended location
                return redirect()->route('admin.dashboard.index');
            }
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->withInput($request->only('email', 'remember'))->withInfo('Wrong Credentials');
        }
        return view('admin.auth.login');
    }

    /**
     * Logout the admin user and redirect back to the admin login page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        try{
            Auth::guard('admin')->logout(); //logout the logged in admin user
            return redirect(route('admin.auth.login')); //redirect the user back to the admin login page
        } catch (\Exception $e) {
            abort('404');
        }
    }
}
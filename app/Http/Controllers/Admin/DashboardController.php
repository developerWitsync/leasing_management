<?php
/**
 * Created by PhpStorm.
 * User: flexsin
 * Date: 25/10/18
 * Time: 9:14 AM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * loads the admin dashboard view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.dashboard.index');
    }
}
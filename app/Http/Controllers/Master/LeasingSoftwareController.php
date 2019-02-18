<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 14/2/19
 * Time: 5:37 PM
 */

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;

class LeasingSoftwareController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('leasing-software.index');
    }

    public function IFRS(){
        return view('leasing-software.ifrs-16');
    }

}
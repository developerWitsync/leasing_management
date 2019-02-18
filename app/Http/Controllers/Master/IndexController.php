<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 14/2/19
 * Time: 3:06 PM
 */

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('index.index');
    }
}
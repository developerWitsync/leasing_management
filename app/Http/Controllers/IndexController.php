<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 14/2/19
 * Time: 3:06 PM
 */

namespace App\Http\Controllers;


class IndexController extends Controller
{
    public function index(){
        return view('index.index');
    }
}
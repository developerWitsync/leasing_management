<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:38 AM
 */

namespace App\Http\Controllers\Settings;


use App\Http\Controllers\Controller;

class UserAccessController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.useraccess'),
                'title' => 'User Access Settings'
            ]
        ];
        return view('settings.useraccess.index', compact('breadcrumbs'));
    }
}
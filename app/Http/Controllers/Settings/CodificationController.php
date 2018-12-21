<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:39 AM
 */

namespace App\Http\Controllers\Settings;


use App\Http\Controllers\Controller;

class CodificationController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.codification'),
                'title' => 'Codification Settings'
            ]
        ];
        return view('settings.codification.index', compact('breadcrumbs'));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:20 AM
 */

namespace App\Http\Controllers\Settings;


use App\Http\Controllers\Controller;

class CurrenciesController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.currencies'),
                'title' => 'Currencies Settings'
            ]
        ];
        return view('settings.currencies.index', compact('breadcrumbs'));
    }
}
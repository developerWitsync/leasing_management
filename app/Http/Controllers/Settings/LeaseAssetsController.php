<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:36 AM
 */

namespace App\Http\Controllers\Settings;


use App\Http\Controllers\Controller;

class LeaseAssetsController extends Controller
{
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.leaseassets'),
                'title' => 'Lease Assets Settings'
            ]
        ];
        return view('settings.leaseassets.index', compact('breadcrumbs'));
    }
}
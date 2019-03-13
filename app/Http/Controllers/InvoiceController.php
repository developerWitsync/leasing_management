<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 26/2/19
 * Time: 10:21 AM
 */

namespace App\Http\Controllers;


class InvoiceController extends Controller
{
    public function index($id){
        try{
            return view('invoice.index');
        } catch (\Exception $e){
            dd($e);
        }
    }
}
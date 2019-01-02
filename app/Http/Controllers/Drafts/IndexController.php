<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 2/1/19
 * Time: 11:03 AM
 */
namespace App\Http\Controllers\Drafts;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index(){
        dd("drarfts");
        return view('drafts.index');
    }
}
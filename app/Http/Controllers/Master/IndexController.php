<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 14/2/19
 * Time: 3:06 PM
 */

namespace App\Http\Controllers\Master;


use App\Cms;
use App\Http\Controllers\Controller;
use App\Mail\testMail;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'information', 'testMail');
    }

    /**
     * renders the landing page for the website...
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('index.index');
    }

    /**
     * renders the information page on the front-end
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function information($slug = null){
        try{
            $page = Cms::query()->where('slug', '=', $slug)->firstOrFail();
            return view('index.information', compact(
                'page'
            ));
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * renders the static about us page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about(){
        return view('index.about');
    }

    /**
     * renders the static services we offer template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function services(){
        return view('index.services');
    }

    /**
     * renders the static e-learning page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eLearning(){
        return view('index.e-learning');
    }

    public function testMail(){
        try {
            $mail = \Mail::to('harry1@yopmail.com')->send(new testMail());
        }catch (\Exception $exception){
            dd($exception);
        }
    }
}
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

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'information');
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
}
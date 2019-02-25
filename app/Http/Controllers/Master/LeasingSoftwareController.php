<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 14/2/19
 * Time: 5:37 PM
 */

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;
use App\SubscriptionPlans;

class LeasingSoftwareController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        $subscription_plans = SubscriptionPlans::all();
        return view('leasing-software.index', compact(
            'subscription_plans'
        ));
    }

    public function IFRS(){
        $subscription_plans = SubscriptionPlans::all();
        return view('leasing-software.ifrs-16', compact(
            'subscription_plans'
        ));
    }

}
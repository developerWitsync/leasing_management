<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 18/2/19
 * Time: 5:41 PM
 */

namespace App\Http\Controllers;

use Session;
use App\SubscriptionPlans;
use App\Countries;
use App\IndustryTypes;
use App\Currencies;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Renders the register form once the user selects the package
     * @param $package
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($package){
        $package = SubscriptionPlans::query()->where('slug', $package)->first();
        if($package) {

            $selected_plan_data = null;
            if($package->price_plan_type == "1" && !is_null($package->price)){
                $selected_plan_data = Session::get('selected_plan');
                if(is_null($selected_plan_data)) {
                    return redirect()->back()->with('error', 'Please select the plan and subscription years as well.');
                }
            }

            $countries = Countries::query()->where('status','=', '1')->where('trash', '=', '0')->get();
            $industry_types = IndustryTypes::query()->where('status', '=', '1')->get();
            $currencies = Currencies::query()->where('status', '=', '1')->get();
            return view('auth.register', compact(
                'countries',
                'industry_types',
                'currencies',
                'package'
            ));
        } else{
            abort(404);
        }
    }

    /**
     * fetches the states for a country and returns the json for all the states...
     * @param $country_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchStates($country_id, Request $request){
        if($country_id && $request->ajax()){
            $country = Countries::query()->findOrFail($country_id);
            $states = $country->states;
            return response()->json([
                'states' => $states
            ], 200);
        } else {
            abort(404);
        }
    }
}
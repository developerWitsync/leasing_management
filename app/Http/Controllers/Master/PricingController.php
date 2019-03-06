<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 5/3/19
 * Time: 3:12 PM
 */

namespace App\Http\Controllers\Master;

use App\CustomPlansRequest;
use App\Http\Controllers\Controller;
use App\SubscriptionPlans;
use Validator;
use Session;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * renders the pricing for the leasing software
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $subscription_plans = SubscriptionPlans::query()->where('is_custom', '=', '0')->get();
        return view('pricing.index', compact(
            'subscription_plans'
        ));
    }

    /**
     * Renders the Vat E Learning Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function vatELearning()
    {
        try {
            return view('pricing.vatelearning');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * returns the redirect link after setting the selected details to the session...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function planSelected(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'selected_plan' => 'required|exists:subscription_plans,id',
                'smonths' => 'required|numeric'
            ],[
                'selected_plan.required' => 'Please Select Annual Subscription Plan.',
                'smonths.required' => 'Please Select Subscription Years.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 200);
            }

            $selected_plan = SubscriptionPlans::query()->find($request->selected_plan);

            //put the selected details to the session for the selected options
            Session::put('selected_plan', $request->all());
            return response()->json([
                'status' => true,
                'redirect_link' => route('register.index', ['package' => $selected_plan->slug])
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * build your own plan generate request for the custom plan type...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildYourPlan(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'required',
                    'no_of_lease_assets' => 'required|numeric',
                    'no_of_users' => 'required|numeric',
                    'hosting_type' => 'required'
                ]);

                if($validator->fails()){
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 200);
                }

                $plan_request = CustomPlansRequest::create($request->except('_token'));

                if($plan_request) {
                    return response()->json([
                        'status' => true,
                        'message' => '<strong>Thanks!</strong> We have received your request and we will get back to you very soon'
                    ], 200);
                }

            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }
}
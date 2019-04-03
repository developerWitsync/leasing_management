<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 5/3/19
 * Time: 3:12 PM
 */

namespace App\Http\Controllers\Master;

use App\CouponCodes;
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
        $is_register_allowed = env('REGISTER_ALLOWED');

        if(!$is_register_allowed){
            return redirect('/')->with('register_not_allowed', true);
        }

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
                //'redirect_link' => route('register', ['package' => $selected_plan->slug])
                'redirect_link' => route('register')
            ]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * calculate the cart along with the coupon if the user have the coupon code in the input field
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateCart(Request $request){
        try{
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

            //need to  validate so that the coupon code cannot be applied to a trial plan
            if(is_null($selected_plan->price) && $selected_plan->price_plan_type == '1') {
                if($request->has('coupon_code') && trim($request->coupon_code) != ""){
                    $message = 'Coupon code cannot be applied to a trial plan.';
                } else {
                    $message = 'Invalid Request';
                }
                return response()->json([
                    'status' => false,
                    'errorMessage' => $message
                ], 200);
            }

            $original_amount = $selected_plan->price * $request->smonths;
            $coupon_discount = 0;

            if($request->has('coupon_code') && trim($request->coupon_code)!= ""){
                $coupon_code = CouponCodes::query()->where('code', '=', $request->coupon_code)
                    ->whereNull('user_id')
                    ->where('status', '=', '1')
                    ->first();
                if(is_null($coupon_code)){
                    return response()->json([
                        'status' => false,
                        'errorMessage' => 'Coupon not found.'
                    ], 200);
                }

                //check here if the coupon can only be used for a particular plan
                if(!is_null($coupon_code->plan_id) && $coupon_code->plan_id == $selected_plan->id || is_null($coupon_code->plan_id)){
                    $coupon_discount = round(($coupon_code->discount / 100) * $original_amount, 2);
                } else {
                    return response()->json([
                        'status' => false,
                        'errorMessage' => 'This coupon cannot be applied to the selected plan.'
                    ], 200);
                }
            }

            $discounted_amount = 0;
            if ($selected_plan->annual_discount > 0) {
                $discounted_percentage = ($request->smonths / 12 - 1) * $selected_plan->annual_discount;
                if($discounted_percentage > 0){
                    $discounted_amount = round(($discounted_percentage / 100) * $original_amount, 2);
                }
            }

            $net_payable = $original_amount - ($coupon_discount + $discounted_amount);

            return response()->json([
                'status' => true,
                'coupon_discount' => $coupon_discount,
                'net_payable' => $net_payable,
                'offer' => $discounted_percentage,
                'gross_value' => $original_amount
            ], 200);

        }catch (\Exception $e){
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
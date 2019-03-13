<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 11/2/19
 * Time: 5:04 PM
 */

namespace App\Http\Controllers\Admin;

use App\CustomPlansRequest;
use App\SubscriptionPlans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SubscriptionPlansController extends Controller
{
    public function index(){
        return view('admin.subscription-plans.index');
    }

    /**
     * Fetches all the Subscription plans that has been created
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = SubscriptionPlans::query();

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('title', 'like', "%" . $request->search["value"] . "%");
                        }
                    })

                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            return redirect()->back();
        }
    }

    /**
     * Create a new Subscription plan so that the users can purchase the subscription plan
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request){
        try{
            $model = new SubscriptionPlans();
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:subscription_plans,title',
                    'price_plan_type'   => 'required',
                    'price' => 'numeric|min:1|nullable',
                    'available_leases' => 'numeric|min:1|nullable',
                    'available_users' =>  'numeric|min:1|nullable',
                    'hosting_type' => 'required',
                    'validity' => 'numeric|min:1|nullable',
                    'annual_discount' => 'numeric|max:50'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $request->request->add(['slug' => str_slug($request->title)]);

                $model->setRawAttributes($request->except('_token'));
                if($model->save()) {
                    return redirect(route('admin.subscriptionplans.index'))->with('success', 'Subscription Plan has been created successfully.');
                }
            }
            return view('admin.subscription-plans.create', compact(
               'model'
            ));
        }catch (\Exception $exception){
            abort(404);
        }
    }

    /**
     * Update an existing Subscription Plan
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $model = SubscriptionPlans::query()->findOrFail($id);
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:subscription_plans,title,'.$id,
                    'price_plan_type'   => 'required',
                    'price' => 'numeric|min:1|nullable',
                    'available_leases' => 'numeric|min:1|nullable',
                    'available_users' =>  'numeric|min:1|nullable',
                    'hosting_type' => 'required',
                    'validity' => 'numeric|min:1|nullable',
                    'annual_discount' => 'numeric|max:50'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $request->request->add(['slug' => str_slug($request->title)]);

                $model->setRawAttributes($request->except('_token'));
                if($model->save()) {
                    return redirect(route('admin.subscriptionplans.index'))->with('success', 'Subscription Plan has been updated successfully.');
                }
            }
            return view('admin.subscription-plans.update', compact(
                'model'
            ));
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * can delete only custom plans created for some email id..
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, Request $request){
        try{
            if($request->ajax()){
                $plan = SubscriptionPlans::query()->where('id', '=', $id)->where('is_custom', '=', '1')->firstOrFail();
                $plan->delete();
                return response()->json([
                    'status' => true
                ], 200);
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * renders the view to load all the custom plan requests
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customPlanRequests(){
        return view('admin.subscription-plans.customplanrequest');
    }

    /**
     * fetches the custom plan requests so that the requests can be rendered by the datatable
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchCustomPlanRequests(Request $request){
        try{
            if ($request->ajax()) {

                $model = CustomPlansRequest::query()->orderBy('created_at', 'desc');

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('name', 'like', "%" . $request->search["value"] . "%");
                        }
                    })

                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            return redirect()->back();
        }
    }

    public function createCustomPlan($request_id  = null,Request $request){
        try{
            $model = new SubscriptionPlans();
            $plan_request = null;
            if($request_id){
                $plan_request = CustomPlansRequest::query()->findOrFail($request_id);
                $model->email = $plan_request->email;
            }

            if($request->isMethod('post')) {

                $request->request->add(['price_plan_type' => '2']);

                $validator = Validator::make($request->all(), [
                    'title' => 'required|unique:subscription_plans,title',
                    'price_plan_type'   => 'required',
                    'price' => 'required|numeric|min:1',
                    'available_leases' => 'numeric|min:1|nullable',
                    'available_users' =>  'numeric|min:1|nullable',
                    'hosting_type' => 'required',
                    'validity' => 'numeric|min:1|nullable',
                    'annual_discount' => 'numeric|max:50'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $request->request->add(['slug' => str_slug($request->title), 'is_custom' => '1']);

                $model->setRawAttributes($request->except('_token'));

                if($model->save()) {
                    return redirect(route('admin.subscriptionplans.index'))->with('success', 'Subscription Plan has been created successfully.');
                }

            }

            return view('admin.subscription-plans.createcustomplan', compact(
                'model'
            ));
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
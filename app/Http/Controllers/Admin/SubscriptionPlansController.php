<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 11/2/19
 * Time: 5:04 PM
 */

namespace App\Http\Controllers\Admin;

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
                    'price' => 'numeric|min:1|nullable',
                    'available_leases' => 'numeric|min:1|nullable',
                    'available_users' =>  'numeric|min:1|nullable',
                    'hosting_type' => 'required',
                    'validity' => 'numeric|min:1|nullable'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

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
}
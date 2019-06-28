<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 7/3/19
 * Time: 10:39 AM
 */

namespace App\Http\Controllers\Admin;


use App\CouponCodes;
use App\Http\Controllers\Controller;
use App\SubscriptionPlans;
use App\User;
use Illuminate\Http\Request;
use Validator;

class CouponCodeController extends Controller
{
    public function index(){
        return view('admin.coupon.index');
    }

    protected function validationRules($forupdate = false, $id = null){
        $return  = [
            'code' => 'required|unique:coupon_codes,code',
            'user_id' => 'numeric|exists:users,id|nullable',
            'plan_id' => 'numeric|exists:subscription_plans,id|nullable',
            'discount' => 'required|min:1|max:100|numeric',
            'status' => 'required'
        ];

        if($forupdate && $id) {
            $return['code'] = 'required|unique:coupon_codes,code,'.$id;
        }

        return $return;
    }

    /**
     * fetches the data from the coupon codes so that those can be rendered on the dataTable...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetch(Request $request){
        try{
            if ($request->ajax()) {

                $model = CouponCodes::query()->with('user')->with('plan');

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request){
                        if ($request->has('search') && trim($request->search["value"])!="") {
                            $query->where('code', 'like', "%" . $request->search["value"] . "%");
                        }
                    })
                    ->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            dd($e);
            return redirect()->back();
        }
    }

    /**
     * Renders the create coupon code form in case the request method is get
     * In case the request method is post create the coupon code and save to database.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request){
        try{
            $model = new CouponCodes();
            $plans = SubscriptionPlans::query()->where('is_custom', '=', '0')->where('price_plan_type', '=', '1')->whereNotNull('price')->get();
            if($request->isMethod('post')){

                $validator = Validator::make($request->all(), $this->validationRules());

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $model->setRawAttributes($request->except('_token'));
                if($model->save()){
                    return redirect(route('admin.coupon.index'))->with('status','Coupon Code has been created successfully.');
                }
            }
            return view('admin.coupon.create', compact(
                'model',
                'plans'
            ));
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * search the non-admin users and return them to be rendered on the select2 search..
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request){
        try{
            $users = User::query()->where('type', '=', '0')->where('parent_id','=', '0')->where('email', 'like', '%'.$request->term.'%');
            $items = $users->get();
            return response()->json($items, 200);
        } catch (\Exception $e){
            abort(404);
        }
    }


    /**
     * Update a particular Coupon and save the same to the database.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id, Request $request){
        try{
            $model = CouponCodes::query()->findOrFail($id);
            $plans = SubscriptionPlans::query()->where('is_custom', '=', '0')->where('price_plan_type', '=', '1')->whereNotNull('price')->get();
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except('_token'), $this->validationRules(true, $id));

                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }

                $model->setRawAttributes($request->except('_token'));
                $model->save();
                return redirect(route('admin.coupon.index'))->with('success', 'Coupon has been updated successfully.');
            }
            return view('admin.coupon.update', compact('model', 'plans'));
        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * deletes a particular coupon code from the database
     * deleted data cannot be reverted back
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request) {
        try{
            if($request->ajax()) {
                $country = CouponCodes::query()->findOrFail($id);
                if($country) {
                    $country->delete();
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }

    /**
     * updates the status of the country and returns the response in json format
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request){
        try{
            if($request->ajax()){
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                    'id'    => 'required|exists:coupon_codes,id'
                ]);

                if($validator->fails()) {
                    return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
                }

                $country = CouponCodes::query()->findOrFail($request->id);
                $country->status = $request->status;
                $country->save();
                return response()->json(['status' => true], 200);
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort('404');
        }
    }
}
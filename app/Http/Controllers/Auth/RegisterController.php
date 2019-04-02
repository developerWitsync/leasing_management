<?php

namespace App\Http\Controllers\Auth;

use App\UserSubscription;
use Session;
use Mail;
use App\SubscriptionPayments;
use App\SubscriptionPlans;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        abort(404);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'country' => 'required|exists:countries,name',
            'state' => 'required_if:country,India|exists:states,state_name|nullable',
            'gstin' => 'required_if:country,India|min:15|nullable',
            'applicable_gaap' => 'required|exists:accounting_standards,id',
            'legal_entity_name' => 'required',
            'authorised_person_name' => 'required|string|max:255',
            'authorised_person_dob' => 'required|date|before:-18 years',
            'gender' => 'required',
            'authorised_person_designation' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'admin_rights' => 'required|in:yes',
            'declaration' => 'required',
            'terms_and_condition' => 'required',
            'certificates' => config('settings.file_size_limits.certificates'),
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'declaration.required' => 'Please accept the declaration.',
            'terms_and_condition.required' => 'Please accept Terms and Conditions.',
            'authorised_person_dob.before' => 'The authorised person must be atleast 18 years old.',
            'g-recaptcha-response.required' => 'Please confirm the recaptcha.',
            'g-recaptcha-response.captcha' => 'Recaptcha not confirmed.',
            'admin_rights.in' => 'You need to register with admin rights.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['type'] = '0';
        $data['authorised_person_dob'] = date('Y-m-d', strtotime($data['authorised_person_dob']));
        $data['email_verification_code'] = md5(time());
        $data['is_verified'] = '0';
        $data['parent_id'] = 0;
        $data['raw_password'] = $data['password'];
        $data['password'] = bcrypt($data['password']);

        unset($data['_token']);
        return User::create($data);
    }

    /**
     * registration process for the users
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function register(Request $request)
    {
        try{

//            $package = SubscriptionPlans::query()->findOrFail($request->selected_plan);
//            $selected_plan_data = null;
//            if($package->price_plan_type == "1" && !is_null($package->price)){
//                $selected_plan_data = Session::get('selected_plan');
//                if(is_null($selected_plan_data)) {
//                    return redirect(route('master.pricing.index'))->with('error', 'Please select the plan and subscription years as well.');
//                }
//            } elseif($package->price_plan_type == "2"){
//                return redirect(route('master.pricing.index'))->with('error', 'Invalid Request.');
//            }

            $validator = $this->validator($request->all());
            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }

            $userData = $request->all();

            $userData['password'] = str_random(8);

            $uniqueFileName = '';

            if($request->hasFile('certificates')){
                $file = $request->file('certificates');
                $uniqueFileName = uniqid() . $file->getClientOriginalName();
                $request->file('certificates')->move('uploads', $uniqueFileName);
            }

            $userData['certificates'] = $uniqueFileName;

            $user = $this->create($userData);
            if ($user) {

                return redirect('/login')->with('success', 'Your account has been registered. Please check your email inbox to proceed further.');

//                //need to create an entry to the user_subscription table...
//                if (!is_null($package->validity) && is_null($selected_plan_data)) {
//                    //this means that the plan is trial plan
//                    $expiry_date = Carbon::today()->addDays($package->validity)->format('Y-m-d');
//                } else {
//                    //will expires after 1 year
//                    $expiry_date = Carbon::today()->addYear(isset($selected_plan_data["smonths"])? $selected_plan_data["smonths"]/12 : 1 )->format('Y-m-d');
//                }
//
//                $renewal_date = Carbon::parse($expiry_date)->addDays(1)->format('Y-m-d');
//                $user_subscription = UserSubscription::create([
//                    'plan_id' => $request->selected_plan,
//                    'user_id' => $user->id,
//                    'paid_amount' => $package->price,
//                    'subscription_expire_at' => $expiry_date,
//                    'subscription_renewal_at' => $renewal_date,
//                    'subscription_years' => isset($selected_plan_data["smonths"])?$selected_plan_data["smonths"]/12:1, //default is set to 1 year
//                    'payment_status' => 'pending',
//                    'coupon_code'   => isset($selected_plan_data["coupon_code"])?$selected_plan_data["coupon_code"]:null,
//                ]);
//
//                //need to redirect the user to the paypal for generating the payment, before that need to create a transaction to the subscription_payments with
//                //the pending status
//                if ($package->price_plan_type == '1' && is_null($package->price)) {
//                    $user_subscription->payment_status = "Completed";
//                    $user_subscription->save();
//                    return redirect('/login')->with('success', 'Your account has been registered. Please check your email inbox to proceed further.');
//                } elseif ($package->price_plan_type == '1' && !is_null($package->price)) {
//                    //the selected plan is not a free plan and the user will have to pay here...
//                    //need to send the user to the express checkout
//                    $link = generatePaypalExpressCheckoutLink($package, $user_subscription, null, null, null, null, $selected_plan_data['smonths']);
//                    return redirect()->away($link);
//                } else {
//                    // the selected plan is enterprise plan and the user will have to communicate with the admin,,,,
//
//                }
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }
}

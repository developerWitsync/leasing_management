<?php

namespace App\Http\Controllers\Auth;

use App\Currencies;
use App\IndustryTypes;
use App\User;
use App\Countries;
use App\Http\Controllers\Controller;
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
        $countries = Countries::query()->where('status','=', '1')->where('trash', '=', '0')->get();
        $industry_types = IndustryTypes::query()->where('status', '=', '1')->get();
        $currencies = Currencies::query()->where('status', '=', '1')->get();
        return view('auth.register', compact('countries', 'industry_types', 'currencies'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'country' => 'required|exists:countries,id',
            'legal_status' => 'required',
            'applicable_gaap' => 'required',
            'industry_type' => 'required|exists:industry_type,id',
            'legal_entity_name' => 'required',
            'authorised_person_name' => 'required|string|max:255',
            'authorised_person_dob'     => 'required|date',
            'gender'    => 'required',
            'authorised_person_designation' => 'required',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required',
            'annual_reporting_period'   => 'required',
            'currency'  => 'required',
            'parent_id' => 0
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['type'] = '0';
        $data['password'] = bcrypt($data['password']);
        $data['authorised_person_dob'] = date('Y-m-d', strtotime($data['authorised_person_dob']));
        $data['email_verification_code'] = md5(time());
        $data['is_verified'] = '0';

        unset($data['password_confirmation']);
        unset($data['_token']);
        return User::create($data);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        if($user) {
            return redirect('/login')->with('success', 'Your account has been registered. Please check your email inbox to proceed furhter.');
        }
    }
}

<?php

namespace App\Http\Controllers\Contactus;

use App\ContactUs;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\ContactUsQueryFrom;
use Mail;

class ContactusController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contactus Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the contactus of new vistior user as well as their
    | validation and creation.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the Contact Us form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->isMethod('post')) {
            $validator = $this->validationRules($request->all());
            if ($validator->fails()) {
                return redirect()->route('contactus')->withErrors($validator)->withInput();
            }
            $contactus = ContactUs::create($request->except("_token"));
            if ($contactus) {
                \Mail::to($contactus)->send(new ContactUsQueryFrom($contactus));
                \Mail::to(env('INFO_EMAIL'))->send(new ContactUsQueryFrom($contactus));
                return redirect()->route('contactus')->with('success', 'Thank you for contacting us. We will get back to you very soon.');
            }
        }

        return view('contact_us');


    }

    /**
     * Validation rules for the contact us form set to be common
     * @param $data
     * @return mixed
     */
    private function validationRules($data){
        return Validator::make($data, [
            'name' => 'required',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
            'comments' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ],[
            'g-recaptcha-response.required' => 'Please confirm the recaptcha.',
            'g-recaptcha-response.captcha' => 'Recaptcha not confirmed.'
        ]);
    }

    /**
     * contact us from the website and sends the email to the user as well
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInTouchWithUs(Request $request){
        try{
            if($request->ajax()){
                $validator = $this->validationRules($request->all());
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'errorMessages' => $validator->errors() ]);
                }

                $contactus = ContactUs::create($request->except("_token"));
                if ($contactus) {
                    \Mail::to($contactus)->send(new ContactUsQueryFrom($contactus));
                    \Mail::to(env('INFO_EMAIL'))->send(new ContactUsQueryFrom($contactus));
                    return response()->json([
                        'status' => true,
                        'message' => 'Thanks for connecting with us. We will get back to you very soon.'
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

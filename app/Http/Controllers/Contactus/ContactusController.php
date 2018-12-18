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
     * Show the Conatct Us form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	 if( $request->isMethod('post') )
       {
	     $validator=Validator::make($request->all(),[
	          'first_name' => 'required',
	            'last_name' => 'required',
	            'email' => 'required|string|email|max:255',
	            'phone' => 'required',
	            'no_of_realestate'   => 'required|numeric'
	          ]);
      if($validator->fails()){
         return redirect()->route('contactus')->withErrors($validator)->withInput();
      }
      $contactus = ContactUs::create($request->except("_token"));
      if($contactus){
      	 \Mail::to($contactus)->queue(new ContactUsQueryFrom($contactus));
         return redirect()->route('contactus')->with('success','Thank you for contacting us.we will contact you as soon as possible');
        }
      }
        return view('contactus');
    }
}

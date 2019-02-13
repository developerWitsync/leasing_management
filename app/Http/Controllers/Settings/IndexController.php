<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 10:27 AM
 */

namespace App\Http\Controllers\Settings;


use App\GeneralSettings;
use App\LeaseLockYear;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Session;



class IndexController extends Controller
{

    public function index(){

        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.index'),
                'title' => 'General Settings'
            ]
        ];
        $settings = GeneralSettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
        if(is_null($settings)) {
            $settings = new GeneralSettings();
        }
        $lease_lock_year = LeaseLockYear::query()->where('business_account_id', '=', auth()->user()->id)->get();
        
        return view('settings.general.index', compact('breadcrumbs', 'settings','lease_lock_year'));
    }

    /**
     * validates the input from the form
     * if the validation returns true saves the settings to the database and redirects the user with a success message
     * if the general settings already exists for the logged in user than updates the existing settings
     * @todo need to implement the functionality for the disabled option on the general settings tab
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        try{

            $validator = Validator::make($request->except("_token"), [
                'date_of_initial_application'   => 'required',
                'min_previous_first_lease_start_year' => 'required',
                'max_lease_end_year' => 'required'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $request->request->add(['business_account_id' => auth()->user()->id]);
            $settings = GeneralSettings::query()->where('business_account_id', '=', auth()->user()->id)->first();

            $data = $request->except('_token');
            
           if(isset($settings)) {
                $settings->setRawAttributes($data);
                $settings->save();
                $status = 'General Settings has been updated successfully.';
            } else {
               GeneralSettings::create($data);
                $status = 'General Settings has been created successfully.';
            }
            return redirect()->back()->with('status', $status);
        } catch (\Exception $e){
             abort(404);
        }
    }
    
}
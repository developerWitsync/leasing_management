<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 18/02/19
 * Time: 02:06 Pm
 */

namespace App\Http\Controllers\Settings;


use App\FinancialReportingPeriodSetting;
use App\GeneralSettings;
use App\LeaseLockYear;
use App\Http\Controllers\Controller;
use App\ReportingPeriods;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Session;

class IndexController extends Controller
{

    /**
     * renders the general settings complete view for the different options
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
        //get the base date for the selected accounting standard by the current user and send the same to the view
        $calculated_base_date =  $standard_base_date = getParentDetails()->accountingStandard->base_date;
        $date_of_incorporation = getParentDetails()->date_of_incorporation;
        $show_date_of_initial_application = false;
        if(is_null($settings)) {
            $settings = new GeneralSettings();
        } else {
            if($settings->annual_financial_reporting_year_end_date){
                $show_date_of_initial_application = true;
                $calculated_base_date = $settings->annual_financial_reporting_year_end_date;
            }
        }

        $lease_lock_year = LeaseLockYear::query()
            ->selectRaw('year(`start_date`) as lock_year, start_date, status')
            ->whereIn('business_account_id', getDependentUserIds())
            ->get()->toArray();

        if(!empty($lease_lock_year)){
            $lease_lock_year = collect($lease_lock_year)->groupBy('lock_year')->toArray();
        }

        $lease_lock_year_range = range(2019, date('Y'));

        $reporting_periods = ReportingPeriods::query()->get();

        $financial_reporting_period_setting = FinancialReportingPeriodSetting::query()->whereIn('business_account_id',getDependentUserIds())->first();
        if(is_null($financial_reporting_period_setting)){
            $financial_reporting_period_setting =  new FinancialReportingPeriodSetting();
        }
        
        return view('settings.general.index', compact(
            'breadcrumbs',
            'settings',
            'lease_lock_year',
            'modication_reason',
            'lease_lock_year_range',
            'reporting_periods',
            'financial_reporting_period_setting',
            'standard_base_date',
            'date_of_incorporation',
            'show_date_of_initial_application',
            'calculated_base_date'
        ));
    }

    /**
     * save the Date of incorporation for the current logged in business account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDateOfIncorporation(Request $request){
        try{
            $validator = Validator::make($request->except("_token"), [
                'date_of_incorporation' => 'required|date'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $user = getParentDetails();
            $user->setAttribute('date_of_incorporation', Carbon::parse($request->date_of_incorporation)->format('Y-m-d'));
            $user->save();

            return redirect()->back()->with('status', 'Date of incorporation have been saved successfully.');
        }catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * validates the input from the form
     * if the validation returns true saves the settings to the database and redirects the user with a success message
     * if the general settings already exists for the logged in user than updates the existing settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        try{

            $validator = Validator::make($request->except("_token"), [
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
                $status = 'General Settings has been saved successfully.';
            } else {
                GeneralSettings::create($data);
                $status = 'General Settings has been saved successfully.';
            }
            return redirect()->back()->with('status', $status);
        } catch (\Exception $e){
             abort(404);
        }
    }

    /**
     * save the initial date of application from the general settings.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveApplicationStandards(Request $request){
        try{
            $validator = Validator::make($request->except("_token"), [
                'date_of_initial_application'   => 'required'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $request->request->add(['business_account_id' => auth()->user()->id]);

            $settings = GeneralSettings::query()->where('business_account_id', '=', auth()->user()->id)->first();

            //as in case of Full Retrospective we are already subtracting 1 year from the base date where required so we do not need to subtract here....
//            if($request->date_of_initial_application == '1') {
//                $calculated_base_date = Carbon::parse($settings->annual_financial_reporting_year_end_date)->addDay(1)->format('Y-m-d');
//            } else if($request->date_of_initial_application == '2'){
//                $calculated_base_date = Carbon::parse($settings->annual_financial_reporting_year_end_date)->addDay(1)->subDay(365)->format('Y-m-d');
//            }

            $calculated_base_date = Carbon::parse($settings->annual_financial_reporting_year_end_date)->addDay(1)->format('Y-m-d');

            $data = $request->except('_token');

            $data['is_initial_date_of_application_saved'] = 1;

            $data['final_base_date'] = $calculated_base_date;

            if(isset($settings)) {
                $settings->setRawAttributes($data);
                $settings->save();
                $status = 'Date of Initial Application of the New Leasing Standard has been saved successfully.';
            } else {
                GeneralSettings::create($data);
                $status = 'Date of Initial Application of the New Leasing Standard has been saved successfully.';
            }
            return redirect()->back()->with('status', $status);

        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * saves the first step base date standards so that the base date options can be calculated
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveBaseDateStandards(Request $request){
        try{

            $validator = Validator::make($request->except("_token"), [
                'effective_date_of_standard'   => 'required|date',
                'annual_financial_reporting_year_end_date'  => 'required|date'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $request->request->add(['business_account_id' => auth()->user()->id]);
            $settings = GeneralSettings::query()->where('business_account_id', '=', auth()->user()->id)->first();

            $data = $request->except('_token');

            $data['effective_date_of_standard'] = Carbon::parse($request->effective_date_of_standard)->format('Y-m-d');

            $data['annual_financial_reporting_year_end_date'] = Carbon::parse($request->annual_financial_reporting_year_end_date)->format('Y-m-d');

            if(isset($settings)) {
                $settings->setRawAttributes($data);
                $settings->save();
                $status = 'Base Date standards have been saved successfully.';
            } else {
                GeneralSettings::create($data);
                $status = 'Base Date standards have been saved successfully.';
            }

            return redirect()->back()->with('status', $status);

        } catch (\Exception $e) {
            dd($e);
            abort(404);
        }
    }

    /**
     * saves the financial reporting period settings for the business account
     * also updates the financial reporting period settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function financialReportingPeriod(Request $request){
        try{
            if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'reporting_period_id' => 'required|exists:reporting_periods,id'
                ], [
                    'reporting_period_id.required' => 'Financial Reporting Period is required.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $financial_reporting_period_setting = FinancialReportingPeriodSetting::query()->whereIn('business_account_id',getDependentUserIds())->first();
                if(is_null($financial_reporting_period_setting)){
                    $financial_reporting_period_setting =  new FinancialReportingPeriodSetting();
                }
                $request->request->add(['business_account_id' => getParentDetails()->id]);
                $financial_reporting_period_setting->setRawAttributes($request->except('_token'));

                if($financial_reporting_period_setting->save()){
                    return redirect()->back()->with('status', 'Financial Reporting Period Settings has been saved successfully.');
                }

            } else {
                return redirect(route('settings.index'));
            }
        }catch (\Exception $e){
            abort(404);
        }
    }
                
}
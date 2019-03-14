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

        $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
        if(is_null($settings)) {
            $settings = new GeneralSettings();
        }

        $lease_lock_year = LeaseLockYear::query()
            ->selectRaw('year(`start_date`) as lock_year, start_date, status')
            ->whereIn('business_account_id', getDependentUserIds())
            ->get()->toArray();

        if(!empty($lease_lock_year)){
            $lease_lock_year = collect($lease_lock_year)->groupBy('lock_year')->toArray();
        }

        $lease_lock_year_range = range(2016, date('Y'));

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
            'financial_reporting_period_setting'
        ));
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
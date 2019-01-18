<?php
/**
 * Created by PhpStorm.
 * User: Jyoti
 * Date: 24/12/18
 * Time: 11:39 AM
 */

namespace App\Http\Controllers\Lease;

use App\ContractClassifications;
use App\Currencies;
use App\Lease;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseCompletedSteps;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;


class LessorDetailsController extends Controller
{

    protected function validationRules(){
        return [
            'lessor_name' => 'required',
            'lease_type_id' => 'required',
            'lease_contract_id' => 'required',
            'file' => 'mimes:doc,pdf,docx,zip'
        ];
    }

    public $breadcrumbs;
    public function __construct()
    {
        $this->breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Lessor Details'
            ],
        ];
    }

    /**
     * Renders the index form to create a new Lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id = null, Request $request ){
      
        if($id) {
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $request->id)->first();
            if(is_null($lease)) {
                abort(404);
            }
        } else {
            $lease = new Lease();
        }
    
       
        $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
        $currencies = Currencies::query()->where('status', '=', '1')->get();
        $reporting_currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
        $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())->get();
        if(collect($reporting_currency_settings)->isEmpty()) {
            $reporting_currency_settings = new ReportingCurrencySettings();
        }
        $breadcrumbs = $this->breadcrumbs;
        return view('lease.lessor-details.index', compact('breadcrumbs','contract_classifications','currencies','reporting_currency_settings','reporting_foreign_currency_transaction_settings','lease'));
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

            $validator = Validator::make($request->except("_token"), $this->validationRules());

            if($validator->fails()){
                
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $uniqueFileName = '';

            if($request->hasFile('file')){
                $file = $request->file('file');
                $uniqueFileName = uniqid() . $file->getClientOriginalName();
                $request->file('file')->move('uploads', $uniqueFileName);
            }

            $request->request->add(['business_account_id' => auth()->user()->id]);
            $data = $request->except('_token');
            $data['lease_code'] = time().'-'.mt_rand();
            $data['file'] = $uniqueFileName;
            $data['status'] = '0';
            $lease = Lease::create($data);
           
            if($lease) {
                 $lease_id = $lease->id;
                 $step= 'step1';
                 $complete_step1 = confirmSteps($lease_id,$step);

                if($request->has('action') && $request->action == "next") {
                    return redirect(route('addlease.leaseasset.index', ['id'=>$lease->id]))->with('status', 'Lessor Details has been updated successfully.');
                } else {
                    return redirect(route('add-new-lease.index', ['id' => $lease->id]))->with('status', "Lessor Details has been updated successfully.");
                }
            }

         } catch (\Exception $e){
            //dd($e->getMessage());
            abort(404);
        }
    }


    /**
     * validates the input from the form
     * if the validation returns true saves the settings to the database and redirects the user with a success message
     * if the general settings already exists for the logged in user than updates the existing settings
     * @todo need to implement the functionality for the disabled option on the general settings tab
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function udpate($id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if($lease) {
                $validator = Validator::make($request->except("_token"), $this->validationRules());

                if($validator->fails()){

                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $uniqueFileName = '';

                if($request->hasFile('file')){
                    $file = $request->file('file');
                    $uniqueFileName = uniqid() . $file->getClientOriginalName();
                    $request->file('file')->move('uploads', $uniqueFileName);
                }

                $request->request->add(['business_account_id' => auth()->user()->id]);
                $data = $request->except('_token', 'action');
                $data['lease_code'] = time().'-'.mt_rand();
                $data['file'] = $uniqueFileName;
                $data['status'] = '0';
                $lease->setRawAttributes($data);
                if($lease->save()) {
                    if($request->has('action') && $request->action == "next") {
                        return redirect(route('addlease.leaseasset.index', ['id'=>$id]))->with('status', 'Lessor Details has been updated successfully.');
                    } else {
                        return redirect(route('add-new-lease.index', ['id' => $id]))->with('status', "Lessor Details has been updated successfully.");
                    }
                }
            } else {
                abort(404);
            }

        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * update the total assets number for a particular lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function udpateTotalAssets($id, Request $request){
        try{
            if($request->isMethod('post')){
                $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
                if($lease) {
                    $lease->total_assets = $request->total_lease_assets;
                    if($lease->save()) {
                        return redirect()->back();
                    }
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }
}

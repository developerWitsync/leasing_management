<?php
/**
 * Created by Sublime.
 * User: Jyoti
 * Date: 28/12/18
 * Time: 4:10 PM
 */
namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseAssetPaymentsNature;
use App\LeaseResidualValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssets;
use App\LeasePaymentsFrequency;
use App\LeasePaymentsNumber;
use App\LeasePaymentComponents;
use Illuminate\Http\Request;
use Validator;

class LeaseResidualController extends Controller
{
	protected function validationRules1(){
        return [
            'lease_payemnt_nature_id' => 'required',
            'amount_determinable_yes' => 'required',
            'foreign_currency' => 'required',
            'no_of_unit_lease_asset' => 'required',
            'residual_gurantee_value' => 'required',
            'total_residual_gurantee_value' => 'required',
            'other_desc' => 'required',
            'residual_file' => 'mimes:doc,pdf,docx,zip'
        ];
    }
     /**
     * Render the table for all the lease assets so that the user can complete steps for the residual value gurantee
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request){
        try{
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                ->with('assets.category')
                ->with('assets.subcategory')
                ->where('id', '=', $id)
                ->first();

           // $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
            $foreign_currency_if_yes = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

             if($lease) {
                return view('lease.residual-value-gurantee.index', compact(
                    'lease','lease_payments_nature','reporting_currency_settings','foreign_currency_if_yes'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }
    /**
     * Add the  residual Value  for a particular assest
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
     public function create($lease_id, $asset_id, Request $request){
        if($request->isMethod('post')) {
        	dd($request->isMethod('post'));
            $validator = Validator::make($request->except('_token'), [
				'lease_payemnt_nature_id' => 'required',
				'amount_determinable_yes' => 'required',
				'foreign_currency' => 'required',
				'no_of_unit_lease_asset' => 'required',
				'residual_gurantee_value' => 'required',
				'total_residual_gurantee_value' => 'required',
				'other_desc' => 'required',
				'residual_file' => 'mimes:doc,pdf,docx,zip'
            ]);
             if($validator->fails()){

                return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
            }
					$uniqueFileName = '';

					if($request->hasFile('residual_file')){
					$file = $request->file('residual_file');
					$uniqueFileName = uniqid() . $file->getClientOriginalName();
					$request->file('residual_file')->move('uploads', $uniqueFileName);
					}

					$request->request->add(['asset_id' => $asset_id]);
					$data = $request->except('_token');
					$data['residual_file'] = $uniqueFileName;
					$residual = LeaseResidualValue::create($data);
					dd($residual);
                 if($residual){
               return redirect()->back()->with('status', "It has been updated successfully");
            }
        }
             $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $lease_id)->first();
           
            $asset = LeaseAssets::query()->where('lease_id', '=', $lease_id)->where('id', '=', $asset_id)->first();

            $lease_asset_number_of_payments = LeasePaymentsNumber::query()->select('id','number')->whereIn('business_account_id', getDependentUserIds())->get()->toArray();
                    $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
            		$reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
            		$foreign_currency_if_yes = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

                    return view('lease.residual-value-gurantee.create', compact(
                        'lease',
                        'asset',
                        'reporting_currency_settings',
                        'foreign_currency_if_yes','lease_payments_nature'));

    }
}

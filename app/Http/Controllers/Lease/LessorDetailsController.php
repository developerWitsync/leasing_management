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
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use Validator;

class LessorDetailsController extends Controller
{

    public function index(){

        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Lessor Details'
            ]
        ];
        $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
        $currencies = Currencies::query()->where('status', '=', '1')->get();
        $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
        $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

        if(collect($reporting_currency_settings)->isEmpty()) {
            $reporting_currency_settings = new ReportingCurrencySettings();
        }
        return view('lease.lessor-details.index', compact('breadcrumbs','contract_classifications','currencies','reporting_currency_settings','reporting_foreign_currency_transaction_settings'));
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
                'lessor_name' => 'required',
                'lease_type_id' => 'required',
                'lease_contract_id' => 'required',
                'file'   => 'required|mimes:doc,pdf,docx,zip'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
            }

            $uniqueFileName = '';

            if($request->hasFile('file')){
                $file = $request->file('file');
                $uniqueFileName = uniqid() . $file->getClientOriginalName();
                $request->file('file')->move(storage_path() . '/uploads', $uniqueFileName);
            }

            $request->request->add(['business_account_id' => auth()->user()->id]);

            $data = $request->except('_token');

            $data['lease_code'] = time().'-'.mt_rand();

            $data['file'] = $uniqueFileName;

            $data['status'] = '0';

            $lease = Lease::create($data);

            if($lease) {
                return redirect()->back()->with('status','Lessor Details have been saved successfully.');
            }

         } catch (\Exception $e){
            abort(404);
        }
    }
}
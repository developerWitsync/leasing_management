<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;


use App\Countries;
use App\ExpectedLifeOfAsset;
use App\Http\Controllers\Controller;
use App\Lease;
use App\Currencies;
use App\LeaseAccountingTreatment;
use App\LeaseAssetCategories;
use App\LeaseAssets;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseAssetSubCategorySetting;
use App\LeasePaymentsBasis;
use App\UseOfLeaseAsset;
use App\LeaseResidualValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssetPaymentsNature;
use App\LeaseAssetPayments;
use App\FairMarketValue;
use Illuminate\Http\Request;
use Validator;

class LeaseResidualController extends Controller
{
    private $current_step = 8;
    protected function validationRules()
    {
        return [
            'any_residual_value_gurantee'   => 'required',
            'lease_payemnt_nature_id'       => 'required_if:any_residual_value_gurantee,yes|nullable',
            'similar_asset_items' => 'required_if:any_residual_value_gurantee,yes|nullable',
            'residual_gurantee_value' => 'required_if:any_residual_value_gurantee,yes|numeric|nullable',
            'total_residual_gurantee_value' => 'required_if:any_residual_value_gurantee,yes|nullable',
            'attachment' => 'file|mimes:jpeg,pdf,doc|nullable'
        ];
    }

    /**
     * create or update the residual value for the lease for the single lease asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_V2($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.residual.create', ['id' => $id]),
                    'title' => 'Create Residual Value Guarantee'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if ($lease) {

                $asset = $lease->assets->first(); //since the user will now have only one lease asset

                if($asset->residualGuranteeValue){
                    $model = $asset->residualGuranteeValue;
                } else {
                    $model = new LeaseResidualValue();
                }

                if ($request->isMethod('post')) {

                    $rules = $this->validationRules();

                    if($request->has('any_residual_value_gurantee') && $request->any_residual_value_gurantee == 'yes' && $request->has('lease_payemnt_nature_id') && $request->lease_payemnt_nature_id == '2'){
                        $rules['variable_basis_id'] = 'required';
                        $rules['amount_determinable'] = 'required';
                    }

                    $validator = Validator::make($request->except('_token'), $rules);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'uuid', 'asset_name', 'asset_category','action');
                    $data['attachment'] = "";
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    $residual_value = $model->setRawAttributes($data);

                    if ($residual_value->save()) {
                        // complete Step
                        confirmSteps($lease->id, 8);
                        if($request->has('action') && $request->action == "next") {
                            return redirect(route('addlease.durationclassified.index',['id' => $lease->id]));
                        } else {

                            return redirect(route('addlease.residual.index', ['id' => $lease->id]))->with('status', 'Residual value Gurantee has been added successfully.');

                        }
                        
                    }
                }

                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())->get();
                if (collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                $payment_lease_basis = LeasePaymentsBasis::query()->whereIn('business_account_id', getDependentUserIds())->get();

                //to get current step for steps form
                $current_step = $this->current_step;

                return view('lease.residual-value-gurantee.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_payments_nature',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings',
                    'breadcrumbs',
                    'payment_lease_basis',
                    'current_step'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request)
    {
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.residual.index', ['id' => $id]),
                'title' => 'Residual Value Guarantee'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();

        if ($lease) {
            return view('lease.residual-value-gurantee.index', compact(
                'breadcrumbs',
                'lease'
            ));
        } else {
             dd('hi');
            //abort(404);
        }
    }

    /**
     * add fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.residual.create', ['id' => $id]),
                    'title' => 'Create Residual Value Guarantee'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();

            if ($lease) {

                $model = new LeaseResidualValue();
                //$model->any_residual_value_gurantee=$request->any_residual_value_gurantee;

                if ($request->isMethod('post')) {

                    $rules = $this->validationRules();

                    if($request->has('any_residual_value_gurantee') && $request->any_residual_value_gurantee == 'yes' && $request->has('lease_payemnt_nature_id') && $request->lease_payemnt_nature_id == '2'){
                        $rules['variable_basis_id'] = 'required';
                        $rules['amount_determinable'] = 'required';
                    }

                    $validator = Validator::make($request->except('_token'), $rules);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['attachment'] = "";
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    $residual_value = LeaseResidualValue::create($data);

                    if ($residual_value) {
                        // complete Step
                        $complete_step8 = confirmSteps($lease->id, 8);

                        return redirect(route('addlease.residual.index', ['id' => $lease->id]))->with('status', 'Residual value Gurantee has been added successfully.');
                    }
                }
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();

                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())->get();
                if (collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                $payment_lease_basis = LeasePaymentsBasis::query()->whereIn('business_account_id', getDependentUserIds())->get();
                return view('lease.residual-value-gurantee.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_payments_nature',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings',
                    'breadcrumbs',
                    'payment_lease_basis'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * edit existing Residual value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.residual.update', ['id' => $id]),
                    'title' => 'Update Residual Value Guarantee'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {

                $model = LeaseResidualValue::query()->where('asset_id', '=', $id)->first();

                if ($request->isMethod('post')) {



                    $rules = $this->validationRules();

                    if($request->has('any_residual_value_gurantee') && $request->any_residual_value_gurantee == 'yes' && $request->has('lease_payemnt_nature_id') && $request->lease_payemnt_nature_id == '2'){
                        $rules['variable_basis_id'] = 'required';
                        $rules['amount_determinable'] = 'required';
                    }

                    $validator = Validator::make($request->except('_token'), $rules);

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['attachment'] = "";
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    if ($request->hasFile('attachment')) {
                        $file = $request->file('attachment');
                        $uniqueFileName = uniqid() . $file->getClientOriginalName();
                        $request->file('attachment')->move('uploads', $uniqueFileName);
                        $data['attachment'] = $uniqueFileName;
                    }

                    $model->setRawAttributes($data);

                    if ($model->save()) {
                        return redirect(route('addlease.residual.index', ['id' => $lease->id]))->with('status', 'Residual Gurantee has been updated successfully.');
                    }
                }
                $lease_payments_nature = LeaseAssetPaymentsNature::query()->get();
                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->whereIn('business_account_id', getDependentUserIds())->get();
                if (collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                $payment_lease_basis = LeasePaymentsBasis::query()->whereIn('business_account_id', getDependentUserIds())->get();
                return view('lease.residual-value-gurantee.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_payments_nature',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings',
                    'breadcrumbs',
                    'payment_lease_basis'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
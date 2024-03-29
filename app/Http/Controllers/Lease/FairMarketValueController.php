<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 26/12/18
 * Time: 12:33 PM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\Currencies;
use App\LeaseAssets;
use App\FairMarketValue;
use App\ReportingCurrencySettings;
use App\ForeignCurrencyTransactionSettings;
use Illuminate\Http\Request;
use Validator;

class FairMarketValueController extends Controller
{
    private $current_step = 11;

    protected function validationRules()
    {
        return [
            'is_market_value_present' => 'required',
            'currency' => 'required_if:is_market_value_present,yes',
            'similar_asset_items' => 'required_if:is_market_value_present,yes',
            'unit' => 'required_if:is_market_value_present,yes',
            'total_units' => 'required_if:is_market_value_present,yes',
            'attachment' => config('settings.file_size_limits.file_rule')
        ];
    }

    /**
     * changes so that the users can have one lease asset per lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_V2($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.fairmarketvalue.index', ['id' => $id]),
                    'title' => 'Fair Market Value'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if ($lease) {

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $id)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })
                    ->first();

                if($asset){
                    if ($asset->fairMarketValue) {
                        $model = $asset->fairMarketValue;
                    } else {
                        $model = new FairMarketValue();
                    }

                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                            'attachment.max' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.',
                            'attachment.uploaded' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.'
                        ]);

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token', 'uuid', 'asset_name', 'asset_category', 'action');
                        $data['attachment'] = "";
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;
                        if ($request->hasFile('attachment')) {
                            $file = $request->file('attachment');
                            $uniqueFileName = uniqid() . $file->getClientOriginalName();
                            $request->file('attachment')->move('uploads', $uniqueFileName);
                            $data['attachment'] = $uniqueFileName;
                        }
                        //dd('fdfdg');
                        $market_value = $model->setRawAttributes($data);
                        if ($market_value->save()) {
                            // complete Step
                            confirmSteps($lease->id, $this->current_step);
                            if ($request->has('action') && $request->action == "next") {

                                return redirect(route('addlease.discountrate.index', ['id' => $lease->id]))->with('status', 'Fair Market has been added successfully.');
                            } else {

                                return redirect(route('addlease.fairmarketvalue.index', ['id' => $lease->id]))->with('status', 'Fair Market has been added successfully.');

                            }

                        }
                    }

                    $currencies = Currencies::query()->where('status', '=', '1')->get();
                    $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                    $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

                    if (collect($reporting_currency_settings)->isEmpty()) {
                        $reporting_currency_settings = new ReportingCurrencySettings();
                    }

                    //to get current step for steps form
                    $current_step = $this->current_step;

                    return view('lease.fair-market-value.createv2', compact(
                        'model',
                        'lease',
                        'asset',
                        'currencies',
                        'reporting_foreign_currency_transaction_settings',
                        'reporting_currency_settings',
                        'breadcrumbs',
                        'current_step',
                        'subsequent_modify_required'
                    ));
                } else {
                    return redirect(route("addlease.discountrate.index", ['id'=> $id]));
                }

            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
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
                'link' => route('addlease.fairmarketvalue.index', ['id' => $id]),
                'title' => 'Fair Market Value'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if ($lease) {
            return view('lease.fair-market-value.index', compact('breadcrumbs',
                'lease'
            ));
        } else {
            abort(404);
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
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {

                $model = new FairMarketValue();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'attachment.max' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.',
                        'attachment.uploaded' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.'
                    ]);

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

                    $market_value = FairMarketValue::create($data);

                    if ($market_value) {

                        // complete Step
                        confirmSteps($lease->id, $this->current_step);

                        return redirect(route('addlease.fairmarketvalue.index', ['id' => $lease->id]))->with('status', 'Fair Market has been added successfully.');
                    }
                }

                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if (collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.fair-market-value.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }


    /**
     * edit existing fair market value details for an asset
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update($id, Request $request)
    {
        try {
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {

                $model = FairMarketValue::query()->where('asset_id', '=', $id)->first();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules(), [
                        'attachment.max' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.',
                        'attachment.uploaded' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.'
                    ]);

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
                        return redirect(route('addlease.fairmarketvalue.index', ['id' => $lease->id]))->with('status', 'Fair Market has been updated successfully.');
                    }
                }

                $currencies = Currencies::query()->where('status', '=', '1')->get();
                $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
                $reporting_foreign_currency_transaction_settings = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();
                if (collect($reporting_currency_settings)->isEmpty()) {
                    $reporting_currency_settings = new ReportingCurrencySettings();
                }
                return view('lease.fair-market-value.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'currencies',
                    'reporting_foreign_currency_transaction_settings',
                    'reporting_currency_settings'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
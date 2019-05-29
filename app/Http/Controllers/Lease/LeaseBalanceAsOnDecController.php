<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 03/01/19
 * Time: 3:24 AM
 */

namespace App\Http\Controllers\Lease;

use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseBalanceAsOnDec;
use App\LeaseAssets;
use App\CategoriesLeaseAssetExcluded;
use App\ReportingCurrencySettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class LeaseBalanceAsOnDecController extends Controller
{
    private $current_step = 13;
    protected function validationRules()
    {
        return [
            'reporting_currency' => 'required',
            /*'carrying_amount'=>'required_if:accounting_treatment,2',
            'liability_balance'=>'required_if:accounting_treatment,2',
            */
            'prepaid_lease_payment_balance' => 'required|numeric',
            'accrued_lease_payment_balance' => 'required|numeric',
            'outstanding_lease_payment_balance' => 'required|numeric',
            'any_provision_for_onerous_lease' => 'required|numeric',
            'exchange_rate' => 'required|numeric'
        ];
    }

    /**
     * Create or update the lease asset balances , the function will now be executed as there can now be only one Lease asset per lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index_V2($id, Request $request){
         try{

            $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.balanceasondec.index', ['id' => $id]),
                    'title' => 'Lease Balance as on 31 Dec 2018'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if ($lease) {
                if($settings->date_of_initial_application == 2){
                    $base_date =  Carbon::parse(getParentDetails()->accountingStandard->base_date)->subYear(1)->format('Y-m-d');
                } else {
                    $base_date =  getParentDetails()->accountingStandard->base_date;
                }
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('accural_period', '<', $base_date)
                    ->first();//since there can now only be one lease asset per lease

                if ($asset) {
                    if ($asset->leaseBalanceAsOnDec) {
                        $model = $asset->leaseBalanceAsOnDec;
                    } else {
                        $model = new LeaseBalanceAsOnDec();
                    }
                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->except('_token'), $this->validationRules());

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token','submit',  'uuid', 'asset_name', 'asset_category','action');
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;

                        $model->setRawAttributes($data);

                       if($model->save()) {
                            
                        // complete Step
                        confirmSteps($asset->lease->id, 13);
                        if($request->has('action') && $request->action == "next") {
                            return redirect(route('addlease.initialdirectcost.index',['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been added successfully.');
                        } else {

                            return redirect(route('addlease.balanceasondec.index', ['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been added successfully.');

                        }
                        }
                    }
                    $category_excluded = CategoriesLeaseAssetExcluded::query()
                        ->whereIn('business_account_id', getDependentUserIds())
                        ->where('status', '=', '0')
                        ->get();
                    $category_excluded_id = $category_excluded->pluck('category_id')->toArray();
                    $asset_on_discount = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                        ->where('specific_use', 1)
                        ->whereNotIn('category_id', $category_excluded_id)
                        ->whereHas('leaseSelectLowValue', function ($query) {
                            $query->where('is_classify_under_low_value', '=', 'no');
                        })
                        ->whereHas('leaseDurationClassified', function ($query) {
                            $query->where('lease_contract_duration_id', '=', '3');
                        })->count();

                    if (is_null($asset_on_discount)) {
                        $asset_on_discount = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                            ->where('specific_use', 2)
                            ->whereNotIn('category_id', $category_excluded_id)
                            ->whereHas('leaseSelectLowValue', function ($query) {
                                $query->where('is_classify_under_low_value', '=', 'no');
                            })
                            ->whereHas('leaseDurationClassified', function ($query) {

                                $query->where('lease_contract_duration_id', '=', '3');
                            })->count();
                    }
                    if ($asset_on_discount > 0) {
                        $back_url = route('addlease.discountrate.index', ['id' => $id]);
                    } else {
                        $category_excluded = CategoriesLeaseAssetExcluded::query()
                            ->whereIn('business_account_id', getDependentUserIds())
                            ->where('status', '=', '0')
                            ->get();
                        $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                        $asset_on_low = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereNotIn('specific_use', [2])
                            ->whereHas('leaseDurationClassified', function ($query) {
                                $query->whereNotIn('lease_contract_duration_id', [1, 2]);
                            })->whereNotIn('category_id', $category_excluded_id)->count();
                        if ($asset_on_low > 0) {

                            $back_url = route('addlease.lowvalue.index', ['id' => $id]);
                        } else {
                            $back_url = route('lease.escalation.index', ['id' => $id]);
                        }
                    }

                    //to get current step for steps form
                    $current_step = $this->current_step;

                    $currency_settings =  ReportingCurrencySettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

                    $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

                    return view('lease.lease-balnce-as-on-dec.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'breadcrumbs',
                        'back_url',
                        'current_step',
                        'currency_settings',
                        'subsequent_modify_required',
                        'settings'
                    ));
                } else {
                    return redirect(route('addlease.initialdirectcost.index', ['id' => $id]));
                }
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
    public function index($id)
    {
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.balanceasondec.index', ['id' => $id]),
                'title' => 'Lease Balance as on 31 Dec 2018'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if ($lease) {
            //Load the assets only lease start prior to Dec 31 2018
            $base_date =  getParentDetails()->accountingStandard->base_date;
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->where('lease_start_date', '<', $base_date)->get();

            return view('lease.lease-balnce-as-on-dec.index', compact(
                'lease',
                'assets',
                'breadcrumbs'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add lease balance as on 31 Dec 2018 for an asset in NL-12
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

                $model = new LeaseBalanceAsOnDec();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;


                    $select_discount_value = LeaseBalanceAsOnDec::create($data);
                    if ($select_discount_value) {

                        // complete Step
                        $complete_step13 = confirmSteps($asset->lease->id, 13);

                        return redirect(route('addlease.balanceasondec.index', ['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been added successfully.');
                    }
                }
                return view('lease.lease-balnce-as-on-dec.create', compact(
                    'model',
                    'lease',
                    'asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * edit existing lease balance as on 31 dec 2018 details for an asset
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

                $model = LeaseBalanceAsOnDec::query()->where('asset_id', '=', $id)->first();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;

                    $model->setRawAttributes($data);

                    if ($model->save()) {
                        return redirect(route('addlease.balanceasondec.index', ['id' => $lease->id]))->with('status', 'Lease Balance as on 31 Dec 2018 has been updated successfully.');
                    }
                }
                return view('lease.lease-balnce-as-on-dec.update', compact(
                    'model',
                    'lease',
                    'asset'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 03/01/19
 * Time: 12:08 AM
 */

namespace App\Http\Controllers\Lease;

use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\LeaseAssets;
use App\CategoriesLeaseAssetExcluded;
use Illuminate\Http\Request;
use Validator;

class SelectDiscountRateController extends Controller
{
    private $current_step = 12;

    protected function validationRules()
    {
        return [
            'discount_rate_to_use' => 'required|numeric|min:1',
            'daily_discount_rate' => 'required'
        ];
    }

    /**
     * create or update the data for the discount rates.
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
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
                    'link' => route('addlease.discountrate.index', ['id' => $id]),
                    'title' => 'Select Discount Rate'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
            if ($lease) {
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();
                $category_excluded = CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                    ->where('specific_use', 1)
                    ->whereNotIn('category_id', $category_excluded_id)
                    ->whereHas('leaseSelectLowValue', function ($query) {
                        $query->where('is_classify_under_low_value', '=', 'no');
                    })
                    ->whereHas('leaseDurationClassified', function ($query) {
                        $query->where('lease_contract_duration_id', '=', '3');
                    })->first();

                if (is_null($asset)) {
                    $asset = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                        ->where('specific_use', 2)
                        ->whereNotIn('category_id', $category_excluded_id)
                        ->whereHas('leaseSelectLowValue', function ($query) {
                            $query->where('is_classify_under_low_value', '=', 'no');
                        })
                        ->whereHas('leaseDurationClassified', function ($query) {

                            $query->where('lease_contract_duration_id', '=', '3');
                        })->first();
                }
                if ($asset) {
                    if ($asset->leaseSelectDiscountRate) {
                        $model = $asset->leaseSelectDiscountRate;
                    } else {
                        $model = new LeaseSelectDiscountRate();
                    }

                    $settings = GeneralSettings::query()->whereIn('business_account_id', getDependentUserIds())->first();

                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->except('_token'), $this->validationRules());
                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }
                        $data = $request->except('_token', 'submit', 'uuid', 'asset_name', 'asset_category', 'action');
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;
                        $model->setRawAttributes($data);
                        if ($model->save()) {
                            // complete Step
                            confirmSteps($asset->lease->id, 12);
                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('addlease.balanceasondec.index', ['id' => $lease->id]))->with('status', 'Select Discount Rate has been added successfully.');
                            } else {
                                return redirect(route('addlease.discountrate.index', ['id' => $lease->id]))->with('status', 'Select Discount Rate has been added successfully.');
                            }

                        }
                    }

                    //to get current step for steps form
                    $current_step = $this->current_step;

                    return view('lease.select-discount-rate.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'breadcrumbs',
                        'back_url',
                        'current_step',
                        'subsequent_modify_required',
                        'settings'
                    ));

                } else {
                    //skip the step and send the user to the next step
                    return redirect(route('addlease.balanceasondec.index', ["id" => $id]));
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
                'link' => route('addlease.discountrate.index', ['id' => $id]),
                'title' => 'Select Discount Rate'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();
        if ($lease) {

            $own_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                ->where('specific_use', 1)
                ->whereNotIn('category_id', [8, 5])
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })
                ->whereHas('leaseDurationClassified', function ($query) {
                    $query->where('lease_contract_duration_id', '=', '3');
                })->get();

            $own_assets_id = $own_assets->pluck('id')->toArray();

            $sublease_assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)
                ->where('specific_use', 2)
                ->whereNotIn('category_id', [8, 5])
                ->whereHas('leaseSelectLowValue', function ($query) {
                    $query->where('is_classify_under_low_value', '=', 'no');
                })
                ->whereHas('leaseDurationClassified', function ($query) {

                    $query->where('lease_contract_duration_id', '=', '3');
                })->get();

            $sublease_assets_id = $sublease_assets->pluck('id')->toArray();

            $discountrate = LeaseSelectDiscountRate::query()->whereIn('asset_id', array_merge($own_assets_id, $sublease_assets_id))->count();

            $required_discount_rate = count($own_assets_id) + count($sublease_assets_id);

            $show_next = ($required_discount_rate == $discountrate);

            return view('lease.select-discount-rate.index', compact(
                'lease',
                'own_assets',
                'sublease_assets',
                'discountrate',
                'breadcrumbs',
                'show_next'
            ));
        } else {
            abort(404);
        }
    }

    /**
     * add select discount rate details for an asset in NL-10
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

                $model = new LeaseSelectDiscountRate();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token', 'submit');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;

                    $select_discount_value = LeaseSelectDiscountRate::create($data);
                    if ($select_discount_value) {

                        // complete Step
                        confirmSteps($asset->lease->id, 12);

                        return redirect(route('addlease.discountrate.index', ['id' => $lease->id]))->with('status', 'Select Discount Rate has been added successfully.');
                    }
                }
                return view('lease.select-discount-rate.create', compact(
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
     * edit existing select discount rate details for an asset
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

                $model = LeaseSelectDiscountRate::query()->where('asset_id', '=', $id)->first();

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
                        return redirect(route('addlease.discountrate.index', ['id' => $lease->id]))->with('status', 'Select Discount Rate has been updated successfully.');
                    }
                }
                return view('lease.select-discount-rate.update', compact(
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
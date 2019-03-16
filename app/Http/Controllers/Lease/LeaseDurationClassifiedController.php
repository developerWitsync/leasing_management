<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 01/01/19
 * Time: 4:24 PM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseContractDuration;
use App\LeaseDurationClassified;
use App\LeaseAssets;

use Illuminate\Http\Request;
use Validator;

class LeaseDurationClassifiedController extends Controller
{
    private $current_step = 8;

    protected function validationRules()
    {
        return [
            'lease_start_date' => 'required',
            'lease_end_date' => 'required'

        ];
    }

    /**
     * function will be used to show the form directly for the single lease asset, since there can be only one lease asset per lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index_v2($id, Request $request)
    {
        try {
            $breadcrumbs = [
                [
                    'link' => route('add-new-lease.index'),
                    'title' => 'Add New Lease'
                ],
                [
                    'link' => route('addlease.durationclassified.create', ['id' => $id]),
                    'title' => 'Create Lease Duration Classified'
                ],
            ];

            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            if ($lease) {

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $back_button = route('addlease.residual.index', ['id' => $lease->id]);

                $category_excluded = \App\CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('status', '=', '0')
                    ->get();

                $category_excluded_id = $category_excluded->pluck('category_id')->toArray();

                $asset = LeaseAssets::query()->where('lease_id', '=', $id)
                    ->whereNotIn('category_id', $category_excluded_id)->first();

                if ($asset) {
                    if ($asset->leaseDurationClassified) {
                        $model = $asset->leaseDurationClassified;
                    } else {
                        $model = new LeaseDurationClassified();
                    }

                    if ($request->isMethod('post')) {
                        $validator = Validator::make($request->except('_token'), $this->validationRules());

                        if ($validator->fails()) {
                            return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                        }

                        $data = $request->except('_token', 'uuid', 'asset_name', 'asset_category', 'action');
                        $data['lease_id'] = $asset->lease->id;
                        $data['asset_id'] = $asset->id;
                        $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                        $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));
                        $data['lease_contract_duration_id'] = $model->getLeaseAssetClassification($asset); //we will calculate and save the same...

                        $model->setRawAttributes($data);
                        if ($model->save()) {
                            // complete Step
                            confirmSteps($lease->id, $this->current_step);

                            if ($request->has('action') && $request->action == "next") {
                                return redirect(route('lease.escalation.index', ['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been added successfully.');
                            } else {
                                return redirect(route('addlease.durationclassified.index', ['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been added successfully.');
                            }


                        }
                    }

                    //find the expected values for the end date, lease classification
                    $model->lease_end_date = $model->getExpectedLeaseEndDate($asset);
                    $model->lease_contract_duration_id = $model->getLeaseAssetClassification($asset);

                    $lease_contract_duration = LeaseContractDuration::query()->get();

                    //to get current step for steps form
                    $current_step = $this->current_step;

                    return view('lease.lease-duration-classified.create', compact(
                        'model',
                        'lease',
                        'asset',
                        'expected_lease_classification',
                        'lease_contract_duration',
                        'breadcrumbs',
                        'subsequent_modify_required',
                        'back_button',
                        'current_step'
                    ));
                } else {
                    return redirect(route('lease.escalation.index', ['id' => $id]));
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
    public function index($id, Request $request)
    {
        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.durationclassified.index', ['id' => $id]),
                'title' => 'Lease Duration Classified'
            ],
        ];
        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->with('leaseType')->with('assets')->first();
        if ($lease) {
            $back_button = route('addlease.purchaseoption.index', ['id' => $lease->id]);

            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->whereHas('terminationOption', function ($query) {
                $query->where('exercise_termination_option_available', '=', 'yes');
            })->get();

            if (count($assets) > 0) {
                $back_button = route('addlease.leaseterminationoption.index', ['id' => $lease->id]);
            }

            return view('lease.lease-duration-classified.index', compact('breadcrumbs',
                'lease',
                'back_button',
                'breadcrumbs'
            ));

        } else {
            abort(404);
        }
    }

    /**
     * add lease duration classifed value details for an asset
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
                    'link' => route('addlease.durationclassified.create', ['id' => $id]),
                    'title' => 'Create Lease Duration Classified'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();

            if ($lease) {

                $model = new LeaseDurationClassified();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));
                    $data['lease_contract_duration_id'] = $request->lease_contract_duration_id;
                    $data['expected_lease_end_Date'] = date('Y-m-d', strtotime($request->expected_lease_end_Date));


                    $duration_classified_value = LeaseDurationClassified::create($data);

                    if ($duration_classified_value) {

                        // complete Step
                        confirmSteps($lease->id, $this->current_step);

                        return redirect(route('addlease.durationclassified.index', ['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been added successfully.');
                    }
                }

                //find the expected values for the end date, lease classification
                $model->lease_end_date = $model->getExpectedLeaseEndDate($asset);
                $model->lease_contract_duration_id = $model->getLeaseAssetClassification($asset);

                $lease_contract_duration = LeaseContractDuration::query()->get();

                return view('lease.lease-duration-classified.create', compact(
                    'model',
                    'lease',
                    'asset',
                    'expected_lease_classification',
                    'lease_contract_duration',
                    'breadcrumbs'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * edit existing lease duration classified option value details for an asset
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
                    'link' => route('addlease.durationclassified.update', ['id' => $id]),
                    'title' => 'Update Lease Duration Classified'
                ],
            ];
            $asset = LeaseAssets::query()->findOrFail($id);
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $asset->lease->id)->first();
            if ($lease) {
                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                $model = LeaseDurationClassified::query()->where('asset_id', '=', $id)->first();

                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    $data = $request->except('_token');
                    $data['lease_id'] = $asset->lease->id;
                    $data['asset_id'] = $asset->id;
                    $data['lease_start_date'] = date('Y-m-d', strtotime($request->lease_start_date));
                    $data['lease_end_date'] = date('Y-m-d', strtotime($request->lease_end_date));

                    $model->setRawAttributes($data);
                    if ($model->save()) {
                        return redirect(route('addlease.durationclassified.index', ['id' => $lease->id]))->with('status', 'Lease Duration Classified Value has been updated successfully.');
                    }
                }
                $model->lease_end_date = $model->getExpectedLeaseEndDate($asset);
                $model->lease_contract_duration_id = $model->getLeaseAssetClassification($asset);

                $lease_contract_duration = LeaseContractDuration::query()->get();

                return view('lease.lease-duration-classified.update', compact(
                    'model',
                    'lease',
                    'asset',
                    'lease_contract_duration',
                    'breadcrumbs',
                    'subsequent_modify_required'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
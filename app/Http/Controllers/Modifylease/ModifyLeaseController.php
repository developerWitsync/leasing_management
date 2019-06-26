<?php
/**
 * Created by PhpStorm.
 * User: Jyoti Gupta
 * Date: 9/01/19
 * Time: 09:37 AM
 */

namespace App\Http\Controllers\Modifylease;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Lease;
use App\ModifyLeaseApplication;
use App\LeaseModificationReason;
use Validator;


class ModifyLeaseController extends Controller
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Dashboard'
            ],
            [
                'link' => route('modifylease.index'),
                'title' => 'Modify Lease'
            ]
        ];
    }

    /**
     * Render the table for all the leases
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumbs = $this->breadcrumbs;
        return view('modifylease.index', compact('breadcrumbs'));
    }

    /**
     * fetch the lease details table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function fetchLeaseDetails(Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = Lease::select('lease.*', 'contract_classifications.title as contract_classification_title')
                    ->join('contract_classifications', 'lease.lease_type_id', '=', 'contract_classifications.id')
                    ->whereIn('lease.business_account_id', getDependentUserIds())
                    ->where('lease.status', '=', '1');
                return datatables()->eloquent(
                    $model
                )
                ->filter(function ($query) use ($request){
                    if ($request->has('search') && trim($request->search["value"])!="") {
                        $query->where('lease.lessor_name', 'like', "%" . $request->search["value"] . "%");
                    }
                })
                ->toJson();
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Create  modify lease application
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id, Request $request)
    {
        try {
            $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

            $lase_modification = LeaseModificationReason::query()
                ->whereIn('business_account_id', getDependentUserIds())
                ->get();
            if ($lease) {

                $disable_initial = false;
                $subsequent_modifications_for_lease = ModifyLeaseApplication::query()->where('lease_id', '=', $id)->where('valuation', '=', 'Subsequent Valuation')->count();
                if ($subsequent_modifications_for_lease > 0) {
                    $disable_initial = true;
                }

                $model = Lease::query()->where('id', '=', $id)->first();
                if ($request->isMethod('post')) {
                    $validator = Validator::make($request->except('_token'), $this->validationRules());

                    if ($validator->fails()) {
                        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
                    }

                    if ($disable_initial && trim($request->valuation) != "Subsequent Valuation") {
                        return redirect()->back()->with('error', 'Incorrect option selected, you can only select Subsequent Valuation.');
                    }

                    $data = $request->except('_token');

                    $data['lease_id'] = $id;

                    if ($request->effective_from) {
                        $data['effective_from'] = date('Y-m-d', strtotime($request->effective_from));
                    }

                    $data['business_account_id'] = auth()->user()->id;

                    ModifyLeaseApplication::create($data);

                    //update the lease status back to 0 so that it will appear in the Drafts untill the user will submit the lease back
                    $data1['status'] = '0';
                    $model->setRawAttributes($data1);
                    $model->save();

                    //need to set the subsequent status of all the payments to '0' so that we will ask the user to update the payments once again...

                    $lease = Lease::query()->where('id', '=', $id)->first();
                    $asset = $lease->assets()->first();
                    foreach ($asset->payments as $payment){

                        $escalationModel = $payment->paymentEscalationSingle;

                        $payment->setRawAttributes([
                            'subsequent_status' => '0'
                        ]);

                        $payment->save();

                        //need to change the subsequent status of each escalation to 0 as well...
                        if($escalationModel){

                            $escalationModel->setRawAttributes([
                                'subsequent_status' => '0'
                            ]);

                            $escalationModel->save();
                        }
                    }

                    return redirect(route('add-new-lease.index', ['id' => $id]))->with('status', 'Modify Lease has been Created successfully.');
                }

                $asset = $lease->assets()->first();

                // need to send the minDate
                // minDate will be lease start date + 1 day in case there is no modification done till this step..
                // minDate will be last effective date + 1 day in case there are some modifications made till this point

                $lease_modifications_history = ModifyLeaseApplication::query()->whereIn('business_account_id', getDependentUserIds())
                    ->where('lease_id', '=', $id)
                    ->where('valuation', '=', 'Subsequent Valuation')
                    ->orderBy('created_at', 'desc')
                    ->first();
                if($lease_modifications_history) {
                    $minDate = Carbon::parse($lease_modifications_history->effective_from)->addDay(1)->format('Y-m-d');
                } else {
                    $base_date = getParentDetails()->accountingStandard->base_date;
                    if (Carbon::parse($asset->accural_period)->greaterThan(Carbon::parse($base_date))) {
                        $date = Carbon::parse($asset->accural_period)->format('Y-m-d');
                    } else {
                        $date = $base_date;
                    }
                    $minDate = Carbon::parse($date)->addDay(1)->format('Y-m-d');
                }


                $maxDate = $asset->getLeaseEndDate($asset);

                return view('modifylease.create', compact(
                    'lease',
                    'lase_modification',
                    'disable_initial',
                    'asset',
                    'minDate',
                    'maxDate'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    protected function validationRules()
    {
        return [
            'valuation' => 'required',
            'effective_from' => 'required_if:valuation,Subsequent Valuation',
            'reason' => 'required'
        ];
    }


}

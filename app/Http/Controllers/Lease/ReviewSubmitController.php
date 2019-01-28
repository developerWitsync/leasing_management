<?php
/**
 * Created by Sublime.
 * User: Jyoti Gupta
 * Date: 07/01/19
 * Time: 10:30 AM
 */

namespace App\Http\Controllers\Lease;

use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseSelectDiscountRate;
use App\LeaseDurationClassified;
use App\Countries;
use App\ReportingCurrencySettings;
use App\ContractClassifications;
use App\ForeignCurrencyTransactionSettings;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;

class ReviewSubmitController extends Controller
{
    /**
     * renders the table to list all the lease assets.
     * @param $id Primary key for the lease
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id, Request $request)
    {

        if (!checkPreviousSteps($id, 'step17')) {
            //check lease incentives
            return redirect(route('addlease.leaseasset.index', ['lease_id' => $id]))->with('status', 'Please complete the previous steps.');
        }

        $breadcrumbs = [
            [
                'link' => route('add-new-lease.index'),
                'title' => 'Add New Lease'
            ],
            [
                'link' => route('addlease.reviewsubmit.index', ['id' => $id]),
                'title' => 'Review & Submit'
            ],
        ];

        $lease = Lease::query()->whereIn('business_account_id', getDependentUserIds())->where('id', '=', $id)->first();

        if ($lease) {
            $assets = LeaseAssets::query()->where('lease_id', '=', $lease->id)->get();
            $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
            if ($request->isMethod('post')) {
                $model = Lease::query()->where('id', '=', $id)->first();
                $model->status = "1";
                $model->save();
                // complete Step
                confirmSteps($id, 'step18');
                return redirect(route('addlease.reviewsubmit.index', ['id' => $id]))->with('status', 'Lease Information has been Submitted successfully.');
            }


            return view('lease.review-submit.index', compact(
                'lease',
                'assets',
                'breadcrumbs',
                'reporting_currency_settings',
                'contract_classifications',
                'reporting_foreign_currency_transaction_settings'
            ));

        } else {
            abort(404);
        }
    }
}

    
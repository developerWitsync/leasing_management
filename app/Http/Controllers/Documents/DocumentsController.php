<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 7/6/19
 * Time: 4:34 PM
 */

namespace App\Http\Controllers\Documents;


use App\Http\Controllers\Controller;
use App\Lease;
use App\LeaseDocumentsView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class DocumentsController extends Controller
{
    /**
     * Renders the dataTable for all the leases that has been submitted by the business account.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("documents.index");
    }

    /**
     * returns the dataTable json output for all the leases that have been submitted by the business account.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchLeaseAssets(Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = Lease::select('lease.*', 'lease_assets.name', 'lease_assets.uuid', 'lease_assets.lease_start_date', 'lease_assets.lease_end_date', 'contract_classifications.title as contract_classification_title')
                    ->join('contract_classifications', 'lease.lease_type_id', '=', 'contract_classifications.id')
                    ->join('lease_assets', 'lease_assets.lease_id', '=', 'lease.id')
                    ->whereIn('lease.business_account_id', getDependentUserIds())
                    ->where('lease.status', '=', '1')->orderBy('id', 'desc');
                return datatables()->eloquent(
                    $model
                )
                    ->addColumn('lease_start_date', function ($data) {
                        return Carbon::parse($data->lease_start_date)->format(config("settings.date_format"));
                    })
                    ->addColumn('lease_end_date', function ($data) {
                        return Carbon::parse($data->lease_end_date)->format(config("settings.date_format"));
                    })
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && trim($request->search["value"]) != "") {
                            $query->where('lease_assets.name', 'like', "%" . $request->search["value"] . "%");
                            $query->orWhere('lease_assets.uuid', 'like', "%" . $request->search["value"] . "%");
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
     * renders the view for all the documents that have been uploaded for the selected lease for the current logged in business account
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listDocuments($id)
    {
        try {
            $model = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                ->where('id', '=', $id)
                ->where('status', '=', '1')
                ->firstOrFail();
            return view('documents.listdocuments', compact(
                'model'
            ));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * returns the lease documents list for all the documents that have been uploaded to the lease with the $id
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function fetchDocumentsList($id, Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = LeaseDocumentsView::query()
                    ->select('lease_documents.*')
                    ->join('lease', 'lease.id', '=', 'lease_id')
                    ->where('lease_id', '=', $id)
                    ->whereIn('lease.business_account_id', getDependentUserIds())
                    ->where('lease.status', '=', '1');
                return datatables()->eloquent(
                    $model
                )
                    ->addColumn('file', function ($data) {
                        return URL::to('uploads/' . $data->file);
                    })
                    ->addColumn('upload_date', function ($data) {
                        return Carbon::parse($data->upload_date)->format(config('settings.date_format') . " H:i:s");
                    })
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && trim($request->search["value"]) != "") {
                            $query->where('lease_documents.type', 'like', "%" . $request->search["value"] . "%");
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
}
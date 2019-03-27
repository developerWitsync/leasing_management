<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 27/3/19
 * Time: 12:09 PM
 */

namespace App\Http\Controllers\Transactions;


use App\Http\Controllers\Controller;
use App\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App;

class TransactionsController extends Controller
{
    /**
     * renders the view for listing all the transactions
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('transactions.index');
    }

    /**
     * returns the json object for the transactions to be loaded on the datatables..
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function fetch(Request $request){
        try{
            if($request->ajax()){
                return datatables()->eloquent(
                    UserSubscription::query()->whereIn('user_id', getDependentUserIds())
                    ->with('subscriptionPackage')->with('coupon')->with('user')
                    ->orderBy('created_at', 'desc')
                )
                ->filter(function ($query) use ($request){
                    if ($request->has('search') && trim($request->search["value"])!="") {
                        $query->where('invoice_number', 'like', "%" . $request->search["value"] . "%");
                    }
                })
                ->addColumn('created_at', function($data){
                    return Carbon::parse($data->created_at)->format(config('settings.date_format'));
                })
                ->addColumn('payment_status', function($data){
                    return ucfirst($data->payment_status);
                })
                ->toJson();
            } else {
                return redirect('/');
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Download the invoice file if exists else create the invoice pdf file only when the payment has been paid by the user..
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function downloadInvoice($id){
        try{
            if($id){
                $user_subscription = UserSubscription::query()->whereIn('user_id', getDependentUserIds())->where('id', '=', $id)->firstOrFail();
                $invoice_file_name = "{$user_subscription->id}_invoice.pdf";
                if(file_exists(public_path()."/uploads/".$invoice_file_name)){
                    return response()->download(public_path()."/uploads/".$invoice_file_name);
                } else {
                    return redirect()->back()->with('error', "Invoice can only be downloaded when the payment will be completed.");
                }
            } else {
                dd("asfsdf");
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }
}
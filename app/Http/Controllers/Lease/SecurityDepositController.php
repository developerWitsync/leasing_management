<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 25/3/19
 * Time: 11:59 AM
 */

namespace App\Http\Controllers\Lease;

use Carbon\Carbon;
use Validator;
use App\Http\Controllers\Controller;
use App\Lease;
use App\SecurityDeposits;
use Illuminate\Http\Request;

class SecurityDepositController extends Controller
{
    public $current_step = 19;

    /**
     * update or create the security deposit for the lease
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index($id, Request $request){
        try{
            $lease = Lease::query()->findOrFail($id);
            $asset = $lease->assets()->first(); //every lease can have only one lease asset from now...
            if($asset){

                //check if the Subsequent Valuation is applied for the lease modification
                $subsequent_modify_required = $lease->isSubsequentModification();

                if($asset->securityDeposit){
                    $model = $asset->securityDeposit;
                } else {
                    $model = new SecurityDeposits();
                }

                if($request->isMethod('post')){

                    $validator =  Validator::make($request->all(),[
                        'any_security_applicable' => 'required',
                        'refundable_or_adjustable' => 'required_if:any_security_applicable,yes|nullable',
                        'payment_date_of_security_deposit' => 'required_if:any_security_applicable,yes|date|nullable',
                        'type_of_security_deposit' => 'required_if:any_security_applicable,yes|nullable',
                        'currency' => 'required_if:any_security_applicable,yes|nullable',
                        'total_amount' => 'required_if:any_security_applicable,yes|nullable',
                        'file' => config('settings.file_size_limits.file_rule')
                    ],[
                        'file.max' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.',
                        'file.uploaded' => 'Maximum file size can be '.config('settings.file_size_limits.max_size_in_mbs').'.'
                    ]);

                    if($validator->fails()){
                        return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                    }

                    if($request->any_security_applicable == "yes"){
                        $payment_date = Carbon::parse($request->payment_date_of_security_deposit)->format('Y-m-d');

                        $uniqueFileName = '';

                        if($request->hasFile('file')){
                            $file = $request->file('file');
                            $uniqueFileName = uniqid() . $file->getClientOriginalName();
                            $request->file('file')->move('uploads', $uniqueFileName);
                        }

                        $request->request->add([
                            'asset_id' => $asset->id,
                            'lease_id' => $id,
                            'payment_date_of_security_deposit'=> $payment_date,
                            'doc' => $uniqueFileName
                        ]);
                    } else {
                        $payment_date = null;
                        $request->request->add([
                            'asset_id' => $asset->id,
                            'lease_id' => $id,
                            'payment_date_of_security_deposit'=> $payment_date,
                            'refundable_or_adjustable' => null,
                            'type_of_security_deposit' => null,
                            'currency' => null,
                            'total_amount' => null
                        ]);
                    }

                    $model->setRawAttributes($request->except('_token', 'action', 'file'));

                    $security_deposit = $model->save();

                    if($security_deposit){

                        confirmSteps($id, $this->current_step);
                        if ($request->has('action') && $request->action == "next") {
                            return redirect(route('addlease.reviewsubmit.index', ['id' => $lease->id]))->with('status', 'Security Deposit has been added successfully.');
                        } else {
                            return redirect(route('addlease.securitydeposit.index', ['id' => $lease->id]))->with('status', 'Security Deposit has been added successfully.');
                        }
                    } else {
                        return redirect()->back()->withInput($request->all())->with('error', 'Something went wrong.Please try again.');
                    }
                }

                $current_step = $this->current_step;

                $back_url = getBackUrl($this->current_step - 1, $id);

                return view('lease.security-deposit.create', compact(
                    'lease',
                    'asset',
                    'model',
                    'subsequent_modify_required',
                    'current_step',
                    'back_url'
                ));
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            dd($e);
        }
    }
}
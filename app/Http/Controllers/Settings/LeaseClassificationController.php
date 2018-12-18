<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:07 AM
 */

namespace App\Http\Controllers\Settings;


use App\ContractClassifications;
use App\Http\Controllers\Controller;
use App\LeaseAssetPaymentsNature;
use App\LeasePaymentComponents;
use App\LeasePaymentsBasis;
use App\RateTypes;
use App\UseOfLeaseAsset;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;

class LeaseClassificationController extends Controller
{
    /**
     * Renders the Lease Classifications Settings Tab for the logged in user.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $breadcrumbs = [
            [
                'link' => route('settings.index'),
                'title' => 'Settings'
            ],
            [
                'link' => route('settings.leaseclassification'),
                'title' => 'Lease Classification Settings'
            ]
        ];

        $rates = RateTypes::query()->where('status', '=', '1')->get();
        $contract_classifications = ContractClassifications::query()->where('status', '=', '1')->get();
        $lease_asset_use = UseOfLeaseAsset::query()->where('status', '=', '1')->get();
        $lease_payment_component = LeasePaymentComponents::query()->where('status', '=', '1')->get();
        $lease_payment_nature = LeaseAssetPaymentsNature::query()->where('status', '=', '1')->get();
        $lease_payment_basis = LeasePaymentsBasis::query()->where('status', '=', '1')->where('business_account_id', '=', auth()->user()->id)->get();
        return view('settings.classification.index', compact('breadcrumbs', 'rates', 'contract_classifications', 'lease_asset_use', 'lease_payment_component', 'lease_payment_nature', 'lease_payment_basis'));
    }


    public function leasePaymentBasis(Request $request){
        try{
            if($request->isMethod('post')) {

                $validator = Validator::make($request->except("_token"), [
                    'lease_basis_title' => [
                        'required',
                        Rule::unique('lease_payments_basis', 'title')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'lease_basis_title.unique' => 'This title has already been taken.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeasePaymentsBasis::create([
                    'title' => $request->lease_basis_title,
                    'business_account_id' => auth()->user()->id,
                    'status' => '1'
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Basis of Variable Lease Payment has been added successfully.');
                }
            }

            return redirect(route('settings.leaseclassification'))->with('error', 'Invalid access.');

        } catch (\Exception $e) {
            dd($e);
            abort(404);
        }
    }

    public function  editLeasePaymentBasis($id, Request $request){
        try{
            if($request->ajax()){
                $lease_payment_basis = LeasePaymentsBasis::query()->findOrFail($id);
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'lease_basis_title' => [
                            'required',
                            Rule::unique('lease_payments_basis', 'title')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'lease_basis_title.unique' => 'This title has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_payment_basis->title = $request->lease_basis_title;
                    $lease_payment_basis->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_payment_basis', compact('lease_payment_basis'));
            }
        } catch (\Exception $e) {
            dd($e);
            abort(404);
        }
    }
}
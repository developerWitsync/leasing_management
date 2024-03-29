<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:07 AM
 */

namespace App\Http\Controllers\Settings;


use App\ContractClassifications;
use App\ContractEscalationBasis;
use App\EscalationAmountCalculated;
use App\EscalationConsistencyGap;
use App\EscalationFrequency;
use App\EscalationPercentageSettings;
use App\Http\Controllers\Controller;
use App\LeaseAccountingTreatment;
use App\LeaseAssetPaymentsNature;
use App\LeaseAssetSimilarCharacteristicSettings;
use App\LeaseAssetsNumberSettings;
use App\LeaseContractDuration;
use App\LeasePaymentComponents;
use App\LeasePaymentsBasis;
use App\LeasePaymentsEscalationClause;
use App\LeasePaymentsFrequency;
use App\LeasePaymentsInterval;
use App\LeasePaymentsNumber;
use App\LeasesExcludedFromTransitionalValuation;
use App\RateTypes;
use App\UseOfLeaseAsset;
use App\LeaseModificationReason;
use App\LeaseAssetCategories;
use App\CategoriesLeaseAssetExcluded;
use App\LeaseAssets;
use Illuminate\Http\Request;
use Validator;
use Session;
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

        $rates= RateTypes::query()->select('id', 'title')->where('status', '=', '1')->get();
        $contract_classifications = ContractClassifications::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_asset_use = UseOfLeaseAsset::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_payment_component = LeasePaymentComponents::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_payment_nature  = LeaseAssetPaymentsNature::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_payment_basis  = LeasePaymentsBasis::query()->select('id', 'title')->where('status', '=', '1')->where('business_account_id', '=', auth()->user()->id)->get();

        $number_of_underlying_assets_settings  = LeaseAssetsNumberSettings::query()->select('id', 'number')->where('status', '=', '1')->whereIn('business_account_id', getDependentUserIds())->orderBy('number','asc')->get();

        $la_similar_charac_number  = LeaseAssetSimilarCharacteristicSettings::query()->select('id', 'number')->where('status', '=', '1')->whereIn('business_account_id', getDependentUserIds())->orderBy('number','asc')
            ->get();

        $contract_escalation_basis   = ContractEscalationBasis::query()->select('id', 'title')->where('status', '=', '1')->get();

        $lease_contract   = LeaseContractDuration::query()->select('id', 'title', 'month_range_description')->where('status', '=', '1')->get();

        $lease_excluded_from_transitional_valuation = LeasesExcludedFromTransitionalValuation::query()->select('id', 'title', 'value_for')->where('status', '=', '1')->get();

        $lease_accounting_treatment = collect(LeaseAccountingTreatment::query()->select('id', 'title', 'upto_year')
            ->where('status', '=', '1')->get()->toArray())->groupBy('upto_year');

        $number_of_lease_payments = LeasePaymentsNumber::query()->select('id', 'number')->whereIn('business_account_id', getDependentUserIds())->orderBy('number','asc')->get();
        $lease_payments_frequency = LeasePaymentsFrequency::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_payment_interval = LeasePaymentsInterval::query()->select('id', 'title')->where('status', '=', '1')->get();
        $lease_payment_escalation_clause = LeasePaymentsEscalationClause::query()->select('id', 'title')->where('status', '=', '1')->get();
        $escalation_amount_calculated_on = EscalationAmountCalculated::query()->select('id', 'title')->where('status', '=', '1')->get();
        $escalation_percentage_settings = EscalationPercentageSettings::query()->select('id', 'number')->whereIn('business_account_id', getDependentUserIds())->orderBy('number','asc')->get();
        $escalation_frequencies = EscalationFrequency::all();

        $modication_reason = LeaseModificationReason::query()
            ->select('id', 'title')
            ->whereIn('business_account_id', getDependentUserIds())
            ->where('status', '=', '1')
            ->get();

        $categories = LeaseAssetCategories::query()->select('id', 'title')->where('status', '=', '1')->get();
        
        $category_excluded = CategoriesLeaseAssetExcluded::query()
            ->whereIn('category_id',[5,8])
            ->where('status', '=', '0')
            ->whereIn('business_account_id', getDependentUserIds())
            ->with('leaseassetcategories')
            ->get();

        $category_excluded_all = CategoriesLeaseAssetExcluded::query()
             ->whereIn('business_account_id', getDependentUserIds())
             ->groupBy('category_id')
             ->get();

        $category_excluded_id = $category_excluded_all->pluck('category_id')->toArray();

        $escalation_consistency_gap = EscalationConsistencyGap::query()
            ->whereIn('business_account_id', getDependentUserIds())
            ->get();

        return view('settings.classification.index', compact('breadcrumbs',
            'rates',
            'contract_classifications',
            'lease_asset_use',
            'lease_payment_component',
            'lease_payment_nature',
            'lease_payment_basis',
            'number_of_underlying_assets_settings',
            'la_similar_charac_number',
            'contract_escalation_basis',
            'lease_contract',
            'lease_excluded_from_transitional_valuation',
            'lease_accounting_treatment',
            'number_of_lease_payments',
            'lease_payments_frequency',
            'lease_payment_interval',
            'lease_payment_escalation_clause',
            'escalation_amount_calculated_on',
            'escalation_percentage_settings',
            'escalation_frequencies',
            'modication_reason',
            'categories',
            'category_excluded',
            'category_excluded_id',
            'category_excluded_all',
            'escalation_consistency_gap'
        ));
    }

    /**
     * Create the lease payments basis settings for the current authenticated user.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * update an existing Lease Payment Basis for the lease classification settings
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function  editLeasePaymentBasis($id, Request $request){
        try{
            if($request->ajax()){
                $lease_payment_basis = LeasePaymentsBasis::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            Rule::unique('lease_payments_basis', 'title')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.required' => 'The title field is required.',
                        'title.unique' => 'This title has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_payment_basis->title = $request->title;
                    $lease_payment_basis->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_payment_basis', compact('lease_payment_basis'));
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Delete the lease basis setting for the current logged in user
     * @todo need to make sure that the setting cannot be deleted if a lease has been added to this setting
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeasePaymentsBasis($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_payment_basis = LeasePaymentsBasis::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($lease_payment_basis) {
                    $lease_payment_basis->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * create a new underlying lease asset number setting for the current logged in user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeaseAssetNumber(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'lease_asset_number' => [
                        'required',
                        'numeric',
                        Rule::unique('un_lease_assets_numbers_settings', 'number')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'lease_asset_number.unique' => 'This number has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeaseAssetsNumberSettings::create([
                    'number' => $request->lease_asset_number,
                    'business_account_id' => auth()->user()->id,
                    'status' => '1'
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Number of Underlying Lease Assets has been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * update an existing underlying lease asset number setting for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeaseAssetNumber($id, Request $request){
        try{
            if($request->ajax()){
                $lease_asset_number = LeaseAssetsNumberSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('un_lease_assets_numbers_settings', 'number')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_asset_number->number = $request->title;
                    $lease_asset_number->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_asset_number', compact('lease_asset_number'));
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Delete a particular underlying number of assets setting for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseAssetNumber($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_asset_number = LeaseAssetsNumberSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($lease_asset_number) {
                    $lease_asset_number->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Add Number of Lease Assets of Similar Characteristics for the current authenticated user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeaseAssetSimilarCharac(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'lease_similar_charac_number' => [
                        'required',
                        'numeric',
                        Rule::unique('la_similar_charac_number', 'number')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'lease_similar_charac_number.numeric' => 'The number should be numeric.',
                    'lease_similar_charac_number.unique' => 'This number has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeaseAssetSimilarCharacteristicSettings::create([
                    'number' => $request->lease_similar_charac_number,
                    'business_account_id' => auth()->user()->id,
                    'status' => '1'
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Number of Lease Assets of Similar Characteristics been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * update an Number of Lease Assets of Similar Characteristics for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeaseAssetSimilarCharac($id, Request $request){
        try{
            if($request->ajax()){
                $la_similar_charac_number = LeaseAssetSimilarCharacteristicSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('la_similar_charac_number', 'number')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.numeric' => 'The number should be numeric.',
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $la_similar_charac_number->number = $request->title;
                    $la_similar_charac_number->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_asset_similar_charac', compact('la_similar_charac_number'));
            }
        } catch (\Exception $e){
            abort(404);
        }
    }


    /**
     * Delete a particular Number of Lease Assets of Similar Characteristics for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseAssetSimilarCharac($id, Request $request){
        try{
            if($request->ajax()) {
                $la_similar_charac_number = LeaseAssetSimilarCharacteristicSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($la_similar_charac_number) {
                    $la_similar_charac_number->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }


    /**
     * Add Number of Lease Payments for the current authenticated user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeasePaymentsNumber(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'lease_payments_no' => [
                        'required',
                        'numeric',
                        'max:'.config('settings.max_lease_asset_number_of_payments').'.',
                        Rule::unique('no_of_lease_payments', 'number')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'lease_payments_no.numeric' => 'The number should be numeric.',
                    'lease_payments_no.unique' => 'This number has already been added.',
                    'lease_payments_no.max' => 'This number should not be greater than 10',
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeasePaymentsNumber::create([
                    'number' => $request->lease_payments_no,
                    'business_account_id' => auth()->user()->id
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Number of Lease Payments been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * update an Number of Lease Payments for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeasePaymentsNumber($id, Request $request){
        try{
            if($request->ajax()){
                $lease_payments_no = LeasePaymentsNumber::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('no_of_lease_payments', 'number')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.numeric' => 'The number should be numeric.',
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_payments_no->number = $request->title;
                    $lease_payments_no->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_payments_number', compact('lease_payments_no'));
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }

    /**
     * Delete aan Number of Lease Payments for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeasePaymentsNumber($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_payments_no = LeasePaymentsNumber::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($lease_payments_no) {
                    $lease_payments_no->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Add Escalation Percentages Number for the current authenticated user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addEscalationPercentageNumber(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'escalation_percentage_number' => [
                        'required',
                        'numeric',
                        Rule::unique('escalation_percentage_settings', 'number')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'escalation_percentage_number.numeric' => 'The number should be numeric.',
                    'escalation_percentage_number.unique' => 'This number has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = EscalationPercentageSettings::create([
                    'number' => $request->escalation_percentage_number,
                    'business_account_id' => auth()->user()->id,
                    'status'    => '1'
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Escalation Percentages Number been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * create escalation consistency gap for the current logged in user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addEscalationConsistencyGap(Request $request){
        try{
            if($request->isMethod('post')){
                $validator = Validator::make($request->except("_token"), [
                    'escalation_consistency_gap' => [
                        'required',
                        'numeric',
                        Rule::unique('escalation_consistency_gap', 'years')->where(function ($query) use ($request) {
                            return $query->where('business_account_id', '=', auth()->user()->id);
                        })
                    ]
                ], [
                    'escalation_consistency_gap.numeric' => 'The escalation consistency gap should be numeric.',
                    'escalation_consistency_gap.unique' => 'This escalation consistency gap has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = EscalationConsistencyGap::create([
                    'years' => $request->escalation_consistency_gap,
                    'business_account_id' => auth()->user()->id
                ]);

                if($model){
                    return redirect()->back()->with('status', 'Escalation Consistency Gap been added successfully.');
                }

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * edit the escalation consistency gap year for the current logged in business account
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editEscalationConsistencyGap($id, Request $request){
        try{
            if($request->ajax()){

                $escalation_consistency_gap = EscalationConsistencyGap::query()->where('id', $id)
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->first();

                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('escalation_consistency_gap', 'years')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.numeric' => 'The number should be numeric.',
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $escalation_consistency_gap->years = $request->title;
                    $escalation_consistency_gap->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_escalation_consistency_gap', compact('escalation_consistency_gap'));
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * delete the escalation consistency Gap for the current logged in business account
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteEscalationConsistencyGap($id, Request $request){
        try{
            if($request->ajax()) {
                $escalation_consistency_gap = EscalationConsistencyGap::query()
                    ->where('id', $id)
                    ->whereIn('business_account_id', getDependentUserIds());
                if($escalation_consistency_gap) {
                    $escalation_consistency_gap->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * update an Escalation Percentages Number for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editEscalationPercentageNumber($id, Request $request){
        try{
            if($request->ajax()){
                $escalation_percentage_number = EscalationPercentageSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id)->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => [
                            'required',
                            'numeric',
                            Rule::unique('escalation_percentage_settings', 'number')->where(function ($query) use ($request) {
                                return $query->where('business_account_id', '=', auth()->user()->id);
                            })->ignore($id,'id')
                        ]
                    ], [
                        'title.numeric' => 'The number should be numeric.',
                        'title.required' => 'The number field is required.',
                        'title.unique' => 'This number has already been taken.'
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $escalation_percentage_number->number = $request->title;
                    $escalation_percentage_number->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_escalation_percentage_number', compact('escalation_percentage_number'));
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * Delete aan Number of Lease Payments for the current authenticated user
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteEscalationPercentageNumber($id, Request $request){
        try{
            if($request->ajax()) {
                $escalation_percentage_number = EscalationPercentageSettings::query()->where('id', $id)->where('business_account_id', '=', auth()->user()->id);
                if($escalation_percentage_number) {
                    $escalation_percentage_number->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

     /**
     * Add title of Lease Modification Reason
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addLeaseModificationReason(Request $request){
        try{
            if($request->isMethod('post')) {
                $validator = Validator::make($request->except("_token"), [
                    'title' => 'required'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
                }

                $model = LeaseModificationReason::create([
                    'title' => $request->title,
                    'business_account_id' => getParentDetails()->id
                 ]);

                if($model){
                    return redirect()->back()->with('status', 'Lease Modification Reason been added successfully.');
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }


    /**
     * update Title of Lease Modification Reason
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function editLeaseModificationReason($id, Request $request){
        try{
            if($request->ajax()){
                $lease_reason = LeaseModificationReason::query()
                    ->where('id', $id)
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->first();
                if($request->isMethod('post')) {
                    $validator = Validator::make($request->except("_token"), [
                        'title' => ['required',]
                    ]);

                    if($validator->fails()){
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }

                    $lease_reason->title = $request->title;
                    $lease_reason->save();
                    return response()->json([
                        'status' =>true,
                        'message' => 'Settings has been saved successfully.'
                    ]);
                }

                return view('settings.classification._edit_lease_modification_reason', compact('lease_reason'));
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }

    /**
     * Delete a Title of Lease Modification Reason
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteLeaseModificationReason($id, Request $request){
        try{
            if($request->ajax()) {
                $lease_reason = LeaseModificationReason::query()->where('id', $id);
                if($lease_reason) {
                    $lease_reason->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

     /**
     * Add Categories of Lease Assets Excluded
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCategoriesExcluded($id,Request $request){
        try{
            if($request->ajax()) {
                
                $category_id = $id;

                $model = CategoriesLeaseAssetExcluded::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->where('category_id', '=', $category_id)
                    ->first();

                if(is_null($model)){
                    $model = new CategoriesLeaseAssetExcluded();
                }

                $model->category_id = $category_id;
                $model->business_account_id = getParentDetails()->id;
                $model->status = $request->status;

                if($model->save()){
                    Session::flash('status', 'Setting has been added successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

}
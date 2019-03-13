<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 12/3/19
 * Time: 4:20 PM
 */

namespace App\Http\Controllers\Settings;

use Validator;
use Session;
use App\Countries;
use App\Http\Controllers\Controller;
use App\LeaseAssetCountries;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaseAssetCountriesController extends Controller
{
    /**
     * fetches the lease asset countries added by the business account...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = LeaseAssetCountries::query()
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->with('country');

                return datatables()->eloquent($model)->toJson();

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * creates the country for the logged in user...
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        try {
            $model = new LeaseAssetCountries();
            $countries = Countries::query()->where('status', '=', '1')->get();
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'country_id' => [
                        'required',
                        'exists:countries,id',
                        Rule::unique('lease_asset_countries', 'country_id')->where(function ($query) use ($request) {
                            return $query->whereIn('business_account_id', getDependentUserIds());
                        })
                    ]
                ],[
                    'country_id.unique' => 'This country has already been added.'
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
                }

                $model = LeaseAssetCountries::create([
                    'country_id' => $request->country_id,
                    'business_account_id' => getParentDetails()->id
                ]);

                if($model){
                    return redirect(route('settings.index'))->with('status', 'Country has been added successfully.');
                }
            }
            return view('settings.general.leaseassetcountries.create', compact(
                'model',
                'countries'
            ));
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * delete a country setting
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete($id, Request $request){
        try{
            if($request->ajax()) {

                $setting = LeaseAssetCountries::query()
                    ->where('id','=', $id)
                    ->whereIn('business_account_id', getDependentUserIds())
                    ->first();

                if($setting) {
                    $setting->delete();
                    Session::flash('status', 'Setting has been deleted successfully.');
                    return response()->json(['status' => true], 200);
                } else {
                    return response()->json(['status' => false, "message" => "Invalid request!"], 200);
                }
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e){
            dd($e);
            abort(404);
        }
    }
}
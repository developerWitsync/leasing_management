<?php
/**
 * Created by PhpStorm.
 * User: himanshu
 * Date: 14/12/18
 * Time: 11:20 AM
 */

namespace App\Http\Controllers\Settings;


use App\Currencies;
use App\ExchangeRates;
use App\Exports\CurrencyExchangeRates;
use App\ForeignCurrencyTransactionSettings;
use App\GeneralSettings;
use App\Http\Controllers\Controller;
use App\Imports\ExchangeRatesImport;
use App\ReportingCurrencySettings;
use App\Lease;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\try_fopen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use DB;

class CurrenciesController extends Controller
{
  /**
   * renders the view for the currencies settings for the current logged in user
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
  {
    $breadcrumbs = [
      [
        'link' => route('settings.index'),
        'title' => 'Settings'
      ],
      [
        'link' => route('settings.currencies'),
        'title' => 'Currencies Settings'
      ]
    ];

    $currencies = Currencies::query()->where('status', '=', '1')->get();
    $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();

    if (collect($reporting_currency_settings)->isEmpty()) {
      $reporting_currency_settings = new ReportingCurrencySettings();
    }

    $countforeign = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->get();

    $foreign_currency = $countforeign->pluck('foreign_exchange_currency')->toArray();

    $exsist_froegincurrency = Lease::query()->whereIn('business_account_id', getDependentUserIds())->whereIn('lease_contract_id', $foreign_currency)->count();

    $foreign_currencies = ForeignCurrencyTransactionSettings::query()
      ->whereIn('business_account_id', getDependentUserIds())
      ->get()
      ->pluck('foreign_exchange_currency')
      ->toArray();

    return view('settings.currencies.index', compact(
      'breadcrumbs',
      'currencies',
      'reporting_currency_settings',
      'internal_same_as_statutory_currency',
      'currency_for_lease_reports_same_as_statutory',
      'currency_for_lease_reports_same_as_internal',
      'exsist_froegincurrency',
      'foreign_currencies'
    ));
  }

  /**
   * create and update the reporting currencies for the current authenticated user
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function save(Request $request)
  {
    try {
      $validator = Validator::make($request->except('_token'), [
        'statutory_financial_reporting_currency' => 'required|exists:currencies,code',
        //'internal_same_as_statutory_reporting' => 'required',
        //'internal_company_financial_reporting_currency' => 'required_if:internal_same_as_statutory_reporting,no',
        'lease_report_same_as_statutory_reporting' => 'required',
        'currency_for_lease_reports' => 'required_if:lease_report_same_as_statutory_reporting,3'
      ], [
        //'internal_same_as_statutory_reporting.required' => 'Please select any one option.',
        //'internal_company_financial_reporting_currency.required_if' => 'This field is required.',
        'lease_report_same_as_statutory_reporting.required' => 'Please select any one option.',
        'currency_for_lease_reports.required_if' => 'This field is required'
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withInput($request->except('_token'))->withErrors($validator->errors());
      }

      $request->request->add(['business_account_id' => auth()->user()->id]);

      //if the settings alredy exists for the user than delete the current existing settings and than create the new settings.
      $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
      if ($reporting_currency_settings) {
        $reporting_currency_settings->delete();
      }
      ReportingCurrencySettings::create($request->except('_token'));
      return redirect()->back()->with('status', 'Reporting Currency Settings has been saved successfully.');
    } catch (\Exception $e) {
      dd($e);
      abort(404);
    }
  }

  /**
   * update the is foreign transaction currency involved to yes or no
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function udpateIsForeignTransactionCurrencyInvolved(Request $request)
  {
    try {
      if ($request->ajax()) {
        $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
        if ($reporting_currency_settings) {
          $reporting_currency_settings->is_foreign_transaction_involved = isset($request->is_foreign_transaction_involved) ? $request->is_foreign_transaction_involved : "no";
          $reporting_currency_settings->save();
          return response()->json([
            'status' => true
          ], 200);
        } else {
          return response()->json([
            'status' => false,
            'message' => 'Please set the reporting currencies settings first.'
          ], 200);
        }
      } else {
        return redirect(route('settings.currencies'));
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * Create a new Foreign Currency Transaction setting for the current logged in user
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   */
  public function addForeignTransactionCurrency(Request $request)
  {
    try {
      $breadcrumbs = [
        [
          'link' => route('settings.index'),
          'title' => 'Settings'
        ],
        [
          'link' => route('settings.currencies'),
          'title' => 'Currencies Settings'
        ]
      ];

      $reporting_currency_settings = ReportingCurrencySettings::query()
        ->whereIn('business_account_id', getDependentUserIds())
        ->first();

      $foreign_currencies = ForeignCurrencyTransactionSettings::query()
        ->whereIn('business_account_id', getDependentUserIds())
        ->get()
        ->pluck('foreign_exchange_currency')
        ->toArray();

      if ($reporting_currency_settings) {
        $currencies = Currencies::query()->where('status', '=', '1')->get();

        if ($request->isMethod('post')) {
          $validator = Validator::make($request->except('_token'), [
            'foreign_exchange_currency' => 'required|exists:currencies,code',
//                        'valid_from'                => 'required|date',
//                        'exchange_rate'             => 'required|numeric',
//                        'valid_to'                  => 'required|date|after:valid_from'
          ], [
            'foreign_exchange_currency.required' => 'The currency field is required.',
//                        'exchange_rate.required' => 'The rate field is required.',
//                        'exchange_rate.numeric' => 'The rate field must be a number.',
//                        'valid_from.required'   => 'The from date field is required',
//                        'valid_from.date'   => 'The from date must be a valid date.',
//                        'valid_to.required'   => 'The to date field is required',
//                        'valid_to.date'   => 'The to date must be a valid date.',
          ]);

          if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
          }

          $request->request->add(['base_currency' => $reporting_currency_settings->currency_for_lease_reports, 'business_account_id' => auth()->user()->id]);

          $data = $request->except('_token');

//                    $data['valid_from']= date('Y-m-d', strtotime($request->valid_from));

//                    $data['valid_to'] = date('Y-m-d', strtotime($request->valid_to));

          ForeignCurrencyTransactionSettings::create($data);

          return redirect(route('settings.currencies'))->with('status', 'Foreign Transaction Currency has been added successfully.');

        }
        return view('settings.currencies.add-foreign-transaction-currency',
          compact(
            'currencies',
            'reporting_currency_settings',
            'breadcrumbs',
            'foreign_currencies'
          ));
      } else {
        return redirect(route('settings.currencies'))->with('error', 'Please create the Reporting currencies first.');
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * fetch the foreign transaction currencies for the current logged in user
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   */
  public function fetchForeignTransactionCurrency(Request $request)
  {
    try {

      if ($request->ajax()) {
        $model = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id);
        return datatables()->eloquent($model)
          ->filter(function ($query) use ($request) {
            if ($request->has('search') && trim($request->search["value"]) != "") {
              $query->where('foreign_exchange_currency', 'like', "%" . $request->search["value"] . "%");
            }
          })
          ->addColumn('created_at', function ($data) {
            return date('jS F Y h:i a', strtotime($data->created_at));
          })
          ->addColumn('is_used', function ($data) {
            $is_used = Lease::query()
              ->whereIn('business_account_id', getDependentUserIds())
              ->where('lease_contract_id', '=', $data->foreign_exchange_currency)
              ->count();
            return $is_used;
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
   * @todo need to place the condition so that a user cannot delete a foreign transaction currency if it have been used with some lease or lease asset
   * @param $id
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
   */
  public function deleteForeignTransactionCurrency($id, Request $request)
  {
    try {
      if ($request->ajax()) {
        $model = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->where('id', '=', $id)->first();
        if ($model) {
          $model->delete();
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

  /**
   * update an existing foreign transaction currency setting for the current logged in user
   * @param $id
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   */
  public function editForeignTransactionCurrency($id, Request $request)
  {
    try {
      $breadcrumbs = [
        [
          'link' => route('settings.index'),
          'title' => 'Settings'
        ],
        [
          'link' => route('settings.currencies'),
          'title' => 'Currencies Settings'
        ]
      ];
      $currencies = Currencies::query()->where('status', '=', '1')->get();
      $reporting_currency_settings = ReportingCurrencySettings::query()->where('business_account_id', '=', auth()->user()->id)->first();
      $model = ForeignCurrencyTransactionSettings::query()->where('business_account_id', '=', auth()->user()->id)->where('id', '=', $id)->first();
      if ($reporting_currency_settings && $model) {
        if ($request->isMethod('post')) {
          $validator = Validator::make($request->except('_token'), [
            'foreign_exchange_currency' => 'required|exists:currencies,code',
//                        'valid_from'                => 'required|date',
//                        'exchange_rate'             => 'required|numeric',
//                        'valid_to'                  => 'required|date|after:valid_from'
          ], [
            'foreign_exchange_currency.required' => 'The currency field is required.',
//                        'exchange_rate.required' => 'The rate field is required.',
//                        'exchange_rate.numeric' => 'The rate field must be a number.',
//                        'valid_from.required'   => 'The from date field is required',
//                        'valid_from.date'   => 'The from date must be a valid date.',
//                        'valid_to.required'   => 'The to date field is required',
//                        'valid_to.date'   => 'The to date must be a valid date.',
          ]);

          if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->except("_token"));
          }

          $request->request->add(['base_currency' => $reporting_currency_settings->currency_for_lease_reports, 'business_account_id' => auth()->user()->id]);

          $data = $request->except('_token');

//                    $data['valid_from'] = date('Y-m-d', strtotime($request->valid_from));
//
//                    $data['valid_to']   = date('Y-m-d', strtotime($request->valid_to));

          $model->setRawAttributes($data);

          $model->save();

          return redirect(route('settings.currencies'))->with('status', 'Foreign Transaction Currency has been updated successfully.');

        }
        return view('settings.currencies.edit-foreign-transaction-currency', compact('currencies', 'reporting_currency_settings', 'model', 'breadcrumbs'));
      } else {
        return redirect(route('settings.currencies'))->with('error', 'Invalid request!!!');
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * renders the import exchange rates view for the selected foreign currency
   * @param $id
   * @param Request $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function setExchangeRate($id, Request $request)
  {
    try {
      $model = ForeignCurrencyTransactionSettings::query()
        ->whereIn('business_account_id', getDependentUserIds())
        ->where('id', '=', $id)
        ->firstOrFail();
      $showExchangeRates = false;
      $start_year = null;
      $end_year = null;
      if($model->exchangeRates()->count()){
        $showExchangeRates = true;
        $start_year = Carbon::parse($model->exchangeRates()->orderBy('date', 'asc')->first()->date)->format('Y');
        $end_year = Carbon::parse($model->exchangeRates()->orderBy('date', 'desc')->first()->date)->format('Y');
      }
      return view('settings.currencies.exchangerates', compact(
        'model',
        'showExchangeRates',
        'start_year',
        'end_year'
      ));
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * generate and download the xlsx file for importing the currency exchange rates
   * @param $id
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   */
  public function downloadRatesExcel($id, Request $request)
  {
    try {
      $model = ForeignCurrencyTransactionSettings::query()
        ->whereIn('business_account_id', getDependentUserIds())
        ->where('id', '=', $id)
        ->firstOrFail();
      //get the minimum and maximum years settings to generate the xlsx for download
      $settings = GeneralSettings::query()
        ->whereIn('business_account_id', getDependentUserIds())
        ->firstOrFail();

      $min_year = $settings->min_previous_first_lease_start_year;
      $max_year = $settings->max_lease_end_year;

      $start_date = Carbon::create($min_year, 01, 01)->format('Y-m-d');
      $end_date = Carbon::create($max_year, 01, 01)->lastOfYear()->format('Y-m-d');
      $dates = getDatesFromRange($start_date, $end_date);
      $final_data = [];
      foreach ($dates as $date) {
        $final_data[] = [
          'date' => $date,
          'statutory_currency' => $model->base_currency,
          'foreign_currency' => $model->foreign_exchange_currency,
          'rate' => 1
        ];
      }
      $file_name = 'import_rate_' . md5(time());
      if (Excel::store(new CurrencyExchangeRates($final_data), $file_name . ".xlsx", 'custom')) {
        return response()->download(public_path() . '/uploads/' . $file_name . ".xlsx");
      } else {
        abort(404);
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  /**
   * imports the exchange rates for the currency and saves them to the database as well.
   * @param $id
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function saveExchangeRates($id, Request $request)
  {
    try {
      if ($request->hasFile('import_excel')) {
        $validator = Validator::make(
          [
            'import_excel' => $request->import_excel,
            'extension' => strtolower($request->import_excel->getClientOriginalExtension()),
          ],
          [
            'import_excel' => 'file|required|max:2000|nullable',
            'extension' => 'required|in:xlsx,xls',
          ],
          [
            'import_excel.max' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.',
            'import_excel.uploaded' => 'Maximum file size can be ' . config('settings.file_size_limits.max_size_in_mbs') . '.'
          ]
        );

        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator->errors());
        }

        $file = $request->file('import_excel');
        $uniqueFileName = uniqid() . $file->getClientOriginalName();
        $request->file('import_excel')->move('uploads', $uniqueFileName);
        $data = (new ExchangeRatesImport())->toArray(public_path('uploads/' . $uniqueFileName));

        if (!empty($data)) {
          $data = $data[0];
          if (!empty($data)) {
            unset($data[0]);
          }
        }

        unlink(public_path('uploads/' . $uniqueFileName));

        $model = ForeignCurrencyTransactionSettings::query()
          ->whereIn('business_account_id', getDependentUserIds())
          ->where('id', '=', $id)
          ->firstOrFail();
        $final_data = [];
        foreach ($data as $datum){
          $internal = [];
          $internal['foreign_currency_id'] = $model->id;
          $internal['date'] = $datum[0];
          $internal['rate'] = $datum[3];
          $internal['created_at'] = date('Y-m-d H:i:s');
          $internal['updated_at'] = date('Y-m-d H:i:s');
          $final_data[] = $internal;
        }

        DB::transaction(function () use ($final_data, $id) {
          DB::table('exchange_rates')->where('foreign_currency_id', '=', $id)->delete();
          DB::table('exchange_rates')->insert($final_data);
        });

        return redirect()->back()->with('status', 'Exchange rates have been saved successfully.');

      } else {
        return redirect()->back()->with('error', 'Please choose the xlsx file from which the exchange rates will be imported. You can first download the template for the same by clicking on the "Download Excel for Importing" button.');
      }
    } catch (\Exception $e) {
      abort(404);
    }
  }

  public function getExchangeRates(Request $request){
    try{
      $validator = Validator::make($request->all(), [
        'id' => 'required|exists:foreign_currency_transaction_settings,id',
        'year' => 'required'
      ]);

      if($validator->fails()){
        return response()->json([
          'status' => false,
          'message' => $validator->errors()->first()
        ], 200);
      }

      $model = ExchangeRates::query()
        ->where('foreign_currency_id', '=', $request->id)
        ->whereYear('date', '=', $request->year)
        ->orderBy('date', 'asc')
        ->get()->toArray();
      $final_data = [];
      foreach ($model as $item){
        $final_data[Carbon::parse($item['date'])->format('F')][] = $item;
      }

      return view('settings.currencies._render_rates',compact(
        'final_data'
      ));

    } catch (\Exception $e) {
      dd($e);
    }
  }

}
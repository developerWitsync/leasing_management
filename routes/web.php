<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('test-mail', 'Master\IndexController@testMail');

Route::get('/', 'Master\IndexController@index');

Route::get('information/{slug}', ['as' => 'information.index', 'uses' => 'Master\IndexController@information']);

Route::get('about-us', ['as' => 'information.about', 'uses' => 'Master\IndexController@about']);

Route::get('services-we-offer', ['as' => 'information.services', 'uses' => 'Master\IndexController@services']);

Route::get('e-learning', ['as' => 'information.eLearning', 'uses' => 'Master\IndexController@eLearning']);

Route::namespace('Master')->prefix('leasing-software')->group(function () {
    Route::get('/', ['as' => 'master.leasingsoftware.index', 'uses' => 'LeasingSoftwareController@index']);
});

Route::namespace('Master')->prefix('pricing')->group(function () {
    Route::get('leasing-software', ['as' => 'master.pricing.index', 'uses' => 'PricingController@index']);
    Route::get('vat-e-learning', ['as' => 'master.pricing.vatelearning', 'uses' => 'PricingController@vatELearning']);
    Route::post('plan-selected', ['as' => 'master.pricing.subscribe', 'uses' => 'PricingController@planSelected']);
    Route::post('build-your-plan', ['as' => 'master.pricing.buildyourplan', 'uses' => 'PricingController@buildYourPlan']);
    Route::post('calc-cart', ['as' => 'master.pricing.calccart', 'uses' => 'PricingController@calculateCart']);
});

Auth::routes();

Route::get('register', ['as' => 'register', 'uses' => 'RegisterController@index']);
Route::post('create-account', ['as' => 'register.create', 'uses' => 'RegisterController@register']);
Route::get('fetch-states/{country_id}', ['as' => 'register.fetch.states', 'uses' => 'RegisterController@fetchStates']);

Route::prefix('payment')->group(function () {
    Route::any('success', ['as' => 'payment.success', 'uses' => 'PaymentController@success']);
    Route::any('cancel', ['as' => 'payment.cancel', 'uses' => 'PaymentController@cancel']);
    Route::any('notify', ['as' => 'payment.notify', 'uses' => 'PaymentController@notify']);
});

Route::middleware('auth')->group(function () {

    Route::get('invoice/{id}', ['as' => 'invoice.index', 'uses' => 'InvoiceController@index']);

    Route::get('/home', 'HomeController@index')->middleware(['permission:dashboard'])->name('home');
    Route::get('/home/consolidated-chart', ['as' => 'home.chart.consolidatedchart', 'uses' => 'HomeController@consolidatedChart']);
    Route::get('/home/categorised-charts', ['as' => 'home.fetchdetails', 'uses' => 'HomeController@categorisedChart']);

    Route::prefix('plan')->middleware(['permission:manage_subscription'])->group(function () {
        Route::get('/', ['as' => 'plan.index', 'uses' => 'UpgradeController@index']);
        Route::post('purchase', ['as' => 'plan.purchase', 'uses' => 'UpgradeController@purchase']);
        Route::get('success', ['as' => 'plan.purchase.success', 'uses' => 'UpgradeController@success']);
        Route::get('cancel', ['as' => 'plan.purchase.cancel', 'uses' => 'UpgradeController@cancel']);
        Route::get('upgrade-downgrade-info/{plan}', ['as' => 'plan.purchase.updowninfo', 'uses' => 'UpgradeController@changePlanDetails']);

        Route::get('subscription-selection/{plan}', ['as' => 'plan.purchase.subscriptionselection', 'uses' => 'UpgradeController@subscriptionSelection']);
        Route::get('show-subscription-plan-adjustments', ['as' => 'plan.purchase.showadjustments', 'uses' => 'UpgradeController@showadjustments']);

    });

    Route::namespace('Transactions')->middleware(['permission:manage_subscription'])->prefix('transactions')->group(function(){
        Route::get('/', ['as' => 'transactions.index', 'uses' => 'TransactionsController@index']);
        Route::get('fetch-transactions', ['as' => 'transactions.fetch', 'uses'=>'TransactionsController@fetch']);
        Route::get('download-invoice/{id}', ['as'=> 'transactions.download.invoice', 'uses'=>'TransactionsController@downloadInvoice']);
    });

    Route::namespace('Lease')->middleware(['permission:add_lease', /*'checksubscription:add_lease'*/])->prefix('lease')->group(function () {
        // To check Lock Period Date
        Route::get('checklockperioddate', ['as' => 'lease.checklockperioddate', 'uses' => 'IndexController@checkLockPeriodDate']);
        /**
         * Lessor Details Routes NL1
         */
        Route::prefix('lessor-details')->group(function () {
            Route::match(['post', 'get'], 'create/{id?}', ['as' => 'add-new-lease.index', 'uses' => 'LessorDetailsController@index'])->middleware('checksubscription:add_lease,lease_id,id');
            Route::post('save', ['as' => 'add-new-lease.index.save', 'uses' => 'LessorDetailsController@save']);
            Route::post('update/{id}', ['as' => 'add-new-lease.index.update', 'uses' => 'LessorDetailsController@udpate'])->middleware('checksubscription:add_lease,lease_id,id');
            Route::post('udpate-total-assets/{id}', ['as' => 'add-new-lease.index.updatetotalassets', 'uses' => 'LessorDetailsController@udpateTotalAssets'])->middleware('checksubscription:add_lease,lease_id,id');
        });

        /**
         * Underlying Lease Assets Routes NL2
         */
        Route::prefix('underlying-lease-assets')->group(function () {
            //Route::match(['post', 'get'],'create/{id}', ['as' => 'addlease.leaseasset.index', 'uses' => 'UnderlyingLeaseAssetController@index']); // commented out to show the form for one lease asset directly
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.leaseasset.index', 'uses' => 'UnderlyingLeaseAssetController@index_V2'])->middleware('checksubscription:add_lease,lease_id,id');
            Route::get('fetch-sub-categories/{id}', ['as' => 'addlease.leaseasset.fetchsubcategories', 'uses' => 'UnderlyingLeaseAssetController@fetchSubCategories']);
            Route::match(['post', 'get'], 'complete-asset-details/{lease}/{asset}', ['as' => 'addlease.leaseasset.completedetails', 'uses' => 'UnderlyingLeaseAssetController@assetDetails']);
            Route::post('save/{id}', ['as' => 'addlease.leaseasset.saveasset', 'uses' => 'UnderlyingLeaseAssetController@save']);
            Route::match(['post', 'get'], 'getdatedifference', ['as' => 'addlease.leaseasset.getdatedifference', 'uses' => 'UnderlyingLeaseAssetController@getDateDifference']);
        });
        /*
        * Lease Termination Options Routes NL6
        */
        Route::prefix('lease-termination-option')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.leaseterminationoption.index', 'uses' => 'LeaseTerminationOptionController@index_V2'])->middleware('checkpreviousdata:2,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.leaseterminationoption.create', 'uses' => 'LeaseTerminationOptionController@create'])->middleware('checkpreviousdata:2,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.leaseterminationoption.update', 'uses' => 'LeaseTerminationOptionController@update'])->middleware('checkpreviousdata:2,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Renewable Value NL7
         */
        Route::prefix('lease-renewal-option')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.renewable.index', 'uses' => 'LeaseRenewableOptionController@index'])->middleware('checkpreviousdata:step6,lease_id,id');

            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.renewable.index', 'uses' => 'LeaseRenewableOptionController@index_V2'])->middleware('checkpreviousdata:3,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.renewable.create', 'uses' => 'LeaseRenewableOptionController@create'])->middleware('checkpreviousdata:3,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.renewable.update', 'uses' => 'LeaseRenewableOptionController@update'])->middleware('checkpreviousdata:3,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /*
         * Purchase Option Routes NL8
         */
        Route::prefix('purchase-option')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.purchaseoption.index', 'uses' => 'PurchaseOptionController@index_V2'])->middleware('checkpreviousdata:4,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::post('save', ['as' => 'addlease.purchaseoption.save', 'uses' => 'PurchaseOptionController@store']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.purchaseoption.create', 'uses' => 'PurchaseOptionController@create'])->middleware('checkpreviousdata:4,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.purchaseoption.update', 'uses' => 'PurchaseOptionController@update'])->middleware('checkpreviousdata:4,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Lease Payments Routes NL3
         */
        Route::prefix('payments')->group(function () {
            Route::get('index/{id}', ['as' => 'addlease.payments.index', 'uses' => 'LeasePaymentsController@index'])->middleware('checkpreviousdata:5,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::post('save-total-payments/{id}', ['as' => 'lease.payments.savetotalpayments', 'uses' => 'LeasePaymentsController@saveTotalPayments']);
            Route::get('fetch-asset-payments/{id}', ['as' => 'lease.payments.fetchassetpayments', 'uses' => 'LeasePaymentsController@fetchAssetPayments']);
            Route::match(['post', 'get'], 'create-asset-payment/{id}', ['as' => 'lease.payments.createassetpayment', 'uses' => 'LeasePaymentsController@createAssetPayments']);
            Route::match(['post', 'get'], 'update-asset-payment/{id}/{payment_id}', ['as' => 'lease.payments.updateassetpayment', 'uses' => 'LeasePaymentsController@updateAssetPayments']);
            Route::get('lease-asset-payment-due-dates-annexure', ['as' => 'lease.payments.duedatesannexure', 'uses' => 'LeasePaymentsController@dueDatesAnnexure']);
            Route::get('lease-asset-payment-due-dates-view-dates/{id}', ['as' => 'addlease.payments.showpaymentdates', 'uses' => 'LeasePaymentsController@viewExistingDates']);
            Route::post('payment-annexure-inconsistent-per-interval', ['as' => 'addlease.payments.inconsitentperintervalannexure', 'uses' => 'LeasePaymentsController@inconsistentIntervalAnnexure']);
            Route::get('load-payment-annexure-inconsistent/{payment_id}', ['as' => 'addlease.payments.loadinconsistentannexure', 'uses' => 'LeasePaymentsController@loadInconsistentAnnexure']);

            Route::delete('delete-lease-asset-payment', ['as' => 'addlease.payments.delete', 'uses' => 'LeasePaymentsController@delete']);

            Route::post('generate-excel-format-for-import', ['as' => 'addlease.payments.inconsistent_download_excel', 'uses' => 'LeasePaymentsController@generateExcelForImport']);

            Route::post('import-excel-for-inconsistent', ['as' => 'addlease.payments.inconsistent_import_excel', 'uses' => 'LeasePaymentsController@importExcelInconsistent']);
        });


        /**
         * Residual Value Guarantee Routes NL5
         */
        Route::prefix('residual-value-gurantee')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.residual.index', 'uses' => 'LeaseResidualController@create_V2'])->middleware('checkpreviousdata:6,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.residual.create', 'uses' => 'LeaseResidualController@create'])->middleware('checkpreviousdata:6,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.residual.update', 'uses' => 'LeaseResidualController@update'])->middleware('checkpreviousdata:6,asset_id,id','checksubscription:add_lease,asset_id,id');
        });


        /**
         * Lease Duration Classified Value NL8.1
         */
        Route::prefix('lease-duration-classified')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.durationclassified.index', 'uses' => 'LeaseDurationClassifiedController@index'])->middleware('checkpreviousdata:step8,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.durationclassified.index', 'uses' => 'LeaseDurationClassifiedController@index_V2'])->middleware('checkpreviousdata:7,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.durationclassified.create', 'uses' => 'LeaseDurationClassifiedController@create'])->middleware('checkpreviousdata:7,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.durationclassified.update', 'uses' => 'LeaseDurationClassifiedController@update'])->middleware('checkpreviousdata:7,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Lease Escalation Clause Routes NL9
         */
        Route::prefix('escalation')->group(function () {
            Route::get('index/{id}', ['as' => 'lease.escalation.index', 'uses' => 'EscalationController@index'])->middleware('checkpreviousdata:8,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::post('update-lease-escalation-applicable-status/{id}', ['as' => 'lease.esacalation.applicablestatus', 'uses' => 'EscalationController@updateLeaseEscalationApplicableStatus'])->middleware('checkpreviousdata:8,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['get', 'post'], 'create/{id}/{lease}', ['as' => 'lease.escalation.create', 'uses' => 'EscalationController@create'])->middleware('checkpreviousdata:8,lease_id,lease','checksubscription:add_lease,lease_id,lease');
            Route::get('escalation-chart/{id}', ['as' => 'lease.escalation.showescalationchart', 'uses' => 'EscalationController@escalationChart']);
            Route::get('compute-total-escalation/{id}', ['as' => 'lease.escalation.compute', 'uses' => 'EscalationController@computeTotalUndiscountedPayment']);
            Route::get('show-payment-annexure/{id}', ['as' => 'lease.escalation.showpaymentannexure', 'uses' => 'EscalationController@paymentAnnexure']);
        });

        /*
         * Select Low Value Value NL10
         */
        Route::prefix('select-low-value')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.lowvalue.index', 'uses' => 'SelectLowValueController@index_V2'])->middleware('checkpreviousdata:9,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.lowvalue.create', 'uses' => 'SelectLowValueController@create'])->middleware('checkpreviousdata:9,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.lowvalue.update', 'uses' => 'SelectLowValueController@update'])->middleware('checkpreviousdata:9,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /*
         * Fair Market Value Routes NL4
         */
        Route::prefix('fair-market-value')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.fairmarketvalue.index', 'uses' => 'FairMarketValueController@index'])->middleware('checkpreviousdata:step3,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.fairmarketvalue.index', 'uses' => 'FairMarketValueController@index_V2'])->middleware('checkpreviousdata:10,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.fairmarketvalue.create', 'uses' => 'FairMarketValueController@create'])->middleware('checkpreviousdata:10,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.fairmarketvalue.update', 'uses' => 'FairMarketValueController@update'])->middleware('checkpreviousdata:10,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Select Discount Rate  NL11
         */
        Route::prefix('select-discount-rate')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.discountrate.index', 'uses' => 'SelectDiscountRateController@index'])->middleware('checkpreviousdata:step11,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.discountrate.index', 'uses' => 'SelectDiscountRateController@index_V2'])->middleware('checkpreviousdata:11,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.discountrate.create', 'uses' => 'SelectDiscountRateController@create'])->middleware('checkpreviousdata:11,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.discountrate.update', 'uses' => 'SelectDiscountRateController@update'])->middleware('checkpreviousdata:11,asset_id,id','checksubscription:add_lease,asset_id,id');
        });
        /**
         * Lease Balances as on Dec 31, 2018  NL12
         */
        Route::prefix('lease-balnce-as-on-dec')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.balanceasondec.index', 'uses' => 'LeaseBalanceAsOnDecController@index'])->middleware('checkpreviousdata:step12,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.balanceasondec.index', 'uses' => 'LeaseBalanceAsOnDecController@index_V2'])->middleware('checkpreviousdata:12,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.balanceasondec.create', 'uses' => 'LeaseBalanceAsOnDecController@create'])->middleware('checkpreviousdata:12,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.balanceasondec.update', 'uses' => 'LeaseBalanceAsOnDecController@update'])->middleware('checkpreviousdata:12,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Initial Direct Cost NL13
         */
        Route::prefix('initial-direct-cost')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.initialdirectcost.index', 'uses' => 'InitialDirectCostController@index'])->middleware('checkpreviousdata:step13,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.initialdirectcost.index', 'uses' => 'InitialDirectCostController@index_V2'])->middleware('checkpreviousdata:13,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.initialdirectcost.create', 'uses' => 'InitialDirectCostController@create'])->middleware('checkpreviousdata:13,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.initialdirectcost.update', 'uses' => 'InitialDirectCostController@update'])->middleware('checkpreviousdata:13,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'add-supplier-details', ['as' => 'addlease.initialdirectcost.addsupplier', 'uses' => 'InitialDirectCostController@addSupplier']);
            Route::match(['post', 'get'], 'update-supplier-details/{id}', ['as' => 'addlease.initialdirectcost.updatesupplier', 'uses' => 'InitialDirectCostController@updateSupplier'])->middleware('checkpreviousdata:13,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::post('create-supplier', ['as' => 'addlease.initialdirectcost.createsupplier', 'uses' => 'InitialDirectCostController@createSupplier']);
            Route::delete('delete-supplier/{id}/{lease_id}', ['as' => 'addlease.initialdirectcost.deletesupplier', 'uses' => 'InitialDirectCostController@deleteSupplier'])->middleware('checkpreviousdata:13,lease_id,lease_id','checksubscription:add_lease,lease_id,id');
            Route::delete('delete-create-supplier/{id}', ['as' => 'addlease.initialdirectcost.deletecreatesupplier', 'uses' => 'InitialDirectCostController@deleteCreateSupplier'])->middleware('checkpreviousdata:13,asset_id,id','checksubscription:add_lease,asset_id,id');
        });
        /**
         * Lease Incentives  NL14
         */
        Route::prefix('lease-incentives')->group(function () {
            //Route::get('index/{id}', ['as' => 'addlease.leaseincentives.index', 'uses' => 'LeaseIncentivesController@index'])->middleware('checkpreviousdata:step14,lease_id,id');
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.leaseincentives.index', 'uses' => 'LeaseIncentivesController@index_V2'])->middleware('checkpreviousdata:14,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.leaseincentives.create', 'uses' => 'LeaseIncentivesController@create'])->middleware('checkpreviousdata:14,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.leaseincentives.update', 'uses' => 'LeaseIncentivesController@update'])->middleware('checkpreviousdata:14,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::match(['post', 'get'], 'add-customer-details', ['as' => 'addlease.leaseincentives.addcustomer', 'uses' => 'LeaseIncentivesController@addCustomer']);
            Route::match(['post', 'get'], 'update-customer-details/{id}', ['as' => 'addlease.leaseincentives.updatecustomer', 'uses' => 'LeaseIncentivesController@updateCustomer'])->middleware('checkpreviousdata:14,asset_id,id','checksubscription:add_lease,asset_id,id');
            Route::post('create-customer', ['as' => 'addlease.leaseincentives.createcustomer', 'uses' => 'LeaseIncentivesController@createCustomer']);
            Route::delete('delete-customer/{id}/{lease_id}', ['as' => 'addlease.leaseincentives.deletecustomer', 'uses' => 'LeaseIncentivesController@deleteCustomer'])->middleware('checkpreviousdata:14,lease_id,lease_id','checksubscription:add_lease,lease_id,lease_id');
            Route::delete('delete-create-customer/{id}', ['as' => 'addlease.leaseincentives.deletecreatecustomer', 'uses' => 'LeaseIncentivesController@deleteCreateCustomer'])->middleware('checkpreviousdata:14,asset_id,id','checksubscription:add_lease,asset_id,id');
        });

        /**
         * Lease Dismantling Costs NL14.1
         */
        Route::prefix('dismantling-costs')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'lease.dismantlingcosts.index', 'uses' => 'DismantlingCostsController@index_V2']);
        });


        /**
         * Lease Incentives  NL15
         */
        Route::prefix('lease-valuation')->group(function () {
            Route::get('index/{id}', ['as' => 'addlease.leasevaluation.index', 'uses' => 'LeaseValuationController@index'])->middleware('checkpreviousdata:16,lease_id,id','checksubscription:add_lease,lease_id,id');

            Route::get('lease-liability-asset/{id}', ['as' => 'addlease.leasevaluation.liability', 'uses' => 'LeaseValuationController@presentValueOfLeaseLiability'])->middleware('checkpreviousdata:16,asset_id,id','checksubscription:add_lease,asset_id,id');

            Route::get('show-lease-liability-calculus/{id}', ['as' => 'addlease.leasevaluation.showcalculus', 'uses' => 'LeaseValuationController@showPresentValueOfLeaseLiabilityCalculus'])->middleware('checkpreviousdata:16,asset_id,id','checksubscription:add_lease,asset_id,id');

            Route::get('lease-valuation-asset/{id}', ['as' => 'addlease.leasevaluation.valuation', 'uses' => 'LeaseValuationController@equivalentPresentValueOfLeaseLiability'])->middleware('checkpreviousdata:16,asset_id,id','checksubscription:add_lease,asset_id,id');

            Route::get('lease-impairment/{id}', ['as' => 'addlease.leasevaluation.impairment', 'uses' => 'LeaseValuationController@leaseAssetImpairment'])->middleware('checkpreviousdata:16,asset_id,id','checksubscription:add_lease,asset_id,id');

            Route::get('termination-present-value/{id}', ['as' => 'addlease.leasevaluation.terminationpresentvalue', 'uses' => 'LeaseValuationController@terminationPresentValue']);

            Route::get('residual-present-value/{id}', ['as' => 'addlease.leasevaluation.residualpresentvalue', 'uses' => 'LeaseValuationController@residualPresentValue']);

            Route::get('purchase-present-value/{id}', ['as' => 'addlease.leasevaluation.purchasepresentvalue', 'uses' => 'LeaseValuationController@purchasePresentValue']);

            Route::get('save-lease-liability-calculus/{id}', ['as' => 'addlease.leasevaluation.savecalculus', 'uses' => 'LeaseValuationController@savePresentValueOfLeaseLiabilityCalculus']);

            Route::post('update-asset-for-subsequent/{id}', ['as' => 'addlease.leasevaluation.updateassetforsubsequent', 'uses' => 'LeaseValuationController@updateAssetForSubsequent']);

            Route::get('carrying-amount-calculations/{id}', ['as' => 'addlease.leasevaluation.carryingamountdetails', 'uses' => 'LeaseValuationController@carryingAmountDetails']);

            Route::get('historical-present-value/{id}', ['as' => 'addlease.leasevaluation.historicalpresentvalue', 'uses' => 'LeaseValuationController@historicalPresentValue']);

            Route::get('show-historical-annexure-calculus/{id}', ['as' => 'addlease.leasevaluation.historicalcalculusannexure', 'uses' => 'LeaseValuationController@showHistoricalAnnexure']);

        });
        /**
         * Lease Payment Invoice NL16
         */
        Route::prefix('lease-payment-invoice')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.leasepaymentinvoice.index', 'uses' => 'LeaseInvoiceController@index']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.leasepaymentinvoice.update', 'uses' => 'LeaseInvoiceController@index']);
        });

        /**
         *  Security Deposists NL16
         */
        Route::prefix('security-deposit')->group(function (){
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.securitydeposit.index', 'uses' => 'SecurityDepositController@index']);
        });

        /**
         * Review&Submit  NL17
         */
        Route::prefix('review-submit')->group(function () {
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.reviewsubmit.index', 'uses' => 'ReviewSubmitController@index'])->middleware('checkpreviousdata:18,lease_id,id','checksubscription:add_lease,lease_id,id');
            Route::match(['post', 'get'], 'submit/{id}', ['as' => 'addlease.reviewsubmit.submit', 'uses' => 'ReviewSubmitController@submit'])->middleware('checkpreviousdata:18,lease_id,id','checksubscription:add_lease,lease_id,id');
        });

    });

    /*
    * Drafts Routes
    */
    Route::namespace('Drafts')->middleware(['permission:drafts','checksubscription'])->prefix('drafts')->group(function () {
        Route::get('/', ['as' => 'drafts.index', 'uses' => 'IndexController@index']);
        Route::get('fetch-lease-details', ['as' => 'drafts.fetchleasedetails', 'uses' => 'IndexController@fetchLeaseDetails']);
        Route::match(['post', 'get', 'delete'], 'delete-lease-details/{id}', ['as' => 'drafts.deleteleasedetails', 'uses' => 'IndexController@deleteLeaseDetails']);
    });

    /*
    * Modify Lease Routes
    */

    Route::namespace('Modifylease')->middleware(['permission:modify_lease','checksubscription'])->prefix('modify-lease')->group(function () {
        Route::get('/', ['as' => 'modifylease.index', 'uses' => 'ModifyLeaseController@index']);
        Route::get('fetch-lease-details', ['as' => 'modifylease.fetchleasedetails', 'uses' => 'ModifyLeaseController@fetchLeaseDetails']);
        Route::match(['post', 'get'], 'create/{id}', ['as' => 'modifylease.create', 'uses' => 'ModifyLeaseController@create']);
        Route::match(['post', 'get'], 'update/{id}', ['as' => 'modifylease.update', 'uses' => 'ModifyLeaseController@update']);
        Route::get('checklockdate', ['as' => 'modifylease.checklockdate', 'uses' => 'ModifyLeaseController@checkLockDate']);
    });


    /*
    * Lease Valuation Routes
    */

    Route::namespace('Leasevaluation')->prefix('lease-valuation')->middleware('checksubscription')->group(function () {

        Route::prefix('valuation-capitalised')->middleware('permission:valuation_cap')->group(function(){

            Route::get('/', ['as' => 'leasevaluation.cap.index', 'uses' => 'LeaseValuationController@capitalised']);

            Route::get('overview/{id}', ['as' => 'leasevaluation.cap.asset', 'uses' => 'LeaseValuationController@leaseOverview']);

            Route::get('valuation/{id}', ['as' => 'leasevaluation.cap.asset.valuation', 'uses' => 'LeaseValuationController@leaseValuation']);

            Route::get('assets/{category_id}/{capitalized}', ['as' => 'leasevaluation.cap.fetchassets', 'uses' => 'LeaseValuationController@fetchAssets']);

            Route::get('fetch-assets-for-category/{category_id}', ['as' => 'leasevaluation.cap.fetchassetsforcategory', 'uses' => 'LeaseValuationController@fetchCategoryAsset']);

            Route::get('generate-discount-rate-chart/{id}', ['as' => 'leasevaluation.cap.discountRateChart', 'uses' => 'LeaseValuationController@generateDiscountRateChart']);

            Route::get('fetch-complete-lease-valuations/{id}',['as' => 'leasevaluation.cap.asset.fetchvaluations', 'uses' => 'LeaseValuationController@fetchCompletedLeaseValuation']);

            Route::get('see-valuation-details/{history_id}', ['as' => 'addlease.cap.leasevaluation.seedetails', 'uses' => 'LeaseValuationController@seeDetails']);

            Route::get('interest-depreciation/{id}', ['as' => 'leasevaluation.cap.interestdepreciation', 'uses' => 'LeaseValuationController@interestDepreciation']);

            Route::get('show-pv-calculus/{id}', ['as' => 'leasevaluation.cap.pvcalculus', 'uses' => 'LeaseValuationController@pvCalculus']);

            Route::get('export-interest-depreciation/{id}', ['as' => 'leasevaluation.cap.exportinterestdepreciation', 'uses' => 'LeaseValuationController@exportInterestDepreciation']);

        });

        Route::prefix('valuation-non-capitalised')->middleware('permission:valuation_ncap')->group(function(){

            Route::get('/', ['as' => 'leasevaluation.ncap.index', 'uses' => 'LeaseValuationController@capitalised']);

            Route::get('overview/{id}', ['as' => 'leasevaluation.ncap.asset', 'uses' => 'LeaseValuationController@leaseOverview']);

            Route::get('valuation/{id}', ['as' => 'leasevaluation.ncap.asset.valuation', 'uses' => 'LeaseValuationController@leaseValuation']);

            Route::get('assets/{category_id}/{capitalized}', ['as' => 'leasevaluation.ncap.fetchassets', 'uses' => 'LeaseValuationController@fetchAssets']);

            Route::get('fetch-assets-for-category/{category_id}', ['as' => 'leasevaluation.ncap.fetchassetsforcategory', 'uses' => 'LeaseValuationController@fetchCategoryAsset']);

            Route::get('generate-discount-rate-chart/{id}', ['as' => 'leasevaluation.ncap.discountRateChart', 'uses' => 'LeaseValuationController@generateDiscountRateChart']);

            Route::get('fetch-complete-lease-valuations/{id}',['as' => 'leasevaluation.ncap.asset.fetchvaluations', 'uses' => 'LeaseValuationController@fetchCompletedLeaseValuation']);

            Route::get('see-valuation-details/{history_id}', ['as' => 'addlease.ncap.leasevaluation.seedetails', 'uses' => 'LeaseValuationController@seeDetails']);

            Route::get('show-pv-calculus/{id}', ['as' => 'leasevaluation.ncap.pvcalculus', 'uses' => 'LeaseValuationController@pvCalculus']);

            Route::get('fetch-short-term-lease-assets',['as' => 'leasevaluation.ncap.shorttermassets', 'uses' => 'LeaseValuationController@fetchShortTermLeaseAssets']);

            Route::get('fetch-low-value-lease-assets',['as' => 'leasevaluation.ncap.lowvalueleaseassets', 'uses' => 'LeaseValuationController@fetchLowValueLeaseAssets']);

            Route::get('lease-interest-expense-annexure/{id}',['as' => 'leasevaluation.ncap.expensereport', 'uses' => 'LeaseValuationController@leaseExpense']);

            Route::get('export-lease-expense-annexure/{id}', ['as' => 'leasevaluation.cap.exportleaseexpenseannexure', 'uses' => 'LeaseValuationController@exportLeaseExpenseAnnexure']);
        });

        Route::get('show-escalation-chart/{id}', ['as' => 'lease.leasevaluation.escalationchart', 'uses' => 'LeaseValuationController@showEscalationChart']);

        Route::get('see-carrying-amount-annexure/{id}', ['as' => 'lease.leasevaluation.carryingamountannexure', 'uses' => 'LeaseValuationController@carryingAmountAnnexure']);

    });

    /**
     * Reports Routes
     */
    Route::namespace('Reports')->prefix('reports')->middleware(['permission:reports','checksubscription'])->group(function(){
        Route::get('/', ['as' => 'reports.index', 'uses' => 'ReportsController@index']);
        Route::get('lease-liability-contractual', ['as' => 'reports.leaseliability.contractual', 'uses' => 'ReportsController@leaseLiabilityContractual']);

        Route::get('fetch-liability-contractual-data',['as' => 'reports.leaseliability.fetchcontractual', 'uses' => 'ReportsController@fetchLeaseLiabilityContractual']);
    });

    /**
     * Settings Routes
     */
    Route::namespace('Settings')->middleware(['permission:settings','checksubscription'])->prefix('settings')->group(function () {

        Route::prefix('general')->group(function () {

            Route::get('/', ['as' => 'settings.index', 'uses' => 'IndexController@index']);

            Route::post('save', ['as' => 'settings.index.save', 'uses' => 'IndexController@save']);

            Route::post('save-application-standards', ['as' => 'settings.index.saveapplicationstandards', 'uses' => 'IndexController@saveApplicationStandards']);

            Route::get('changestatus', ['as' => 'settings.leaselockyear.index', 'uses' => 'LeaseLockYearController@index']);

            Route::get('fetch-lease-assets-countries', ['as' => 'settings.index.fetchleaseassetcountries', 'uses' => 'LeaseAssetCountriesController@index']);

            Route::match(['get', 'post'], 'add-lease-asset-country', ['as' => 'settings.index.addleaseassetcountries', 'uses' => 'LeaseAssetCountriesController@create']);

            Route::delete('delete-lease-asset-country/{id}', ['as' => 'settings.index.deleteleaseassetcountry', 'uses' => 'LeaseAssetCountriesController@delete']);

            Route::post('save-financial-reporting-period', ['as' => 'settings.index.financialreportingperiod', 'uses' => 'IndexController@financialReportingPeriod']);

        });

        Route::prefix('lease-classification')->group(function () {

            Route::get('/', ['as' => 'settings.leaseclassification', 'uses' => 'LeaseClassificationController@index']);
            /**
             * Routes for the lease payment Basics Settings for the authenticated user
             */
            Route::post('add-more-lease-payment-basis', ['as' => 'settings.leaseclassification.addleasepaymentbasis', 'uses' => 'LeaseClassificationController@leasePaymentBasis']);
            Route::match(['get', 'post'], '/edit-lease-payment-basis/{id}', ['as' => 'settings.leaseclassification.editleasepaymentbasis', 'uses' => 'LeaseClassificationController@editLeasePaymentBasis']);
            Route::delete('lease-classification-delete/{id}', ['as' => 'settings.leaseclassification.deleteleasepaymentbasis', 'uses' => 'LeaseClassificationController@deleteLeasePaymentsBasis']);

            /**
             * Routes for the number of underlying lease asset Settings for the authenticated user
             */
            Route::post('add-more-lease-asset-number', ['as' => 'settings.leaseclassification.addleaseassetnumber', 'uses' => 'LeaseClassificationController@addLeaseAssetNumber']);
            Route::match(['get', 'post'], '/edit-lease-asset-number/{id}', ['as' => 'settings.leaseclassification.editleaseassetnumber', 'uses' => 'LeaseClassificationController@editLeaseAssetNumber']);
            Route::delete('lease-asset-number-delete/{id}', ['as' => 'settings.leaseclassification.deleteleaseassetnumber', 'uses' => 'LeaseClassificationController@deleteLeaseAssetNumber']);

            /**
             * Number of Lease Assets of Similar Characteristics
             */
            Route::post('add-lease-asset-similar-charac', ['as' => 'settings.leaseclassification.addleasesimilarcharac', 'uses' => 'LeaseClassificationController@addLeaseAssetSimilarCharac']);
            Route::match(['get', 'post'], '/edit-lease-asset-similar-charac/{id}', ['as' => 'settings.leaseclassification.editleasesimilarcharac', 'uses' => 'LeaseClassificationController@editLeaseAssetSimilarCharac']);
            Route::delete('lease-asset-similar-charac-delete/{id}', ['as' => 'settings.leaseclassification.deleteleasesimilarcharac', 'uses' => 'LeaseClassificationController@deleteLeaseAssetSimilarCharac']);

            /**
             * Number of Lease Payments
             */
            Route::post('add-lease-payments-number', ['as' => 'settings.leaseclassification.addleasepaymentsnumber', 'uses' => 'LeaseClassificationController@addLeasePaymentsNumber']);
            Route::match(['get', 'post'], '/edit-lease-payments-number/{id}', ['as' => 'settings.leaseclassification.editleasepaymentsnumber', 'uses' => 'LeaseClassificationController@editLeasePaymentsNumber']);
            Route::delete('delete-lease-payments-number/{id}', ['as' => 'settings.leaseclassification.deleteleasepaymentsnumber', 'uses' => 'LeaseClassificationController@deleteLeasePaymentsNumber']);

            /**
             * Escalation Percentages
             */
            Route::post('add-escalation-percentage-number', ['as' => 'settings.leaseclassification.addescalationpercentagenumber', 'uses' => 'LeaseClassificationController@addEscalationPercentageNumber']);
            Route::match(['get', 'post'], '/edit-escalation-percentage-number/{id}', ['as' => 'settings.leaseclassification.editescalationpercentagenumber', 'uses' => 'LeaseClassificationController@editEscalationPercentageNumber']);
            Route::delete('delete-escalation-percentage-number/{id}', ['as' => 'settings.leaseclassification.deleteescalationpercentagenumber', 'uses' => 'LeaseClassificationController@deleteEscalationPercentageNumber']);

            /**
             * Escalation Consistency Gap
             */
            Route::post('add-escalation-consistency-gap', ['as'=> 'settings.leaseclassification.addescalationconsistencygap', 'uses' => 'LeaseClassificationController@addEscalationConsistencyGap']);
            Route::match(['get', 'post'], '/edit-escalation-consistency-gap/{id}', ['as' => 'settings.leaseclassification.editescalationconsistencygap', 'uses' => 'LeaseClassificationController@editEscalationConsistencyGap']);
            Route::delete('delete-escalation-consistency-gap/{id}', ['as' => 'settings.leaseclassification.deleteescalationconsistencygap', 'uses' => 'LeaseClassificationController@deleteEscalationConsistencyGap']);

            /**
             * Lease Modification Reason
             */
            Route::post('add-lease-modification-reason', ['as' => 'settings.leaseclassification.addleasemodificationreason', 'uses' => 'LeaseClassificationController@addLeaseModificationReason']);
            Route::match(['get', 'post'], '/edit-lease-modification-reason/{id}', ['as' => 'settings.leaseclassification.editleasemodificationreason', 'uses' => 'LeaseClassificationController@editLeaseModificationReason']);
            Route::delete('lease-modification_reason-delete/{id}', ['as' => 'settings.leaseclassification.deleteleasemodificationreason', 'uses' => 'LeaseClassificationController@deleteLeaseModificationReason']);

            /**
             * Categories of Lease Assets Excluded
             */
            Route::post('add-categories-excluded/{id}', ['as' => 'settings.leaseclassification.addcategoriesexcluded', 'uses' => 'LeaseClassificationController@addCategoriesExcluded']);

        });

        Route::prefix('currencies')->group(function () {
            Route::get('/', ['as' => 'settings.currencies', 'uses' => 'CurrenciesController@index']);
            Route::post('save', ['as' => 'settings.currencies.save', 'uses' => 'CurrenciesController@save']);
            Route::post('update-is-foreign-transaction-involved', ['as' => 'settings.currencies.updateisforeigninvolved', 'uses' => 'CurrenciesController@udpateIsForeignTransactionCurrencyInvolved']);
            /**
             * Foreign Transaction Currency Settings Routes
             */
            Route::match(['get', 'post'], 'add-foreign-exchange-currency', ['as' => 'settings.currencies.addforeigntransactioncurrency', 'uses' => 'CurrenciesController@addForeignTransactionCurrency']);
            Route::get('fetch-foreign-transaction-currencies', ['as' => 'settings.currencies.fetchforeigntransactioncurrencies', 'uses' => 'CurrenciesController@fetchForeignTransactionCurrency']);
            Route::match(['get', 'post'], 'edit-foreign-transaction-currencies/{id}', ['as' => 'settings.currencies.editforeigntransactioncurrency', 'uses' => 'CurrenciesController@editForeignTransactionCurrency']);
            Route::delete('delete-foreign-transaction-currencies/{id}', ['as' => 'settings.currencies.deleteforeigntransactioncurrencies', 'uses' => 'CurrenciesController@deleteForeignTransactionCurrency']);
        });

        Route::prefix('lease-assets')->group(function () {
            Route::get('/', ['as' => 'settings.leaseassets', 'uses' => 'LeaseAssetsController@index']);
            /**
             * Expected useful life of assets Routes
             */
            Route::post('add-life-of-asset', ['as' => 'settings.leaseassets.addlife', 'uses' => 'LeaseAssetsController@addLife']);
            Route::match(['get', 'post'], '/edit-life-of-asset/{id}', ['as' => 'settings.leaseassets.editlife', 'uses' => 'LeaseAssetsController@editLife']);
            Route::delete('delete-life-of-asset/{id}', ['as' => 'settings.leaseassets.deletelife', 'uses' => 'LeaseAssetsController@deleteLife']);

            /**
             * Lease Assets Categories Settings Routes
             */
            Route::get('fetch-lease-asset-category/{id}', ['as' => 'settings.leaseassets.fetchassetcategorysettings', 'uses' => 'LeaseAssetsController@fetchCategorySettings']);
            Route::match(['get', 'post'], 'add-lease-asset-category-setting/{id}', ['as' => 'settings.leaseassets.addcategorysetting', 'uses' => 'LeaseAssetsController@addCategorySettings']);
            Route::match(['get', 'post'], 'edit-lease-asset-category-setting/{id}', ['as' => 'settings.leaseassets.editcategorysetting', 'uses' => 'LeaseAssetsController@editCategorySetting']);
            Route::delete('delete-lease-asset-category-setting/{id}', ['as' => 'settings.leaseassets.editcategorysetting', 'uses' => 'LeaseAssetsController@deleteCategorySetting']);
        });

        Route::prefix('user-access')->namespace('UserAccess')->group(function () {
            Route::get('/', ['as' => 'settings.useraccess', 'uses' => 'UserAccessController@index']);

            Route::get('listing', ['as' => 'settings.user', 'uses' => 'UserAccessController@listing']);

            Route::get('fetch', ['as' => 'settings.user.fetch', 'uses' => 'UserAccessController@fetch']);

            Route::match(['get', 'post'], 'create', ['as' => 'settings.user.create', 'uses' => 'UserAccessController@create'])->middleware('checksubscription:add_sub_users');

            Route::post('user-status-update', ['as' => 'settings.user.updatestatus', 'uses' => 'UserAccessController@changeStatus']);

            Route::match(['get', 'post'], '/update/{id}', ['as' => 'settings.user.update', 'uses' => 'UserAccessController@update']);

            Route::delete('delete/{id}', ['as' => 'settings.user.delete', 'uses' => 'UserAccessController@delete']);

            Route::match(['get', 'post'], 'assigned-permission-role/{id}', ['as' => 'settings.user.assigned-permission-role', 'uses' => 'UserAccessController@assignPermissionToRole']);

            Route::match(['get', 'post'], 'assigned-role-user/{id}', ['as' => 'settings.user.assigned-role-User', 'uses' => 'UserAccessController@assignRoleToUser']);


            Route::prefix('role')->group(function () {
                Route::get('/', ['as' => 'settings.role', 'uses' => 'RoleController@index']);

                Route::get('fetch', ['as' => 'settings.role.fetch', 'uses' => 'RoleController@fetch']);

                Route::match(['get', 'post'], 'create', ['as' => 'settings.role.create', 'uses' => 'RoleController@create']);

                Route::match(['get', 'post'], '/update/{id}', ['as' => 'settings.role.update', 'uses' => 'RoleController@update']);

                Route::delete('delete/{id}', ['as' => 'settings.role.delete', 'uses' => 'RoleController@delete']);
            });

        });

        Route::prefix('codification')->group(function () {
            Route::get('/', ['as' => 'settings.codification', 'uses' => 'CodificationController@index']);
        });

        Route::prefix('profile')->group(function () {
            Route::match(['get', 'post'], '/', ['as' => 'settings.profile.index', 'uses' => 'ProfileController@index']);
        });

    });

    Route::namespace('Documents')->middleware(['permission:documents', 'checksubscription'])->prefix('documents')->group(function(){

        Route::get('/', ['as' => 'documents.index', 'uses' => 'DocumentsController@index']);

        Route::get('fetch-lease-assets', ['as' => 'documents.index.fetchleaseassets', 'uses' => 'DocumentsController@fetchLeaseAssets']);

        Route::get('list/{id}', ['as' => 'documents.index.listdocuments', 'uses' => 'DocumentsController@listDocuments']);

        Route::get('fetch-documents/{id}', ['as' => 'documents.index.fetchdocuments', 'uses' => 'DocumentsController@fetchDocumentsList']);

    });

});

Route::namespace('Contactus')->prefix('contact')->group(function () {
    Route::match(['get', 'post'], '/', ['as' => 'contactus', 'uses' => 'ContactusController@index']);
    Route::post('get-in-touch-with-us', ['as' => 'getintouchwithus', 'uses' => 'ContactusController@getInTouchWithUs']);
});

Route::namespace('Support')->prefix('support')->group(function(){
    Route::post('raise-ticket', ['as' => 'support.raise.ticket', 'uses' => 'SupportController@create']);
});

Route::get('email-confirmation/{verification_code}', ['as' => 'email.confirmation', 'uses' => 'Auth\LoginController@verifyEmail']);

Route::namespace('Admin')->prefix('admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    Route::match(['get', 'post'], '', ['as' => 'admin.auth.login', 'uses' => 'AuthController@index']);

    Route::get('logout', ['as' => 'admin.auth.logout', 'uses' => 'AuthController@logout']);

    Route::get('reset-password', ['as' => 'admin.password.reset', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);

    Route::post('reset-password/email', ['as' => 'admin.password.reset.email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);

    Route::get('reset-password/request/{token?}', ['as' => 'admin.auth.password.request', 'uses' => 'ResetPasswordController@showResetForm']);

    Route::post('reset-password/reset', ['as' => 'admin.auth.password.reset', 'uses' => 'ResetPasswordController@reset']);

    Route::group(['middleware' => 'checkAdmin'], function () {

        Route::get('dashboard', ['as' => 'admin.dashboard.index', 'uses' => 'DashboardController@index']);

        Route::get('email-templates', ['as' => 'admin.emailtemplates.index', 'uses' => 'EmailTemplatesController@index']);

        Route::get('email-templates-fetch', ['as' => 'admin.emailtemplates.fetch', 'uses' => 'EmailTemplatesController@fetch']);

        Route::get('email-templates-preview/{template_code}', ['as' => 'admin.emailtemplates.preview', 'uses' => 'EmailTemplatesController@preview']);

        Route::match(['get', 'post'], 'email-template-edit/{id}', ['as' => 'admin.emailtemplates.edit', 'uses' => 'EmailTemplatesController@edit']);

        Route::get('manage-users', ['as' => 'admin.users.index', 'uses' => 'UserController@index']);

        Route::get('manage-users-fetch', ['as' => 'admin.users.fetch', 'uses' => 'UserController@fetch']);

        Route::delete('manage-user-delete/{id}', ['as' => 'admin.manage.user.delete', 'uses' => 'UserController@delete']);

        Route::match(['get', 'post'], 'manage-user-add', ['as' => 'admin.user.add', 'uses' => 'UserController@add']);

        Route::match(['get', 'post'], 'manage-user-edit/{id}', ['as' => 'admin.manage.user.edit', 'uses' => 'UserController@edit']);

        Route::post('manage-user-status-update', ['as' => 'admin.users.updatestatus', 'uses' => 'UserController@changeStatus']);

        Route::prefix('country')->group(function () {

            Route::get('/', ['as' => 'admin.countries.index', 'uses' => 'CountriesController@index']);

            Route::match(['get', 'post'], 'create', ['as' => 'admin.countries.create', 'uses' => 'CountriesController@create']);

            Route::get('fetch', ['as' => 'admin.countries.fetch', 'uses' => 'CountriesController@fetch']);

            Route::match(['get', 'post'], 'update/{id}', ['as' => 'admin.countries.update', 'uses' => 'CountriesController@update']);

            Route::delete('delete/{id}', ['as' => 'admin.countries.delete', 'uses' => 'CountriesController@delete']);

            Route::post('updatestatus', ['as' => 'admin.countries.updatestatus', 'uses' => 'CountriesController@changeStatus']);

        });

        Route::prefix('cms')->group(function () {

            Route::get('/', ['as' => 'admin.cms.index', 'uses' => 'CmsController@index']);

            Route::match(['get', 'post'], 'create', ['as' => 'admin.cms.create', 'uses' => 'CmsController@create']);

            Route::get('fetch', ['as' => 'admin.cms.fetch', 'uses' => 'CmsController@fetch']);

            Route::match(['get', 'post'], 'update/{id}', ['as' => 'admin.cms.update', 'uses' => 'CmsController@update']);

            Route::delete('delete/{id}', ['as' => 'admin.cms.delete', 'uses' => 'CmsController@delete']);

            Route::post('updatestatus', ['as' => 'admin.cms.updatestatus', 'uses' => 'CmsController@changeStatus']);


        });

        Route::prefix('contactus')->group(function () {
            Route::get('/', ['as' => 'admin.contactus.index', 'uses' => 'ContactusController@index']);
            Route::get('fetch', ['as' => 'admin.contactus.fetch', 'uses' => 'ContactusController@fetch']);
            Route::match(['get', 'post'], 'preview/{id}', ['as' => 'admin.contactus.preview', 'uses' => 'ContactusController@preview']);
        });

        Route::prefix('subscription-plans')->group(function () {
            Route::get('/', ['as' => 'admin.subscriptionplans.index', 'uses' => 'SubscriptionPlansController@index']);
            Route::get('fetch', ['as' => 'admin.subscriptionplans.fetch', 'uses' => 'SubscriptionPlansController@fetch']);
            Route::match(['get', 'post'], 'create', ['as' => 'admin.subscriptionplans.create', 'uses' => 'SubscriptionPlansController@create']);
            Route::match(['get', 'post'], 'update/{id}', ['as' => 'admin.subscriptionplans.update', 'uses' => 'SubscriptionPlansController@update']);
            Route::get('custom-plans-request', ['as' => 'admin.subscriptionplans.customplanrequests', 'uses' => 'SubscriptionPlansController@customPlanRequests']);
            Route::get('fetch-custom-plan-requests', ['as' => 'admin.subscriptionplans.fetchcustomplanrequests', 'uses' => 'SubscriptionPlansController@fetchCustomPlanRequests']);
            Route::match(['get', 'post'], 'create-custom-plan/{request_id?}', ['as' => 'admin.subscriptionplans.createcustomplan', 'uses' => 'SubscriptionPlansController@createCustomPlan']);
            Route::delete('delete/{id}', ['as' => 'admin.subscriptionplans.delete', 'uses' => 'SubscriptionPlansController@delete']);
        });


        Route::prefix('coupon-codes')->group(function(){
            Route::get('/', ['as' => 'admin.coupon.index', 'uses' => 'CouponCodeController@index']);
            Route::match(['get', 'post'], 'create', ['as' => 'admin.coupon.create', 'uses' => 'CouponCodeController@create']);
            Route::get('search-users', ['as' => 'admin.coupon.searchusers', 'uses' => 'CouponCodeController@searchUsers']);
            Route::get('fetch', ['as' => 'admin.coupon.fetch', 'uses' => 'CouponCodeController@fetch']);
            Route::post('updatestatus', ['as' => 'admin.coupon.updatestatus', 'uses' => 'CouponCodeController@changeStatus']);
            Route::delete('delete/{id}', ['as' => 'admin.coupon.delete', 'uses' => 'CouponCodeController@delete']);
            Route::match(['get', 'post'], 'update/{id}', ['as' => 'admin.coupon.update', 'uses' => 'CouponCodeController@update']);
        });

    });
});
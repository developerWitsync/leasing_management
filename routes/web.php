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


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function(){

    Route::get('/home', 'HomeController@index')->name('home');


    Route::namespace('Lease')->middleware(['permission:add_lease'])->prefix('lease')->group(function(){

        /**
         * Lessor Details Routes NL1
         */
        Route::prefix('lessor-details')->group(function(){
            Route::match(['post','get'],'create/{id?}', ['as' => 'add-new-lease.index', 'uses' => 'LessorDetailsController@index']);
            Route::post('save', ['as' => 'add-new-lease.index.save', 'uses' => 'LessorDetailsController@save']);
            Route::post('udpate/{id}', ['as' => 'add-new-lease.index.update', 'uses' => 'LessorDetailsController@udpate']);

            Route::post('udpate-total-assets/{id}', ['as' => 'add-new-lease.index.updatetotalassets', 'uses' => 'LessorDetailsController@udpateTotalAssets']);
        });

        /**
         * Underlying Lease Assets Routes NL2
         */
        Route::prefix('underlying-lease-assets')->group(function(){
            Route::match(['post', 'get'],'create/{id}', ['as' => 'addlease.leaseasset.index', 'uses' => 'UnderlyingLeaseAssetController@index']);
            Route::get('fetch-sub-categories/{id}', ['as'=> 'addlease.leaseasset.fetchsubcategories', 'uses' => 'UnderlyingLeaseAssetController@fetchSubCategories']);
            Route::match(['post', 'get'],'complete-asset-details/{lease}/{asset}', ['as' => 'addlease.leaseasset.completedetails', 'uses' => 'UnderlyingLeaseAssetController@assetDetails']);
        });

        /**
         * Lease Payments Routes NL3
         */
        Route::prefix('payments')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.payments.index', 'uses' => 'LeasePaymentsController@index']);
            Route::get('create/{lease_id}/{asset_id}/{payment_id?}', ['as' => 'lease.payments.add', 'uses' => 'LeasePaymentsController@create']);
            Route::post('save-total-payments/{id}', ['as' => 'lease.payments.savetotalpayments', 'uses' => 'LeasePaymentsController@saveTotalPayments']);
            Route::get('fetch-asset-payments/{id}', ['as' => 'lease.payments.fetchassetpayments', 'uses' => 'LeasePaymentsController@fetchAssetPayments']);
            Route::match(['post', 'get'],'create-asset-payment/{id}', ['as' => 'lease.payments.createassetpayment', 'uses' => 'LeasePaymentsController@createAssetPayments']);
            Route::match(['post', 'get'],'update-asset-payment/{id}/{payment_id}', ['as' => 'lease.payments.updateassetpayment', 'uses' => 'LeasePaymentsController@updateAssetPayments']);
            Route::get('lease-asset-payment-due-dates-annexure', ['as' => 'lease.payments.duedatesannexure', 'uses' => 'LeasePaymentsController@dueDatesAnnexure']);

            Route::get('lease-asset-payment-due-dates-view-dates/{id}', ['as'=> 'addlease.payments.showpaymentdates', 'uses'=> 'LeasePaymentsController@viewExistingDates']);
        });


        /*
         * Fair Market Value Routes NL4
         */
        Route::prefix('fair-market-value')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.fairmarketvalue.index', 'uses' => 'FairMarketValueController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.fairmarketvalue.create', 'uses' => 'FairMarketValueController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.fairmarketvalue.update', 'uses' => 'FairMarketValueController@update']);

        });

        /**
         * Residual Value Guarantee Routes NL5
         */
        Route::prefix('residual-value-gurantee')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.residual.index', 'uses' => 'LeaseResidualController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.residual.create', 'uses' => 'LeaseResidualController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.residual.update', 'uses' => 'LeaseResidualController@update']);
        });

        /*
         * Lease Termination Options Routes NL6
         */
        Route::prefix('lease-termination-option')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.leaseterminationoption.index', 'uses' => 'LeaseTerminationOptionController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.leaseterminationoption.create', 'uses' => 'LeaseTerminationOptionController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.leaseterminationoption.update', 'uses' => 'LeaseTerminationOptionController@update']);
        });

        /**
         * Renewable Value NL7
         */
        Route::prefix('lease-renewal-option')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.renewable.index', 'uses' => 'LeaseRenewableOptionController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.renewable.create', 'uses' => 'LeaseRenewableOptionController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.renewable.update', 'uses' => 'LeaseRenewableOptionController@update']);

        });

        /*
         * Purchase Option Routes NL8
         */
        Route::prefix('purchase-option')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.purchaseoption.index', 'uses' => 'PurchaseOptionController@index']);
            Route::post('save', ['as' => 'addlease.purchaseoption.save', 'uses' => 'PurchaseOptionController@store']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.purchaseoption.create', 'uses' => 'PurchaseOptionController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.purchaseoption.update', 'uses' => 'PurchaseOptionController@update']);
        });

        /**
         * Lease Duration Classified Value NL8.1
         */
        Route::prefix('lease-duration-classified')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.durationclassified.index', 'uses' => 'LeaseDurationClassifiedController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.durationclassified.create', 'uses' => 'LeaseDurationClassifiedController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.durationclassified.update', 'uses' => 'LeaseDurationClassifiedController@update']);
        });

        /**

         * Lease Escalation Clause Routes NL9
         */

        Route::prefix('escalation')->group(function (){
            Route::get('index/{id}', ['as'=>'lease.escalation.index', 'uses'=> 'EscalationController@index']);
            Route::post('update-lease-escalation-applicable-status/{id}', ['as' => 'lease.esacalation.applicablestatus', 'uses' => 'EscalationController@updateLeaseEscalationApplicableStatus']);
            Route::match(['get', 'post'],'create/{id}/{lease}', ['as'=>'lease.escalation.create', 'uses'=> 'EscalationController@create']);

            Route::get('escalation-chart/{id}', ['as'=>'lease.escalation.showescalationchart', 'uses'=> 'EscalationController@escalationChart']);
            Route::get('compute-total-escalation/{id}', ['as'=>'lease.escalation.compute', 'uses'=> 'EscalationController@computeTotalUndiscountedPayment']);
        });


        /*
         * Select Low Value Value NL10
         */
        Route::prefix('select-low-value')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.lowvalue.index', 'uses' => 'SelectLowValueController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.lowvalue.create', 'uses' => 'SelectLowValueController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.lowvalue.update', 'uses' => 'SelectLowValueController@update']);

         });
        /**
         * Select Discount Rate  NL11
         */
        Route::prefix('select-discount-rate')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.discountrate.index', 'uses' => 'SelectDiscountRateController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.discountrate.create', 'uses' => 'SelectDiscountRateController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.discountrate.update', 'uses' => 'SelectDiscountRateController@update']);
        });
        /**
         * Lease Balances as on Dec 31, 2018  NL12
         */
        Route::prefix('lease-balnce-as-on-dec')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.balanceasondec.index', 'uses' => 'LeaseBalanceAsOnDecController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.balanceasondec.create', 'uses' => 'LeaseBalanceAsOnDecController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.balanceasondec.update', 'uses' => 'LeaseBalanceAsOnDecController@update']);
        });

        /**
         * Initial Direct Cost NL13
         */
        Route::prefix('initial-direct-cost')->group(function(){

            Route::get('index/{id}', ['as' => 'addlease.initialdirectcost.index', 'uses' => 'InitialDirectCostController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.initialdirectcost.create', 'uses' => 'InitialDirectCostController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.initialdirectcost.update', 'uses' => 'InitialDirectCostController@update']);

            Route::match(['post', 'get'], 'add-supplier-details', ['as' => 'addlease.initialdirectcost.addsupplier', 'uses' => 'InitialDirectCostController@addSupplier']);

            Route::match(['post', 'get'], 'update-supplier-details/{id}', ['as' => 'addlease.initialdirectcost.updatesupplier', 'uses' => 'InitialDirectCostController@updateSupplier']);

            Route::post('create-supplier',['as' => 'addlease.initialdirectcost.createsupplier', 'uses' => 'InitialDirectCostController@createSupplier']);

            Route::delete('delete-supplier/{id}/{lease_id}', ['as' => 'addlease.initialdirectcost.deletesupplier', 'uses' => 'InitialDirectCostController@deleteSupplier']);

             Route::delete('delete-create-supplier/{id}', ['as' => 'addlease.initialdirectcost.deletecreatesupplier', 'uses' => 'InitialDirectCostController@deleteCreateSupplier']); 
        });
        /**
         * Lease Incentives  NL14
         */
        Route::prefix('lease-incentives')->group(function(){
            
             Route::get('index/{id}', ['as' => 'addlease.leaseincentives.index', 'uses' => 'LeaseIncentivesController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.leaseincentives.create', 'uses' => 'LeaseIncentivesController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.leaseincentives.update', 'uses' => 'LeaseIncentivesController@update']);
            Route::match(['post', 'get'], 'add-customer-details', ['as' => 'addlease.leaseincentives.addcustomer', 'uses' => 'LeaseIncentivesController@addCustomer']);
            Route::match(['post', 'get'], 'update-customer-details/{id}', ['as' => 'addlease.leaseincentives.updatecustomer', 'uses' => 'LeaseIncentivesController@updateCustomer']);
             Route::post('create-customer',['as' => 'addlease.leaseincentives.createcustomer', 'uses' => 'LeaseIncentivesController@createCustomer']);

            Route::delete('delete-customer/{id}/{lease_id}', ['as' => 'addlease.leaseincentives.deletecustomer', 'uses' => 'LeaseIncentivesController@deleteCustomer']);
            Route::delete('delete-create-customer/{id}', ['as' => 'addlease.leaseincentives.deletecreatecustomer', 'uses' => 'LeaseIncentivesController@deleteCreateCustomer']);
        });
        /**
         * Lease Payment Invoice NL16
         */
        Route::prefix('lease-payment-invoice')->group(function(){
            Route::match(['post', 'get'], 'index/{id}', ['as' => 'addlease.leasepaymentinvoice.index', 'uses' => 'LeaseInvoiceController@index']);
        });
        /**
         * Review&Submit  NL17
         */
        Route::prefix('review-submit')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.reviewsubmit.index', 'uses' => 'ReviewSubmitController@index']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.reviewsubmit.create', 'uses' => 'ReviewSubmitController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.reviewsubmit.update', 'uses' => 'ReviewSubmitController@update']);
        });

    });

    /*
    * Drafts Routes
    */

    Route::namespace('Drafts')->prefix('drafts')->group(function(){
        Route::get('/', ['as' => 'drafts.index', 'uses' => 'IndexController@index']);
        Route::get('fetch-lease-details', ['as' => 'drafts.fetchleasedetails', 'uses' => 'IndexController@fetchLeaseDetails']);
        Route::match(['post', 'get', 'delete'], 'delete-lease-details/{id}', ['as' => 'drafts.deleteleasedetails', 'uses' => 'IndexController@deleteLeaseDetails']);
    });

    /*
    * Modify Lease Routes
    */

    Route::namespace('Modifylease')->prefix('modify-lease')->group(function(){
         Route::get('/', ['as' => 'modifylease.index', 'uses' => 'ModifyLeaseController@index']);
         Route::get('fetch-lease-details', ['as' => 'modifylease.fetchleasedetails', 'uses' => 'ModifyLeaseController@fetchLeaseDetails']);
        Route::match(['post', 'get'], 'create/{id}', ['as' => 'modifylease.create', 'uses' => 'ModifyLeaseController@create']);
        Route::match(['post', 'get'], 'update/{id}', ['as' => 'modifylease.update', 'uses' => 'ModifyLeaseController@update']);
    });


    Route::namespace('Settings')->middleware(['permission:settings'])->prefix('settings')->group(function(){

        Route::prefix('general')->group(function(){
            Route::get('/', ['as' => 'settings.index', 'uses' => 'IndexController@index']);
            Route::post('save', ['as' => 'settings.index.save', 'uses' => 'IndexController@save']);
            Route::post('add-lease-lock-year', ['as' => 'settings.leaselockyear.addleaselockyear', 'uses' => 'LeaseLockYearController@addLeaseLockYear']);
            Route::match(['get', 'post'], '/edit-lease-lock-year/{id}', ['as' => 'settings.leaselockyear.editleaselockyear', 'uses' => 'LeaseLockYearController@editLeaseLockYear']);
            Route::delete('delete-lease-lock-nyear/{id}', ['as' => 'settings.leaselockyear.deleteleaselockyear', 'uses' => 'LeaseLockYearController@deleteLeaseLockYear']);


        });

        Route::prefix('lease-classification')->group(function (){

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
        });

        Route::prefix('currencies')->group(function (){
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

        Route::prefix('lease-assets')->group(function (){
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

        Route::prefix('user-access')->namespace('UserAccess')->group(function (){
            Route::get('/', ['as' => 'settings.useraccess', 'uses' => 'UserAccessController@index']);

            Route::get('listing', ['as' => 'settings.user', 'uses' => 'UserAccessController@listing']);

            Route::get('fetch', ['as' => 'settings.user.fetch', 'uses' => 'UserAccessController@fetch']);

            Route::match(['get', 'post'], 'create', ['as' => 'settings.user.create', 'uses' => 'UserAccessController@create']);

            Route::match(['get', 'post'], '/update/{id}', ['as' => 'settings.user.update', 'uses' => 'UserAccessController@update']);
            
            Route::delete('delete/{id}', ['as' => 'settings.user.delete', 'uses' => 'UserAccessController@delete']);
  
            Route::match(['get', 'post'], 'assigned-permission-role/{id}', ['as' => 'settings.user.assigned-permission-role', 'uses' => 'UserAccessController@assignPermissionToRole']);

            Route::match(['get', 'post'], 'assigned-role-user/{id}', ['as' => 'settings.user.assigned-role-User', 'uses' => 'UserAccessController@assignRoleToUser']);



         Route::prefix('role')->group(function (){
            Route::get('/', ['as' => 'settings.role', 'uses' => 'RoleController@index']);

            Route::get('fetch', ['as' => 'settings.role.fetch', 'uses' => 'RoleController@fetch']);

            Route::match(['get', 'post'], 'create', ['as' => 'settings.role.create', 'uses' => 'RoleController@create']);

            Route::match(['get', 'post'], '/update/{id}', ['as' => 'settings.role.update', 'uses' => 'RoleController@update']);
            
            Route::delete('delete/{id}', ['as' => 'settings.role.delete', 'uses' => 'RoleController@delete']);
        });

    });

        Route::prefix('codification')->group(function (){
            Route::get('/', ['as' => 'settings.codification', 'uses' => 'CodificationController@index']);
        });

        Route::prefix('profile')->group(function (){
            Route::match(['get', 'post'], 'index', ['as' => 'settings.profile.index', 'uses' => 'ProfileController@index']);
        });
    });
      
});

Route::namespace('Contactus')->group(function () {
   Route::match(['get', 'post'],'/contactus', ['as' => 'contactus', 'uses' => 'ContactusController@index']);  
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

        Route::match(['get', 'post'], 'email-template-edit/{id}' , ['as' => 'admin.emailtemplates.edit', 'uses' => 'EmailTemplatesController@edit']);

        Route::get('manage-users', ['as' => 'admin.users.index', 'uses' => 'UserController@index']);

        Route::get('manage-users-fetch', ['as' => 'admin.users.fetch', 'uses' => 'UserController@fetch']);

        Route::delete('manage-user-delete/{id}', ['as' => 'admin.manage.user.delete', 'uses' => 'UserController@delete']);

         Route::match(['get', 'post'],'manage-user-add', ['as' => 'admin.user.add', 'uses' => 'UserController@add']);

        Route::match(['get', 'post'],'manage-user-edit/{id}', ['as' => 'admin.manage.user.edit', 'uses' => 'UserController@edit']);

        Route::post('manage-user-status-update', ['as' => 'admin.users.updatestatus', 'uses' => 'UserController@changeStatus']);

        Route::prefix('country')->group(function(){

            Route::get('/', ['as' => 'admin.countries.index', 'uses' => 'CountriesController@index']);

            Route::match(['get', 'post'], 'create', ['as' => 'admin.countries.create', 'uses' => 'CountriesController@create']);

            Route::get('fetch', ['as' => 'admin.countries.fetch', 'uses' => 'CountriesController@fetch']);

            Route::match(['get', 'post'],'update/{id}', ['as' => 'admin.countries.update', 'uses' => 'CountriesController@update']);

            Route::delete('delete/{id}', ['as' => 'admin.countries.delete', 'uses' => 'CountriesController@delete']);

            Route::post('updatestatus', ['as' => 'admin.countries.updatestatus', 'uses' => 'CountriesController@changeStatus']);

        });
        Route::prefix('contactus')->group(function(){

            Route::get('/', ['as' => 'admin.contactus.index', 'uses' => 'ContactusController@index']);

           Route::get('fetch', ['as' => 'admin.contactus.fetch', 'uses' => 'ContactusController@fetch']);

           Route::match(['get', 'post'],'preview/{id}', ['as' => 'admin.contactus.preview', 'uses' => 'ContactusController@preview']);
         });

    });
});
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
         * Lessor Details Routes
         */
        Route::prefix('lessor-details')->group(function(){
            Route::match(['post','get'],'create/{id?}', ['as' => 'add-new-lease.index', 'uses' => 'LessorDetailsController@index']);
            Route::post('save', ['as' => 'add-new-lease.index.save', 'uses' => 'LessorDetailsController@save']);
            Route::post('udpate/{id}', ['as' => 'add-new-lease.index.update', 'uses' => 'LessorDetailsController@udpate']);

            Route::post('udpate-total-assets/{id}', ['as' => 'add-new-lease.index.updatetotalassets', 'uses' => 'LessorDetailsController@udpateTotalAssets']);
        });

        /**
         * Underlying Lease Assets Routes
         */
        Route::prefix('underlying-lease-assets')->group(function(){
            Route::match(['post', 'get'],'create/{id}', ['as' => 'addlease.leaseasset.index', 'uses' => 'UnderlyingLeaseAssetController@index']);
            Route::get('fetch-sub-categories/{id}', ['as'=> 'addlease.leaseasset.fetchsubcategories', 'uses' => 'UnderlyingLeaseAssetController@fetchSubCategories']);
            Route::match(['post', 'get'],'complete-asset-details/{lease}/{asset}', ['as' => 'addlease.leaseasset.completedetails', 'uses' => 'UnderlyingLeaseAssetController@assetDetails']);
        });

        /**
         * Lease Payments Routes
         */

        Route::prefix('payments')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.payments.index', 'uses' => 'LeasePaymentsController@index']);
            Route::get('create/{lease_id}/{asset_id}/{payment_id?}', ['as' => 'lease.payments.add', 'uses' => 'LeasePaymentsController@create']);
            Route::post('save-total-payments/{id}', ['as' => 'lease.payments.savetotalpayments', 'uses' => 'LeasePaymentsController@saveTotalPayments']);
            Route::get('fetch-asset-payments/{id}', ['as' => 'lease.payments.fetchassetpayments', 'uses' => 'LeasePaymentsController@fetchAssetPayments']);
            Route::match(['post', 'get'],'create-asset-payment/{id}', ['as' => 'lease.payments.createassetpayment', 'uses' => 'LeasePaymentsController@createAssetPayments']);
            Route::match(['post', 'get'],'update-asset-payment/{id}/{payment_id}', ['as' => 'lease.payments.updateassetpayment', 'uses' => 'LeasePaymentsController@updateAssetPayments']);
        });

        /**
         * Residual Value Guarantee Routes
         */

        Route::prefix('residual-value-gurantee')->group(function(){
            
            Route::get('index/{id}', ['as' => 'addlease.residual.index', 'uses' => 'LeaseResidualController@index']);
            Route::post('save', ['as' => 'addlease.residual.save', 'uses' => 'LeaseResidualController@store']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.residual.create', 'uses' => 'LeaseResidualController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.residual.update', 'uses' => 'LeaseResidualController@update']);
        });       
        
        /*
         * Fair Market Value Routes
         */

        Route::prefix('fair-market-value')->group(function(){
            Route::get('index/{id}', ['as' => 'addlease.fairmarketvalue.index', 'uses' => 'FairMarketValueController@index']);
            Route::post('save', ['as' => 'addlease.fairmarketvalue.save', 'uses' => 'FairMarketValueController@store']);
            Route::match(['post', 'get'], 'create/{id}', ['as' => 'addlease.fairmarketvalue.create', 'uses' => 'FairMarketValueController@create']);
            Route::match(['post', 'get'], 'update/{id}', ['as' => 'addlease.fairmarketvalue.update', 'uses' => 'FairMarketValueController@update']);
        });       
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
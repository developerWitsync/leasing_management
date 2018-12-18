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

    Route::namespace('Settings')->prefix('settings')->group(function(){
        Route::prefix('general')->group(function(){
            Route::get('/', ['as' => 'settings.index', 'uses' => 'IndexController@index']);
            Route::post('save', ['as' => 'settings.index.save', 'uses' => 'IndexController@save']);
        });

        Route::prefix('lease-classification')->group(function (){
            Route::get('/', ['as' => 'settings.leaseclassification', 'uses' => 'LeaseClassificationController@index']);
            Route::post('add-more-lease-payment-basis', ['as' => 'settings.leaseclassification.addleasepaymentbasis', 'uses' => 'LeaseClassificationController@leasePaymentBasis']);
            Route::match(['get', 'post'], '/edit-lease-payment-basis/{id}', ['as' => 'settings.leaseclassification.editeasepaymentbasis', 'uses' => 'LeaseClassificationController@editLeasePaymentBasis']);
        });

        Route::prefix('currencies')->group(function (){
            Route::get('/', ['as' => 'settings.currencies', 'uses' => 'CurrenciesController@index']);
        });

        Route::prefix('lease-assets')->group(function (){
            Route::get('/', ['as' => 'settings.leaseassets', 'uses' => 'LeaseAssetsController@index']);
        });

        Route::prefix('user-access')->group(function (){
            Route::get('/', ['as' => 'settings.useraccess', 'uses' => 'UserAccessController@index']);
        });

        Route::prefix('codification')->group(function (){
            Route::get('/', ['as' => 'settings.codification', 'uses' => 'CodificationController@index']);
        });
    });
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

    });
});
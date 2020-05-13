<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'HomeController');

// RDT Registration
Route::post('rdt/register', 'Rdt\RdtRegisterController');
Route::post('rdt/check', 'Rdt\RdtCheckStatusController');

// Medical Cases
Route::apiResource('medical-cases', 'MedicalCaseController', ['except' => ['create', 'edit']]);

// Medical Histories
Route::apiResource('medical-cases.histories', 'MedicalCaseHistoryController', ['only' => ['index', 'show', 'store']]);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', 'Settings\ProfileController@index');
    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');

    // API for Master data
    Route::prefix('master')->namespace('Master')->group(function() {
        Route::get('occupations', 'OccupationController@index');
        Route::get('occupations/{occupation}', 'OccupationController@show');

        Route::get('hospitals', 'HospitalController@index');
        Route::get('hospitals/{hospital}', 'HospitalController@show');

        Route::get('areas', 'AreaController@index');
        Route::get('areas/{area}', 'AreaController@show');

        Route::get('countries', 'CountryController@index');
    });

});

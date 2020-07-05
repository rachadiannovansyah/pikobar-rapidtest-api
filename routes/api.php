<?php

use App\Entities\RdtApplicant;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

$proxyUrl    = config('proxy.url');
$proxyScheme = config('proxy.scheme');

if(!empty($proxyUrl)) {
    URL::forceRootUrl($proxyUrl);
}

if(!empty($proxyScheme)) {
    URL::forceScheme($proxyScheme);
}

Route::get('/', 'HomeController');

// RDT Registration
Route::post('rdt/register', 'Rdt\RdtRegisterController');
Route::post('rdt/check', 'Rdt\RdtCheckStatusController');
Route::get('rdt/register/download', 'Rdt\RdtRegisterDownloadController')->name('registration.download');
Route::get('rdt/qrcode', 'Rdt\RdtQrCodeController')->name('registration.qrcode');

Route::post('rdt/survey', 'Rdt\RdtSurveyStoreController');

Route::post('rdt/checkin', 'Rdt\RdtCheckinController');
Route::post('rdt/event-check', 'Rdt\RdtEventCheckController');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'Settings\ProfileController@index');
});

// RDT Events
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('rdt/events','Rdt\RdtEventController@index');
    Route::get('rdt/events/{rdtEvent}','Rdt\RdtEventController@show');
    Route::post('rdt/events','Rdt\RdtEventController@store');
    Route::put('rdt/events/{rdtEvent}','Rdt\RdtEventController@update');
    Route::delete('rdt/events/{rdtEvent}','Rdt\RdtEventController@destroy');
});

// RDT Event Invitations Participants
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('rdt/events/{rdtEvent}/participants','Rdt\RdtEventParticipantListController');
    Route::post('rdt/events/{rdtEvent}/participants','Rdt\RdtEventParticipantAddController');
    Route::post('rdt/events/{rdtEvent}/participants-remove','Rdt\RdtEventParticipantRemoveController');
    Route::post('rdt/events/{rdtEvent}/participants-notify','Rdt\RdtEventNotifyParticipantController');
    Route::post('rdt/events/{rdtEvent}/participants-import', 'Rdt\RdtEventParticipantImportController');
});

// RDT Applicants
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('rdt/applicants','Rdt\RdtApplicantController@index')->middleware('can:viewAny,'.RdtApplicant::class);
    Route::get('rdt/applicants/{rdtApplicant}','Rdt\RdtApplicantController@show')->middleware('can:view,rdtApplicant');
    Route::post('rdt/applicants','Rdt\RdtApplicantController@store')->middleware('can:create,'.RdtApplicant::class);
    Route::put('rdt/applicants/{rdtApplicant}', 'Rdt\RdtApplicantController@update')->middleware('can:update,rdtApplicant');;
    Route::delete('rdt/applicants/{rdtApplicant}','Rdt\RdtApplicantController@destroy')->middleware('can:delete,rdtApplicant');;
});

Route::group(['middleware' => 'guest:api'], function () {
    // API for Master data
    Route::prefix('master')->namespace('Master')->group(function() {
        Route::get('areas', 'AreaController@index');
        Route::get('areas/{area}', 'AreaController@show');
    });
});

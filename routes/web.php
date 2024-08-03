<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicrosoftController;


use App\Http\Controllers\PowerBIController;




Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('auth/microsoft', [MicrosoftController::class, 'redirectToProvider']);
Route::get('auth/microsoft/callback', [MicrosoftController::class, 'handleProviderCallback']);

Route::get('auth/powerbi', [PowerBIController::class, 'authenticate'])->name('powerbi.authenticate');
Route::get('auth/powerbi/callback', [PowerBIController::class, 'handleProviderCallback']);
Route::get('powerbi/reports', [PowerBIController::class, 'getReports'])->name('powerbi.reports');
*/

Route::get('/get-access-token', [PowerBIController::class, 'getAccessToken']);
//Route::get('/report', function () {
//    return view('report');
//});
Route::get('/link', function () {
    return view('linkweb');
});

Route::get('/view-report', [PowerBIController::class, 'viewReport']);
Route::get('/show', [PowerBIController::class, 'show']);

//Route::get('/powerbi', [App\Http\Controllers\PowerBIController::class, 'index']);


//after web
Route::get('/powerbi/show', [App\Http\Controllers\PowerBIController::class, 'show']);

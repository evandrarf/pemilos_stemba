<?php

use App\Http\Controllers\Admin\RecapitulationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Recapitulation Routes
|--------------------------------------------------------------------------
|
| Here is where you can register setting related routes for your application.
|
*/


Route::controller(RecapitulationController::class)->prefix('recapitulation')->name('recapitulation.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/get-summary', 'getSummary')->name('get-summary');
    Route::get('/get-voter-summary', 'getVoterSummary')->name('get-voter-summary');
});

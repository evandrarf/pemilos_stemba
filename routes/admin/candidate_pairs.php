<?php

// use App\Http\Controllers\Admin\CandidatesController;
use App\Http\Controllers\Admin\CandidatePairsController;

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Here is where you can register setting related routes for your application.
|
*/

Route::controller(CandidatePairsController::class)->prefix('candidate-pairs')->name('candidate-pairs.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/getdata', 'getData')->name('getdata');
    Route::post('/create', 'createCandidatePair')->name('create');
    Route::post('/update/{id}', 'updateCandidatePair')->name('update');
});

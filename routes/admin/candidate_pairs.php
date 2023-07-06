<?php

// use App\Http\Controllers\Admin\CandidatesController;
use App\Http\Controllers\Admin\CandidatePairsController;
use Illuminate\Support\Facades\Route;

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
    Route::get('{id}', 'show')->name('show');
    Route::post('/create', 'createCandidatePair')->name('create');
    Route::post('/update/{id}', 'updateCandidatePair')->name('update');
    Route::delete('/delete/{id}', 'deleteCandidatePair')->name('delete');
});

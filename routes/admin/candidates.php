<?php

use App\Http\Controllers\Admin\CandidatesController;

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Here is where you can register setting related routes for your application.
|
*/

Route::controller(CandidatesController::class)->prefix('candidates')->name('candidates.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/getdata', 'getData')->name('getdata');
    Route::post('/create', 'createCandidate')->name('create');
    Route::put('/update/{id}', 'updateCandidate')->name('update');
    Route::delete('/delete/{id}', 'deleteCandidate')->name('delete');
});

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
});

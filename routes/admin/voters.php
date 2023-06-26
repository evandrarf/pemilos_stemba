<?php

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
|
| Here is where you can register setting related routes for your application.
|
*/

use App\Http\Controllers\Admin\Voter\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('voters')->name('voters.')->group(function () {
    Route::controller(StudentController::class)->prefix('students')->name('students.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getdata', 'getData')->name('getdata');
        Route::post('/create', 'createStudentVoter')->name('create');
        Route::put('/update/{id}', 'updateStudentVoter')->name('update');
        Route::delete('/delete/{id}', 'deleteStudentVoter')->name('delete');
        Route::post('/import', 'importStudentVoter')->name('import');
        Route::get('/download-template', 'downloadTemplateStudentVoter')->name('download-template');
        Route::get('/export', 'exportStudentVoter')->name('export');
    });
});
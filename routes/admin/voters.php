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
use App\Http\Controllers\Admin\Voter\TeacherController;
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
        Route::delete('/delete-all', 'deleteAllStudentVoter')->name('delete-all');
        Route::get('/get-class-list', 'getClassList')->name('get-class-list');
    });

    Route::controller(TeacherController::class)->prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/getdata', 'getData')->name('getdata');
        Route::post('/create', 'createTeacherVoter')->name('create');
        Route::put('/update/{id}', 'updateTeacherVoter')->name('update');
        Route::delete('/delete/{id}', 'deleteTeacherVoter')->name('delete');
        Route::post('/import', 'importTeacherVoter')->name('import');
        Route::get('/download-template', 'downloadTemplateTeacherVoter')->name('download-template');
        Route::get('/export', 'exportTeacherVoter')->name('export');
        Route::delete('/delete-all', 'deleteAllTeacherVoter')->name('delete-all');
    });
});

<?php

use App\Http\Controllers\Admin\CandidatePairsController;
use App\Http\Controllers\Auth\Voter\VoterLoginController;
use App\Http\Controllers\Votes\VotesController;
use App\Http\Middleware\CheckHasVote;
use Illuminate\Support\Facades\Route;

Route::controller(VoterLoginController::class)->name('user-voter.')->group(function () {
    Route::get('login', 'showLoginForm')->name('show-login');
    Route::post('login', 'login')->name('login');
    Route::middleware(['auth:voter'])->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});

Route::middleware(['auth:voter', 'hasVote'])->group(function () {
    Route::controller(VotesController::class)->name('user-voter.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::get('/detail', function () {
            return redirect()->route('user-voter.index');
        });
        Route::post('/vote', 'vote')->name('vote');
        Route::get('/thanks', 'thanks')->name('thanks')->withoutMiddleware('hasVote');
    });

    Route::controller(CandidatePairsController::class)->name('candidate-pairs.')->group(function () {
        Route::get('candidate-pairs', 'getData')->name('get-data');
        // Route::get('candidate-pairs/{id}', 'detail')->name('detail');
        // Route::get('candidate-pairs/{id}', 'show')->name('show');
    });
    // require __DIR__ . '/voter/vote.php';
});

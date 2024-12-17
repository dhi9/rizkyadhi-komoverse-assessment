<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaderboardController;

Route::prefix('/v1')->group(function () {
    Route::post('/submit-score', [LeaderboardController::class, 'submitScore']);
    Route::get('/leaderboard', [LeaderboardController::class, 'leaderboard']);
    Route::post('/external-submit', [LeaderboardController::class, 'externalSubmit']);
});

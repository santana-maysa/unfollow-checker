<?php

use App\Http\Controllers\UnfollowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UnfollowController::class, 'index'])
    ->name('unfollow.index');

Route::post('/analyze', [UnfollowController::class, 'analyze'])
    ->middleware('throttle:10,1')
    ->name('unfollow.analyze');
<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\EventController;

Route::post('/reset', [AccountController::class, 'reset']);
Route::get('/balance', [AccountController::class, 'balance']);
Route::post('/event', [EventController::class, 'handleEvent']);
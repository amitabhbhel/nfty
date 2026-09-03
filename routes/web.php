<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;
use App\Http\Controllers\UpstoxController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SiteController::class, 'home']);

Route::get('/ins', [UpstoxController::class, 'ins']);
Route::get('/opt', [UpstoxController::class, 'optionChain']);

Route::get('/exp', [SiteController::class, 'exp']);

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ins', [SiteController::class, 'ins']);
Route::get('/opt', [SiteController::class, 'opt']);
Route::get('/exp', [SiteController::class, 'exp']);

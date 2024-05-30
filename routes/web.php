<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubmitController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/api-submit', SubmitController::class);
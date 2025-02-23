<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/orders', [QueueController::class , 'getAllSimple']);

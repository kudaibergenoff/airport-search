<?php

use App\Http\Controllers\Api\V1\AirportSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/search', [AirportSearchController::class, 'airportSearch']);

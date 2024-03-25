<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)->group(function() {
    Route::get('/rooms', 'all');
    Route::get('/rooms/{id}', 'find');
});

Route::controller(TypeController::class)->group(function() {
    Route::get('/types', 'all');
    Route::get('/types/{id}', 'find');
});

//Route::controller(TypeController::class)->group(function() {
//    Route::get('/bookings', 'all');
//    Route::get('/bookings/{id}', 'find');
//    Route::get('/bookings/report', 'find');
//});

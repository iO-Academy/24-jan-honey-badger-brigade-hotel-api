<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TypeController;
use App\Http\Middleware\BookingValidator;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)->group(function () {
    Route::get('/rooms', 'all');
    Route::get('/rooms/{id}', 'find');
});

Route::controller(TypeController::class)->group(function () {
    Route::get('/types', 'all');
});
Route::controller(BookingController::class)->group(function () {
    Route::post('/bookings', 'create')->middleware(BookingValidator::class);
    Route::get('/bookings', 'all')->middleware(BookingValidator::class);
    Route::delete('/bookings/{id}', 'delete');
});

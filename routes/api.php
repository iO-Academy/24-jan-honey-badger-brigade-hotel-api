<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)->group(function () {
    Route::get('/rooms', 'all');
    Route::get('/rooms/{id}', 'find');
});

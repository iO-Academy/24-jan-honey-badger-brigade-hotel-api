<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)->group(function () {
    Route::get('/rooms', 'all');
});

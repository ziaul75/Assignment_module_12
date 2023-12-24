<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('trips', TripController::class);
Route::get('trips/{trip}/seats', [TicketController::class, 'showAvailableSeats']);
Route::post('trips/{trip}/book', [TicketController::class, 'bookTicket']);

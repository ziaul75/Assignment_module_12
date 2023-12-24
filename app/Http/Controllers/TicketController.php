<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\SeatAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function showAvailableSeats(Trip $trip)
    {
        $bookedSeats = SeatAllocation::where('trip_id', $trip->id)->pluck('seat_number')->toArray();

        $availableSeats = array_diff(range(1, 36), $bookedSeats);

        return view('tickets.available_seats', compact('trip', 'availableSeats'));
    }

    public function bookTicket(Trip $trip, Request $request)
    {
        $request->validate([
            'seat_number' => 'required|integer|min:1|max:36',
        ]);

        $isSeatAvailable = !SeatAllocation::where('trip_id', $trip->id)
            ->where('seat_number', $request->input('seat_number'))
            ->exists();

        if (!$isSeatAvailable) {
            return redirect()->route('trips.index')->with('error', 'The selected seat is not available.');
        }

        SeatAllocation::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'seat_number' => $request->input('seat_number'),
        ]);

        return redirect()->route('trips.index')->with('success', 'Ticket booked successfully.');
    }
}

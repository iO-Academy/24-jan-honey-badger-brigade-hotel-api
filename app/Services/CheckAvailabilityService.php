<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Request;

class CheckAvailabilityService
{
    public function checkBooking(Request $request): string|true
    {
        $start = strtotime($request->start);
        $end = strtotime($request->end);
        if ($start > $end) {
            return 'Start date must be before the end date.';
        }

        $bookings = Booking::where('room_id', $request->room_id)->get();
        foreach ($bookings as $booking) {
            $bookedFrom = strtotime($booking->start);
            $bookedTo = strtotime($booking->end);
            if ($start >= $bookedFrom && $start <= $bookedTo) {
                return 'Room unavailable for the chosen dates.';
            } elseif ($end >= $bookedFrom && $end <= $bookedTo) {
                return 'Room unavailable for the chosen dates.';
            } elseif ($start <= $bookedFrom && $end >= $bookedTo) {
                return 'Room unavailable for the chosen dates.';
            }
        }

        return true;
    }
}

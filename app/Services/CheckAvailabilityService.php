<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Request;

class CheckAvailabilityService
{
    public function checkBooking(Request $request): bool
    {
        $start = strtotime($request->start);
        $end = strtotime($request->end);

        $bookings = Booking::where('room_id', $request->room_id)->get();
        foreach ($bookings as $booking) {
            $bookedFrom = strtotime($booking->start);
            $bookedTo = strtotime($booking->end);
            if ($start >= $bookedFrom && $start <= $bookedTo) {
                return false;
            } elseif ($end >= $bookedFrom && $end <= $bookedTo) {
                return false;
            } elseif ($start <= $bookedFrom && $end >= $bookedTo) {
                return false;
            }
        }

        return true;
    }
}

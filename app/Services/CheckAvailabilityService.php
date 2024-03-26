<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class CheckAvailabilityService
{
    public function checkBooking(Request $request): string|Booking
    {
        $start = strtotime($request->start);
        $end = strtotime($request->end);
        if ( $start > $end)  {
            return 'Start date must be before the end date.';
        }

        $bookings = Booking::where('room_id', $request->room_id)->get();
        foreach ($bookings as $booking) {
            $bookedFrom = strtotime($booking->start);
            $bookedTo = strtotime($booking->end);
            if ($start >= $bookedFrom && $start <= $bookedTo)
                {
                    return 'Room unavailable for the chosen dates.';
                } else if ($end >= $bookedFrom && $end <= $bookedTo)
                {
                    return 'Room unavailable for the chosen dates.';
                } else if ($start <= $bookedFrom && $end >= $bookedTo)
                {
                    return 'Room unavailable for the chosen dates.';
                }
        }
        $room = Room::find($request->room_id);
        if ($request->guests < $room->min_capacity || $request->guests > $room->max_capacity) {
            return 'The '.$room->name.' can only accommodate between '.$room->min_capacity.' and '.$room->max_capacity.' guests.';
        }

        $newBooking = new Booking();
        $newBooking->room_id = $request->room_id;
        $newBooking->customer = $request->customer;
        $newBooking->guests = $request->guests;
        $newBooking->start = $request->start;
        $newBooking->end = $request->end;
        return $newBooking;

    }

}

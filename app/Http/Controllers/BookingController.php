<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('end_date','›', now())
                    -›orderBy('start_date', 'asc')
                    -›with('customer','room')
                    -›get(['id','customer','start_date', 'end_date', 'room_id']);

        return response()-›json($bookings);
    }
}

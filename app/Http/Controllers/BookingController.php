<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\JsonResponseService;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    private JsonResponseService $responseService;

    public function __construct(JsonResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function create(Request $request)
    {
        $newBooking = new Booking();
        $newBooking->room_id = $request->room_id;
        $newBooking->customer = $request->customer;
        $newBooking->guests = $request->guests;
        $newBooking->start = $request->start;
        $newBooking->end = $request->end;

        if (strtotime($newBooking->start) > strtotime($newBooking->end)) {
            return response()->json($this->responseService->getFormat(
                'Start date must be before the end date.'
            ), 400);
        }

        $bookings = Booking::where('room_id', $request->room_id)->get();
        foreach ($bookings as $booking) {
            if (strtotime($newBooking->start) > strtotime($booking->start) && strtotime($newBooking->end) < strtotime($booking->end)) {
                return response()->json($this->responseService->getFormat(
                    'Room unavailable for the chosen dates.'
                ), 400);
            }
        }
        $room = Room::find($request->room_id);
        if ($newBooking->guests < $room->min_capacity || $newBooking->guests > $room->max_capacity) {
            return response()->json($this->responseService->getFormat(
                'The ' . $room->name . ' can only accommodate between ' . $room->min_capacity . ' and ' . $room->max_capacity . ' guests.'
            ), 400);
        }

        if (! $newBooking->save()) {
            return response()->json($this->responseService->getFormat(
                'Failed to create booking'
            ), 500);
        };
        return response()->json($this->responseService->getFormat(
            'Booking successfully created.'
        ), 201);
    }
}






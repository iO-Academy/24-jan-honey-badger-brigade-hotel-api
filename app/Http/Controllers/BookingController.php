<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer' => 'required|string',
            'guests' => 'required|integer',
            'start' => 'required|date|before_or_equal:end',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $isRoomAvailable = Booking::isAvailable('room_id', $request->room_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start', [$request->start, $request->end])
                    ->orWhereBetween('end', [$request->start, $request->end]);
            })
            ->doesntExist();

        if (!$isRoomAvailable) {
            return response()->json(['message' => 'The room is already booked for the given dates.'], 400);
        }

        $room = Room::checkCapacity($request->room_id);
        if ($request->guests > $room->max_capacity) {
            return response()->json(['message' => 'The number of guests exceeds the room\'s capacity.'], 400);
        }

        $booking = Booking::create([
            'room_id' => $request->room_id,
            'customer' => $request->customer,
            'guests' => $request->guests,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json(['message' => 'Booking created successfully.'], 201);
    }
}

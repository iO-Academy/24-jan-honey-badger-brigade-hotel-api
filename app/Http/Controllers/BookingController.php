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
        dd($room);
        $roomMinCap = Room::find($request->room_id);

        if ($request->guests > $room->max_capacity) {
            return response()->json(['message' => 'The ' . ' can only accommodate between ' . ' and ' . ' guests.'], 400);
        }

        $booking = new Booking();
        $booking->room_id = $request->room_id;
        $booking->customer = $request->customer;
        $booking->guests = $request->guests;
        $booking->start = $request->start;
        $booking->end = $request->end;

        if (! $booking->save()) {
            return response()->json($this->responseService->getFormat(
                'Failed to create booking'
            ), 500);
        };

        return response()->json($this->responseService->getFormat(
             'Booking successfully created.'
        ), 201);
    }
}


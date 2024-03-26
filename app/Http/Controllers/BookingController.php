<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\CheckAvailabilityService;
use App\Services\JsonResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private JsonResponseService $responseService;

    private CheckAvailabilityService $availabilityService;

    public function __construct(JsonResponseService $responseService, CheckAvailabilityService $availabilityService)
    {
        $this->responseService = $responseService;
        $this->availabilityService = $availabilityService;
    }

    public function create(Request $request): JsonResponse
    {
        $result = $this->availabilityService->checkBooking($request);
        if (is_string($result)) {
            return response()->json($this->responseService->getFormat(
                $result
            ), 400);
        }
        $room = Room::find($request->room_id);
        if ($request->guests < $room->min_capacity || $request->guests > $room->max_capacity) {
            return response()->json($this->responseService->getFormat(
                'The '.$room->name.' can only accommodate between '.$room->min_capacity.' and '.$room->max_capacity.' guests.'
            ), 400);
        }
        $newBooking = new Booking();
        $newBooking->room_id = $request->room_id;
        $newBooking->customer = $request->customer;
        $newBooking->guests = $request->guests;
        $newBooking->start = $request->start;
        $newBooking->end = $request->end;

        if (! $newBooking->save()) {
            return response()->json($this->responseService->getFormat(
                'Failed to create booking'
            ), 500);
        }

        return response()->json($this->responseService->getFormat(
            'Booking successfully created.'
        ), 201);
    }
}

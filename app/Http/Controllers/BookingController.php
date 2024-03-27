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
        $start = strtotime($request->start);
        $end = strtotime($request->end);
        if ($start > $end) {
            return response()->json($this->responseService->getFormat(
                'Start date must be before the end date.'
            ), 400);
        }
        $result = $this->availabilityService->checkBooking($request);
        if (! $result) {
            return response()->json($this->responseService->getFormat(
                'Room unavailable for the chosen dates.'
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

    public function all(Request $request)
    {
        $hidden = ['room_id', 'guests'];

        $bookings = Booking::orderBy('start', 'asc')
            ->with('room:id,name')
            ->get()
            ->makeHidden($hidden);

        $filterBookings = $request->room_id;

        if ($filterBookings) {
            return response()->json($this->responseService->getFormat(
                'Bookings successfully retrieved',
                $bookings->where('room_id', $request->room_id)->where('start', '>=', date('Y-m-d'))
            ));
        }

        return response()->json($this->responseService->getFormat(
            'Bookings successfully retrieved.',
            $bookings->where('end', '>', now())
        ));
    }
}

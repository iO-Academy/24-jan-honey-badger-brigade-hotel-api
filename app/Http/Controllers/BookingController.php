<?php

namespace App\Http\Controllers;

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

        if (! $result->save()) {
            return response()->json($this->responseService->getFormat(
                'Failed to create booking'
            ), 500);
        }

        return response()->json($this->responseService->getFormat(
            'Booking successfully created.'
        ), 201);
    }
}

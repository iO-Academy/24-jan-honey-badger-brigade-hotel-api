<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\JsonResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private JsonResponseService $responseService;

    public function __construct (JsonResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function all()
    {
        return response()->json($this->responseService->getFormat(
           'Rooms Retrieved',
            Room::with(['type:id,name'])->get()
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\JsonResponseService;

class RoomController extends Controller
{
    private JsonResponseService $responseService;

    public function __construct(JsonResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function all()
    {
        return response()->json($this->responseService->getFormat(
            'Rooms successfully retrieved',
            Room::with(['type:id,name'])->get()->makeHidden(['rate', 'description'])
        ));
    }

    public function find(int $id)
    {
        $room = Room::find($id);

        if (! $room) {
            return response()->json($this->responseService->getFormat(
                'Room with id '.$id.' not found'
            ), 404);
        }

        return response()->json($this->responseService->getFormat(
            'Room successfully retrieved',
            Room::with(['type:id,name'])->find($id)
        ));
    }
}

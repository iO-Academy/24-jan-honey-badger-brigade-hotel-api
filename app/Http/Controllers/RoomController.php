<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\JsonResponseService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private JsonResponseService $responseService;

    public function __construct(JsonResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function all(Request $request)
    {
        $hidden = ['rate', 'description', 'type_id'];
        $query = Room::with(['type:id,name']);

        $type = $request->type;
        $guests = $request->guests;

        if ($type) {
            $query->where('type_id', $type);
        }

        if ($guests) {
            $query->where('min_capacity', '<=', $guests)
                ->where('max_capacity', '>=', $guests);
        }

        return response()->json($this->responseService->getFormat(
            'Rooms successfully retrieved',
            $query->get()->makeHidden($hidden)
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
            Room::with(['type:id,name'])->find($id)->makeHidden('type_id')
        ));
    }
}

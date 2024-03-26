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
        $type = '%';
        if ($request->type) {
            $type = $request->type;
        }

        return response()->json($this->responseService->getFormat(
            'Rooms successfully retrieved',
            Room::with(['type:id,name'])
                ->where('type_id', 'LIKE', $type)
                ->get()
                ->makeHidden(['rate', 'description', 'type_id'])
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

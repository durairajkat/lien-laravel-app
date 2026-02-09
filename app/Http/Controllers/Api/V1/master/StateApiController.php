<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StateRequest;
use App\Http\Resources\StateResource;
use App\Models\State;

class StateApiController extends Controller
{
    public function getStates(StateRequest $request)
    {
        $states = State::select('id', 'name', 'short_code')->where('country_id', $request->country_id)->get();

        return StateResource::collection($states);
    }
}

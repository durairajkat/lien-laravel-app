<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\CountyRequest;
use App\Http\Resources\master\CountyResource;
use App\Models\Master\County;

class CountyApiCountroller extends Controller
{
    public function getCounties(CountyRequest $request)
    {
        $counties = County::select('id','state_id', 'name')->where('state_id', $request->state_id)->get();
        return CountyResource::collection($counties);
    }
}

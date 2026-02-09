<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Resources\master\ProjectTypeResource;
use App\Models\ProjectType;

class ProjectTypeApiController extends Controller
{
    public function index()
    {
        $types = ProjectType::all();
        return ProjectTypeResource::collection($types);
    }
}

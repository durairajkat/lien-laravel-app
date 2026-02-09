<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Resources\master\ProjectRoleResource;
use App\Models\ProjectRole;

class ProjectRoleApiController extends Controller
{
    public function index()
    {
        $roles = ProjectRole::all();
        return ProjectRoleResource::collection($roles);
    }
}

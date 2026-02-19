<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Resources\master\CountyResource;
use App\Models\Master\TaskAction;
use Illuminate\Http\Request;

class TaskActionController extends Controller
{
    public  function index()
    {
        $data = TaskAction::select('id', 'name')->orderBy('name')->get();
        return CountyResource::collection($data);
    }
}

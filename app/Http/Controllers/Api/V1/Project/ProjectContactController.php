<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectCompanyResource;
use App\Models\Company;

class ProjectContactController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $data = Company::with('contacts')->where('user_id', $user_id)->where('contact_type', 'project')->get();
        return ProjectCompanyResource::collection($data);
    }
}

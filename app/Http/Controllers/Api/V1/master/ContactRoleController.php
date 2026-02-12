<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ContactRoleRequest;
use App\Http\Resources\master\ContactRoleResource;
use App\Models\Master\ContactRole;

class ContactRoleController extends Controller
{
    public function index(ContactRoleRequest $request)
    {
        $type = $request->type;
        $data = ContactRole::select('id', 'name', 'role_type')->where('role_type', $type)->orderBy('name')->get();
        return ContactRoleResource::collection($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()
            ->groupBy('module');
        $roles  = Role::all();
        return view('admin.permission.index', compact('permissions', 'roles'));
    }
}

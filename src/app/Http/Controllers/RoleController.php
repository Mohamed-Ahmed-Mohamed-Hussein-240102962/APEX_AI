<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        // Require Admin middleware will be added at route level
        $roles = Role::with('permissions')->get();

        return view('admin.roles', compact('roles'));
    }
}

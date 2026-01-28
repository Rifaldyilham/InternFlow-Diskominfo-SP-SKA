<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleApiController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleApiController extends Controller
{
   public function index()
   {
    return Role::all();
   }
}

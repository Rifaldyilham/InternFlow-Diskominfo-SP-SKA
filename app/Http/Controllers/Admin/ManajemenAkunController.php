<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ManajemenAkunController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id_role')->get();
        return view('admin.manajemenakun', compact('users'));
    }
}

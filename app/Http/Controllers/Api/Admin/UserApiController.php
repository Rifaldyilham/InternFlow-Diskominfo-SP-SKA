<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->where('id_role', $request->role);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status_aktif', $request->status);
        }

        $users = $query->orderBy('id_user', 'desc')
                       ->paginate($request->per_page ?? 10);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'id_role'  => 'required|integer'
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'id_role'      => $request->id_role,
            'status_aktif' => 1,
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan'], 201);
    }

    public function show($id)
    {
        return User::with('role')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

    //     $user->update([
    //     'name'    => $request->name,
    //     'email'   => $request->email,
    //     'id_role' => $request->id_role,
    // ]);

        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email,' . $id . ',id_user',
            'id_role'  => 'required|integer',
            'status_aktif' => 'required|boolean',
        ]);

        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'id_role'      => $request->id_role,
            'status_aktif' => $request->status_aktif,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json(['message' => 'User berhasil diupdate']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User berhasil dihapus']);
    }
}

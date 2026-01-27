<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
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

    public function show($id)
    {
        return User::with(['role', 'pegawai'])->findOrFail($id_user = $id);
     }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'id_role'  => 'required|integer',
            'nip'      => 'nullable|string',
            'id_bidang'=> 'nullable|integer',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'id_role'      => $request->id_role,
                'status_aktif' => 1,
            ]);

            Pegawai::updateOrCreate(
                ['id_user' => $user->id_user],
                [
                    'nip'       => $request->nip,
                    'nama'      => $request->name,
                    'id_bidang' => $request->id_bidang,
                ]
            );
        });

        return response()->json(['message' => 'Berhasil ditambahkan'], 201);
    }

}

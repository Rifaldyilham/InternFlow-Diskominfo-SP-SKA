<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'pegawai']);

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

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage()
            ]
        ]);
    }

    public function show($id)
    {
        $user = User::with(['role', 'pegawai'])->find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            'id_role' => 'required|integer|exists:roles,id_roles',
            'nip' => 'nullable|string|max:20',
            'id_bidang' => 'nullable|integer|exists:bidang,id_bidang',
            'status_aktif' => 'required|boolean'
        ], [
            'password.regex' => 'Password harus mengandung huruf dan angka',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => $request->id_role,
                'status_aktif' => $request->status_aktif ?? 1,
            ]);

            // Create pegawai if role requires it
            if ($request->id_role == 2 || $request->id_role == 3) { // Admin Bidang or Mentor
                Pegawai::create([
                    'id_user' => $user->id_user,
                    'nip' => $request->nip,
                    'nama' => $request->name,
                    'id_bidang' => $request->id_bidang
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Akun berhasil dibuat',
                'user' => $user->load(['role', 'pegawai'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat akun',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'password' => 'nullable|min:8|confirmed|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            'id_role' => 'required|integer|exists:roles,id_roles',
            'nip' => 'nullable|string|max:20',
            'id_bidang' => 'nullable|integer|exists:bidang,id_bidang',
            'status_aktif' => 'required|boolean'
        ], [
            'password.regex' => 'Password harus mengandung huruf dan angka',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'id_role' => $request->id_role,
                'status_aktif' => $request->status_aktif
            ];

            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update or create pegawai
            if ($request->id_role == 2 || $request->id_role == 3) {
                Pegawai::updateOrCreate(
                    ['id_user' => $user->id_user],
                    [
                        'nip' => $request->nip,
                        'nama' => $request->name,
                        'id_bidang' => $request->id_bidang
                    ]
                );
            } else {
                // Hapus pegawai jika role berubah
                $user->pegawai()->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Akun berhasil diperbarui',
                'user' => $user->load(['role', 'pegawai'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui akun',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Hapus pegawai terkait jika ada
            if ($user->pegawai) {
                $user->pegawai()->delete();
            }
            
            $user->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Akun berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus akun',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
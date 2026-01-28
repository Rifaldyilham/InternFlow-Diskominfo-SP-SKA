<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BidangApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Bidang::with(['admin', 'peserta']);

        // Hitung jumlah peserta aktif per bidang
        $bidangs = $query->get()->map(function ($bidang) {
            $bidang->peserta_aktif = $bidang->peserta->where('status', 'aktif')->count();
            return $bidang;
        });

        return response()->json([
            'data' => $bidangs,
            'message' => 'Data bidang berhasil diambil'
        ]);
    }

    public function show($id)
    {
        $bidang = Bidang::with(['admin', 'peserta'])->find($id);
        
        if (!$bidang) {
            return response()->json(['message' => 'Bidang tidak ditemukan'], 404);
        }
        
        $bidang->peserta_aktif = $bidang->peserta->where('status', 'aktif')->count();
        
        return response()->json([
            'data' => $bidang,
            'message' => 'Detail bidang berhasil diambil'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bidang' => 'required|string|max:100|unique:bidang,nama_bidang',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif,penuh',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $bidang = Bidang::create([
                'nama_bidang' => $request->nama_bidang,
                'deskripsi' => $request->deskripsi,
                'kuota' => $request->kuota,
                'status' => $request->status,
                'id_admin' => null // Admin bisa diassign nanti
            ]);

            DB::commit();

            return response()->json([
                'data' => $bidang,
                'message' => 'Bidang berhasil ditambahkan'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan bidang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $bidang = Bidang::find($id);
        
        if (!$bidang) {
            return response()->json(['message' => 'Bidang tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_bidang' => 'required|string|max:100|unique:bidang,nama_bidang,' . $id . ',id_bidang',
            'deskripsi' => 'nullable|string',
            'kuota' => 'required|integer|min:1|max:100',
            'status' => 'required|in:aktif,nonaktif,penuh',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $bidang->update([
                'nama_bidang' => $request->nama_bidang,
                'deskripsi' => $request->deskripsi,
                'kuota' => $request->kuota,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'data' => $bidang,
                'message' => 'Bidang berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui bidang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $bidang = Bidang::find($id);
        
        if (!$bidang) {
            return response()->json(['message' => 'Bidang tidak ditemukan'], 404);
        }

        // Cek apakah ada peserta di bidang ini
        if ($bidang->peserta()->count() > 0) {
            return response()->json([
                'message' => 'Tidak dapat menghapus bidang karena masih terdapat peserta',
                'errors' => ['bidang' => ['Bidang ini masih memiliki peserta. Pindahkan peserta terlebih dahulu.']]
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $bidang->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Bidang berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus bidang',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAdmin($id)
    {
        $bidang = Bidang::with('admin')->find($id);
        
        if (!$bidang) {
            return response()->json(['message' => 'Bidang tidak ditemukan'], 404);
        }
        
        return response()->json([
            'data' => $bidang->admin,
            'message' => 'Admin bidang berhasil diambil'
        ]);
    }

    public function getPeserta($id)
    {
        $bidang = Bidang::with('peserta')->find($id);
        
        if (!$bidang) {
            return response()->json(['message' => 'Bidang tidak ditemukan'], 404);
        }
        
        // Ambil user data untuk setiap peserta
        $pesertaWithUsers = $bidang->peserta->map(function ($peserta) {
            $peserta->load('user');
            $peserta->name = $peserta->user->name ?? $peserta->nama;
            $peserta->email = $peserta->user->email ?? $peserta->email;
            return $peserta;
        });
        
        return response()->json([
            'data' => $pesertaWithUsers,
            'message' => 'Daftar peserta bidang berhasil diambil'
        ]);
    }
}
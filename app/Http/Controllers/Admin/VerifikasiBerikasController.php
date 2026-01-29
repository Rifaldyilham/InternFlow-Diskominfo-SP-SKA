<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaMagang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerifikasiBerikasController extends Controller
{
    // GET: Daftar peserta yang perlu verifikasi
    public function index()
    {
        $peserta = PesertaMagang::with(['user', 'bidang'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.verifikasi-berkas', compact('peserta'));
    }

    // GET: API daftar peserta yang perlu verifikasi
    public function apiList(): JsonResponse
    {
        $peserta = PesertaMagang::with(['user', 'bidang'])
            ->select([
                'id_pesertamagang',
                'nama',
                'nim',
                'email',
                'asal_univ',
                'program_studi',
                'status_verifikasi',
                'surat_penempatan_path',
                'cv_path',
                'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_pesertamagang,
                    'nama' => $item->nama,
                    'nim' => $item->nim,
                    'email' => $item->email,
                    'universitas' => $item->asal_univ,
                    'prodi' => $item->program_studi,
                    'status_verifikasi' => $item->status_verifikasi,
                    'surat_penempatan_path' => $item->surat_penempatan_path,
                    'cv_path' => $item->cv_path,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json(['data' => $peserta]);
    }

    // POST: Verifikasi atau tolak berkas peserta
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'peserta_id' => 'required|integer',
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $peserta = PesertaMagang::where('id_pesertamagang', $request->peserta_id)->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $peserta->status_verifikasi = $request->status;
        $peserta->catatan_verifikasi = $request->input('catatan');
        $peserta->save();

        return response()->json([
            'message' => 'Verifikasi berkas berhasil',
            'data' => [
                'id' => $peserta->id_pesertamagang,
                'status_verifikasi' => $peserta->status_verifikasi,
            ]
        ]);
    }

    // GET: Detail peserta dengan file paths
    public function detail($id): JsonResponse
    {
        $peserta = PesertaMagang::where('id_pesertamagang', $id)
            ->with(['user', 'bidang'])
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $peserta->id_pesertamagang,
                'nama' => $peserta->nama,
                'nim' => $peserta->nim,
                'email' => $peserta->email,
                'universitas' => $peserta->asal_univ,
                'prodi' => $peserta->program_studi,
                'status_verifikasi' => $peserta->status_verifikasi,
                'catatan_verifikasi' => $peserta->catatan_verifikasi,
                'surat_penempatan_path' => $peserta->surat_penempatan_path,
                'cv_path' => $peserta->cv_path,
                'surat_penempatan_url' => $peserta->surat_penempatan_path ? asset('storage/' . $peserta->surat_penempatan_path) : null,
                'cv_url' => $peserta->cv_path ? asset('storage/' . $peserta->cv_path) : null,
                'created_at' => $peserta->created_at,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaMagang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Bidang;

class VerifikasiBerikasController extends Controller
{
    // VIEW halaman admin
    public function index()
    {
        $bidang = Bidang::where('status', 'aktif')->get();
        return view('admin.verifikasi-berkas', compact('bidang'));
    }

    // ✅ API LIST (INI YANG DIPANGGIL JS)
    public function apiList(): JsonResponse
    {
        $peserta = PesertaMagang::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $peserta->map(function ($p) {
                return [
                    'id' => $p->id_pesertamagang,
                    'nama' => $p->nama,
                    'nim' => $p->nim,
                    'email' => $p->email,
                    'universitas' => $p->asal_univ,
                    'status_verifikasi' => $p->status_verifikasi,
                    'created_at' => $p->created_at,
                ];
            })
        ]);
    }

    // DETAIL peserta
    public function detail($id): JsonResponse
    {
        $p = PesertaMagang::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $p->id_pesertamagang,
                'nama' => $p->nama,
                'nim' => $p->nim,
                'email' => $p->email,
                'universitas' => $p->asal_univ,
                'program_studi' => $p->program_studi,
                'no_telp' => $p->no_telp,
                'tanggal_mulai' => $p->tanggal_mulai,
                'tanggal_selesai' => $p->tanggal_selesai,
                'bidang_pilihan' => $p->bidangPilihan
                ? $p->bidangPilihan->nama_bidang: '-',

                'bidang_pilihan_id' => $p->bidang_pilihan,
                'alasan' => $p->alasan,
                'status' => $p->status_verifikasi,

                // 🔥 SEMUA BERKAS
            'berkas' => [
                'CV / Resume' => $p->cv_path
                    ? asset('storage/'.$p->cv_path)
                    : null,

                'Surat Pengantar' => $p->surat_penempatan_path
                    ? asset('storage/'.$p->surat_penempatan_path)
                    : null,
            ]

            ]
        ]);
    }

    // TERIMA / TOLAK
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'peserta_id' => 'required',
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $p = PesertaMagang::findOrFail($request->peserta_id);

        if ($request->status === 'terverifikasi') {
            if (!$request->id_bidang) {
                return response()->json(['message' => 'Bidang penempatan wajib dipilih'], 422);
            }

            $p->update([
                'status_verifikasi' => 'terverifikasi',
                'status' => 'aktif',
                'id_bidang' => $request->id_bidang,
            ]);

        } else {
            $p->update([
            'status_verifikasi' => 'ditolak',
            'status' => 'aktif',
            'id_bidang' => null
            ]);
        
        }
        return response()->json(['message' => 'OK']);
    }
}
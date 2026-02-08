<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PesertaMagang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class MentorBimbinganController extends Controller
{
    public function stats()
    {
        try {
            $user = Auth::user();
            $pegawai = Pegawai::where('id_user', $user->id_user)->first();
            
            if (!$pegawai) {
                return response()->json([
                    'data' => [
                        'total' => 0,
                        'aktif' => 0
                    ]
                ]);
            }
            
            $total = PesertaMagang::where('id_pegawai', $pegawai->id_pegawai)->count();
            $today = \Illuminate\Support\Carbon::today();
            $aktif = PesertaMagang::where('id_pegawai', $pegawai->id_pegawai)
                ->where(function ($q) use ($today) {
                    $q->whereNull('tanggal_selesai')
                      ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->count();
            
            return response()->json([
                'data' => [
                    'total' => $total,
                    'aktif' => $aktif
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'total' => 0,
                    'aktif' => 0
                ]
            ]);
        }
    }
    
    public function peserta(Request $request)
    {
        try {
            $user = Auth::user();
            $pegawai = Pegawai::where('id_user', $user->id_user)->first();
            
            if (!$pegawai) {
                return response()->json([
                    'data' => [],
                    'meta' => [
                        'total' => 0,
                        'current_page' => 1,
                        'last_page' => 1
                    ]
                ]);
            }
            
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            
            $today = \Illuminate\Support\Carbon::today();
            $peserta = PesertaMagang::where('id_pegawai', $pegawai->id_pegawai)
                ->where(function ($q) use ($today) {
                    $q->whereNull('tanggal_selesai')
                      ->orWhereDate('tanggal_selesai', '>=', $today);
                })
                ->orderBy('nama')
                ->paginate($perPage, ['*'], 'page', $page);
            
            $data = $peserta->map(function($item) {
                return [
                    'id' => $item->id_pesertamagang,
                    'nama' => $item->nama,
                    'nim' => $item->nim,
                    'universitas' => $item->asal_univ,
                    'program_studi' => $item->program_studi,
                    'jurusan' => $item->program_studi,
                    'tanggal_masuk' => $item->tanggal_mulai,
                    'tanggal_mulai' => $item->tanggal_mulai,
                    'tanggal_selesai' => $item->tanggal_selesai,
                    'status' => $item->status,
                    'email' => $item->email,
                    'no_hp' => $item->no_telp,
                    'alasan' => $item->alasan,
                    'status_verifikasi' => $item->status_verifikasi
                ];
            });
            
            return response()->json([
                'data' => $data,
                'meta' => [
                    'current_page' => $peserta->currentPage(),
                    'per_page' => $peserta->perPage(),
                    'total' => $peserta->total(),
                    'last_page' => $peserta->lastPage()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1
                ]
            ]);
        }
    }
    
    public function detailPeserta($id)
    {
        try {
            $user = Auth::user();
            $pegawai = Pegawai::where('id_user', $user->id_user)->first();
            
            if (!$pegawai) {
                return response()->json([
                    'message' => 'Data mentor tidak ditemukan'
                ], 404);
            }
            
            $peserta = PesertaMagang::where('id_pesertamagang', $id)
                ->where('id_pegawai', $pegawai->id_pegawai)
                ->first();
                
            if (!$peserta) {
                return response()->json([
                    'message' => 'Peserta tidak ditemukan atau tidak dibimbing oleh Anda'
                ], 404);
            }
            
            return response()->json([
                'data' => [
                    'id' => $peserta->id_pesertamagang,
                    'nama' => $peserta->nama,
                    'nim' => $peserta->nim,
                    'universitas' => $peserta->asal_univ,
                    'program_studi' => $peserta->program_studi,
                    'jurusan' => $peserta->program_studi,
                    'tanggal_masuk' => $peserta->tanggal_mulai,
                    'tanggal_mulai' => $peserta->tanggal_mulai,
                    'tanggal_selesai' => $peserta->tanggal_selesai,
                    'status' => $peserta->status,
                    'email' => $peserta->email,
                    'no_hp' => $peserta->no_telp,
                    'alasan' => $peserta->alasan,
                    'status_verifikasi' => $peserta->status_verifikasi,
                    'catatan_verifikasi' => $peserta->catatan_verifikasi,
                    'surat_penempatan_path' => $peserta->surat_penempatan_path,
                    'cv_path' => $peserta->cv_path
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }
}

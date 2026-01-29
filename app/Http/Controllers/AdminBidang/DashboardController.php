<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaMagang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        // Validasi: pegawai harus ada dan harus punya id_bidang
        if (!$pegawai || !$pegawai->id_bidang) {
            return redirect('/login')->with('error', 'Akun Anda belum ditugaskan ke bidang. Hubungi admin.');
        }

        $idBidang = $pegawai->id_bidang;

        $peserta = PesertaMagang::where('id_bidang', $idBidang)
            ->with('pegawai')
            ->get();

        $mentor = Pegawai::where('id_bidang', $idBidang)
            ->whereHas('user', function ($a) {
                $a->where('id_role', 3);
            })
            ->get();

        return view('admin-bidang.mentor', compact('peserta', 'mentor'));
    }

    public function penempatan()
    {
        $pegawai = Auth::user()->pegawai;

        // Validasi: pegawai harus ada dan harus punya id_bidang
        if (!$pegawai || !$pegawai->id_bidang) {
            return redirect('/login')->with('error', 'Akun Anda belum ditugaskan ke bidang. Hubungi admin.');
        }

        $idBidang = $pegawai->id_bidang;

        $peserta = PesertaMagang::where('id_bidang', $idBidang)
            ->with('pegawai')
            ->get();

        $mentor = Pegawai::where('id_bidang', $idBidang)
            ->whereHas('user', function ($a) {
                $a->where('id_role', 3);
            })
            ->get();

        return view('admin-bidang.penempatan', compact('peserta', 'mentor'));
    }

    public function assignMentor(Request $request)
    {
        $request->validate([
            'id_pesertamagang' => 'required',
            'id_pegawai' => 'required',
        ]);

        PesertaMagang::where('id_pesertamagang', $request->id_pesertamagang)
            ->update([
                'id_pegawai' => $request->id_pegawai
            ]);

        return back()->with('Success', 'Mentor berhasil ditentukan');
    }

    // API: daftar peserta (JSON) untuk admin-bidang
    public function apiPeserta(Request $request): JsonResponse
    {
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai || !$pegawai->id_bidang) {
            return response()->json(['message' => 'Akun belum ditugaskan ke bidang'], 403);
        }

        $idBidang = $pegawai->id_bidang;
        $perPage = (int) $request->query('per_page', 10);

        $p = PesertaMagang::where('id_bidang', $idBidang)
            ->with('pegawai')
            ->paginate($perPage);

        // Map items to expected frontend shape
        $data = collect($p->items())->map(function ($item) {
            return [
                'id' => $item->id_pesertamagang,
                'nama' => $item->nama,
                'nim' => $item->nim,
                'universitas' => $item->asal_univ ?? null,
                'prodi' => $item->program_studi ?? null,
                'tanggal_mulai' => $item->tanggal_mulai ?? null,
                'tanggal_selesai' => $item->tanggal_selesai ?? null,
                'mentor_id' => $item->id_pegawai,
                'mentor_nama' => $item->pegawai?->nama ?? null,
                'status_penempatan' => $item->id_pegawai ? 'assigned' : 'unassigned',
                'email' => $item->email ?? null,
                'telepon' => $item->no_telp ?? null,
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $p->currentPage(),
                'per_page' => $p->perPage(),
                'total' => $p->total(),
                'last_page' => $p->lastPage(),
            ]
        ]);
    }

    // API: daftar mentor (JSON) untuk admin-bidang
    public function apiMentor(): JsonResponse
    {
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai || !$pegawai->id_bidang) {
            return response()->json(['message' => 'Akun belum ditugaskan ke bidang'], 403);
        }

        $idBidang = $pegawai->id_bidang;

        $mentors = Pegawai::where('id_bidang', $idBidang)
            ->whereHas('user', function ($q) {
                $q->where('id_role', 3);
            })->get();

        $data = $mentors->map(function ($m) {
            $jumlah = $m->pesertamagang()->count();
            return [
                'id' => $m->id_pegawai,
                'nama' => $m->nama,
                'email' => $m->user?->email ?? null,
                'kapasitas' => $m->kapasitas ?? 5,
                'jumlah_bimbingan' => $jumlah,
                'status' => 'aktif',
            ];
        });

        return response()->json(['data' => $data]);
    }

    // API: assign peserta ke mentor (JSON)
    public function apiAssign(Request $request): JsonResponse
    {
        $request->validate([
            'peserta_id' => 'required|integer',
            'mentor_id' => 'required|integer',
        ]);

        $peserta = PesertaMagang::where('id_pesertamagang', $request->peserta_id)->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $peserta->id_pegawai = $request->mentor_id;
        $peserta->save();

        return response()->json(['message' => 'Penempatan berhasil']);
    }

    public function apiPesertaDetail($id): JsonResponse
    {
        $peserta = PesertaMagang::with('pegawai')->where('id_pesertamagang', $id)->first();
        if (!$peserta) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $item = [
            'id' => $peserta->id_pesertamagang,
            'nama' => $peserta->nama,
            'nim' => $peserta->nim,
            'universitas' => $peserta->asal_univ ?? null,
            'prodi' => $peserta->program_studi ?? null,
            'tanggal_mulai' => $peserta->tanggal_mulai ?? null,
            'tanggal_selesai' => $peserta->tanggal_selesai ?? null,
            'mentor_id' => $peserta->id_pegawai,
            'mentor_nama' => $peserta->pegawai?->nama ?? null,
            'email' => $peserta->email ?? null,
            'telepon' => $peserta->no_telp ?? null,
        ];

        return response()->json(['data' => $item]);
    }

    public function apiMentorDetail($id): JsonResponse
    {
        $mentor = Pegawai::with('user')->where('id_pegawai', $id)->first();
        if (!$mentor) return response()->json(['message' => 'Tidak ditemukan'], 404);

        $jumlah = $mentor->pesertamagang()->count();

        $item = [
            'id' => $mentor->id_pegawai,
            'nama' => $mentor->nama,
            'email' => $mentor->user?->email ?? null,
            'kapasitas' => $mentor->kapasitas ?? 5,
            'jumlah_bimbingan' => $jumlah,
            'status' => 'aktif',
        ];

        return response()->json(['data' => $item]);
    }
}

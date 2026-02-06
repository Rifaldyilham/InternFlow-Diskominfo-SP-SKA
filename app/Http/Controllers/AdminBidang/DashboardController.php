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

    // API: daftar peserta (JSON) untuk admin-bidang - PERBAIKI
    // C:\MAGANG\monitoring-magang\monitoring-magang\app\Http\Controllers\AdminBidang\DashboardController.php

// API: daftar peserta - PASTIKAN INI
public function apiPeserta(Request $request): JsonResponse
{
    $pegawai = Auth::user()->pegawai;
    if (!$pegawai || !$pegawai->id_bidang) {
        return response()->json(['message' => 'Akun belum ditugaskan ke bidang'], 403);
    }

    $idBidang = $pegawai->id_bidang;
    
    // DEBUG: Tampilkan id_bidang admin
    \Log::info('Admin bidang ID:', ['id_bidang' => $idBidang, 'pegawai_id' => $pegawai->id_pegawai]);

    // Hanya ambil peserta yang sudah diverifikasi dan ditempatkan di bidang ini
    $query = PesertaMagang::where('id_bidang', $idBidang)
        ->where('status_verifikasi', 'terverifikasi')
        ->with(['pegawai', 'bidangPilihan', 'bidang']);

    $perPage = (int) $request->query('per_page', 10);
    $p = $query->paginate($perPage);

    // DEBUG: Tampilkan query SQL dan hasil
    \Log::info('Query peserta:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
    \Log::info('Jumlah peserta ditemukan:', ['total' => $p->total()]);

    // Map items to expected frontend shape
    $data = collect($p->items())->map(function ($item) {
        // DEBUG setiap peserta
        \Log::info('Peserta detail:', [
            'id' => $item->id_pesertamagang,
            'nama' => $item->nama,
            'id_pegawai' => $item->id_pegawai,
            'id_bidang' => $item->id_bidang
        ]);

        return [
            'id' => $item->id_pesertamagang,
            'nama' => $item->nama,
            'nim' => $item->nim,
            'universitas' => $item->asal_univ ?? null,
            'prodi' => $item->program_studi ?? null,
            'tanggal_mulai' => $item->tanggal_mulai ?? null,
            'tanggal_selesai' => $item->tanggal_selesai ?? null,
            'mentor_id' => $item->id_pegawai, // INI YANG PENTING: null = belum punya mentor
            'mentor_nama' => $item->pegawai ? $item->pegawai->nama : null,
            'status_penempatan' => $item->id_pegawai ? 'assigned' : 'unassigned',
            'email' => $item->email ?? null,
            'telepon' => $item->no_telp ?? null,
            'alasan' => $item->alasan ?? null,
            'bidang_pilihan' => $item->bidangPilihan ? $item->bidangPilihan->nama_bidang : null,
            'catatan_verifikasi' => $item->catatan_verifikasi ?? null,
            'bidang_penempatan' => $item->bidang ? $item->bidang->nama_bidang : null,
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

    // Ambil mentor dari tabel pegawai yang memiliki role mentor (role_id = 3)
    $mentors = Pegawai::where('id_bidang', $idBidang)
        ->whereHas('user', function ($q) {
            $q->where('id_role', 3); // Role mentor
        })
        ->with(['user', 'pesertamagang']) // Load relasi
        ->get();

    $data = $mentors->map(function ($mentor) {
        // Hitung jumlah peserta yang sedang dibimbing
        $jumlahBimbingan = $mentor->pesertamagang->where('status', 'aktif')->count();
        
        return [
            'id' => $mentor->id_pegawai,
            'nama' => $mentor->nama,
            'nip' => $mentor->nip, // TAMBAHKAN NIP
            'email' => $mentor->user->email ?? null,
            'kapasitas' => 5, // Default kapasitas
            'jumlah_bimbingan' => $jumlahBimbingan,
            'status' => 'aktif', // Default status
            'jabatan' => 'Mentor',
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
    $peserta = PesertaMagang::with(['pegawai', 'bidangPilihan', 'bidang'])
        ->where('id_pesertamagang', $id)
        ->first();
        
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
        'alasan' => $peserta->alasan ?? null,
        'bidang_pilihan' => $peserta->bidangPilihan?->nama_bidang ?? null,
        'catatan_verifikasi' => $peserta->catatan_verifikasi ?? null,
        'bidang_penempatan' => $peserta->bidang?->nama_bidang ?? null,
        'status_verifikasi' => $peserta->status_verifikasi ?? 'pending',
        'surat_penempatan_path' => $peserta->surat_penempatan_path ?\Illuminate\Support\Facades\Storage::url($peserta->surat_penempatan_path) : null,
        'cv_path' => $peserta->cv_path ? \Illuminate\Support\Facades\Storage::url($peserta->cv_path): null,
    ];

    return response()->json(['data' => $item]);
}

    public function apiMentorDetail($id): JsonResponse
{
    $pegawai = Auth::user()->pegawai;
    if (!$pegawai || !$pegawai->id_bidang) {
        return response()->json(['message' => 'Akun belum ditugaskan ke bidang'], 403);
    }

    $mentor = Pegawai::where('id_pegawai', $id)
        ->where('id_bidang', $pegawai->id_bidang) // Pastikan mentor di bidang yang sama
        ->with(['user', 'pesertamagang' => function($query) {
            $query->where('status', 'aktif')
                  ->with(['bidang', 'bidangPilihan']);
        }])
        ->first();
        
    if (!$mentor) {
        return response()->json(['message' => 'Mentor tidak ditemukan'], 404);
    }

    $jumlahBimbingan = $mentor->pesertamagang->count();
    $peserta = $mentor->pesertamagang->map(function ($p) {
        return [
            'id' => $p->id_pesertamagang,
            'nama' => $p->nama,
            'nim' => $p->nim,
            'universitas' => $p->asal_univ,
            'prodi' => $p->program_studi,
            'bidang' => $p->bidang ? $p->bidang->nama_bidang : null,
            'bidang_pilihan' => $p->bidangPilihan ? $p->bidangPilihan->nama_bidang : null,
            'tanggal_mulai' => $p->tanggal_mulai,
            'tanggal_selesai' => $p->tanggal_selesai,
        ];
    });

    $data = [
        'id' => $mentor->id_pegawai,
        'nama' => $mentor->nama,
        'nip' => $mentor->nip,
        'email' => $mentor->user->email ?? null,
        'kapasitas' => 5,
        'jumlah_bimbingan' => $jumlahBimbingan,
        'status' => 'aktif',
        'jabatan' => 'Mentor',
        'created_at' => $mentor->created_at,
        'peserta' => $peserta,
    ];

    return response()->json(['data' => $data]);
}
}

<?php

namespace App\Http\Controllers\PesertaMagang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logbook;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function status()
    {
        $user = auth()->user();
        $peserta = null;
        if ($user) {
            $peserta = \App\Models\PesertaMagang::with(['bidang', 'pegawai'])
                ->where('id_user', $user->id_user)
                ->orderByDesc('created_at')
                ->first();
        }
        if (!$peserta) {
            return response()->json([
                'status_verifikasi' => 'pending',
                'status_magang' => null,
                'magang' => null,
                'stats' => null
            ]);
        }

        $today = Carbon::today();
        $start = $peserta->tanggal_mulai ? Carbon::parse($peserta->tanggal_mulai) : null;
        $end = $peserta->tanggal_selesai ? Carbon::parse($peserta->tanggal_selesai) : null;

        $totalHari = ($start && $end) ? $start->diffInDays($end) + 1 : 0;
        $hariKe = 0;
        if ($start) {
            if ($today->lt($start)) {
                $hariKe = 0;
            } else {
                $hariKe = $start->diffInDays($today) + 1;
                if ($totalHari > 0) {
                    $hariKe = min($hariKe, $totalHari);
                }
            }
        }

        $totalLogbook = Logbook::where('id_pesertamagang', $peserta->id_pesertamagang)->count();
        $menunggu = Logbook::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->where('status', 'belum diverifikasi')
            ->count();
        $belumDiisi = max(0, $totalHari - $totalLogbook);

        return response()->json([
            'status_verifikasi' => $peserta->status_verifikasi,
            'status_magang' => $peserta->status,
            'magang' => [
                'id' => $peserta->id_pesertamagang,
                'bidang' => $peserta->bidang ? $peserta->bidang->nama_bidang : null,
                'mentor' => $peserta->pegawai ? $peserta->pegawai->nama : null,
                'tanggal_mulai' => $peserta->tanggal_mulai,
                'tanggal_selesai' => $peserta->tanggal_selesai,
                'status' => $peserta->status,
                'hari_ke' => $hariKe,
                'total_hari' => $totalHari
            ],
            'stats' => [
                'total_hari' => $totalHari,
                'sudah_diisi' => $totalLogbook,
                'menunggu' => $menunggu,
                'belum_diisi' => $belumDiisi
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            // NOTE: required karena kolom bukti_kegiatan di DB belum nullable
            'bukti_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $peserta = auth()->user()->peserta;
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan.'], 404);
        }

        // Validasi tanggal dalam periode magang
        if ($peserta->tanggal_mulai && $request->tanggal < $peserta->tanggal_mulai) {
            return response()->json([
                'message' => 'Tanggal logbook sebelum masa magang dimulai.'
            ], 422);
        }

        if ($peserta->tanggal_selesai && $request->tanggal > $peserta->tanggal_selesai) {
            return response()->json([
                'message' => 'Tanggal logbook sudah melewati masa magang.'
            ], 422);
        }

        // Cegah input tanggal di masa depan
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        if ($request->tanggal > $today) {
            return response()->json([
                'message' => 'Tanggal logbook tidak boleh di masa depan.'
            ], 422);
        }

        // Harus sudah absen HADIR pada tanggal tersebut
        $absensi = Absensi::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->whereDate('waktu_absen', $request->tanggal)
            ->first();

        if (!$absensi) {
            return response()->json([
                'message' => 'Silakan absen terlebih dahulu pada tanggal tersebut sebelum mengisi logbook.'
            ], 422);
        }

        if ($absensi->status !== 'hadir') {
            return response()->json([
                'message' => 'Logbook hanya dapat diisi jika absensi berstatus HADIR.'
            ], 422);
        }

        // Cegah logbook jika absensi izin/sakit pada tanggal yang sama
        $absensiIzinSakit = Absensi::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->whereDate('waktu_absen', $request->tanggal)
            ->whereIn('status', ['izin', 'sakit'])
            ->exists();
        if ($absensiIzinSakit) {
            return response()->json([
                'message' => 'Tidak bisa mengisi logbook pada tanggal izin/sakit.'
            ], 422);
        }

        // Cegah duplikat logbook di tanggal yang sama
        $alreadyExists = Logbook::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->where('tanggal', $request->tanggal)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'message' => 'Logbook untuk tanggal ini sudah ada.'
            ], 409);
        }

        $path = $request->file('bukti_file')
            ->store('bukti-logbook', 'public');

        $logbook = Logbook::create([
            'id_pesertamagang' => $peserta->id_pesertamagang,
            'nama_kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'bukti_kegiatan' => $path,
            'status' => 'belum diverifikasi',
            'catatan_mentor' => '',
        ]);

        return response()->json([
            'message' => 'Logbook berhasil disimpan.',
            'data' => $logbook
        ], 201);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $peserta = null;
        if ($user) {
            $peserta = \App\Models\PesertaMagang::where('id_user', $user->id_user)
                ->orderByDesc('created_at')
                ->first();
        }

        if (!$peserta) {
            return response()->json([
                'current_page' => 1,
                'last_page' => 1,
                'data' => []
            ]);
        }

        $query = Logbook::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_logbook');

        if ($request->filled('bulan') && $request->bulan !== 'all') {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'verified') {
                $query->where('status', 'diverifikasi');
            } elseif ($request->status === 'pending') {
                $query->where('status', 'belum diverifikasi');
            } elseif ($request->status === 'izin') {
                $query->where('status', 'izin');
            } else {
                $query->where('status', $request->status);
            }
        }

        $perPage = 10;
        $paginated = $query->paginate($perPage);

        $data = $paginated->getCollection()->map(function ($item) {
            return [
                'id' => $item->id_logbook,
                'tanggal' => $item->tanggal,
                'kegiatan' => $item->nama_kegiatan,
                'deskripsi' => $item->deskripsi,
                'status' => $item->status,
                'bukti' => $item->bukti_kegiatan,
                'catatan_mentor' => $item->catatan_mentor,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'data' => $data
        ]);
    }
}

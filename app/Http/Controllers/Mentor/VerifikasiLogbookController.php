<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\Pesertamagang;
use Illuminate\Http\Request;

class VerifikasiLogbookController extends Controller
{
    public function index(Request $request)
    {
        $mentor = auth()->user()->pegawai;
        if (!$mentor) {
            return response()->json(['message' => 'Mentor tidak ditemukan'], 404);
        }

        $query = Logbook::with('peserta')
            ->whereHas('peserta', function ($q) use ($mentor) {
                $q->where('id_pegawai', $mentor->id_pegawai);
            })
            ->orderByDesc('tanggal');

        if ($request->filled('pesertaId')) {
            $query->where('id_pesertamagang', $request->pesertaId);
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id_logbook,
                'tanggal' => $item->tanggal,
                'kegiatan' => $item->nama_kegiatan,
                'deskripsi' => $item->deskripsi,
                'status' => $item->status,
                'bukti' => $item->bukti_kegiatan,
                'catatan_mentor' => $item->catatan_mentor,
                'peserta' => [
                    'id' => $item->peserta->id_pesertamagang ?? null,
                    'nama' => $item->peserta->nama ?? '-',
                    'nim' => $item->peserta->nim ?? '-'
                ],
                'updated_at' => $item->updated_at,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function detail($pesertaId, $logbookId)
    {
        $mentor = auth()->user()->pegawai;
        if (!$mentor) {
            return response()->json(['message' => 'Mentor tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $mentor->id_pegawai)
            ->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak valid'], 403);
        }

        $logbook = Logbook::where('id_logbook', $logbookId)
            ->where('id_pesertamagang', $pesertaId)
            ->first();
        if (!$logbook) {
            return response()->json(['message' => 'Logbook tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $logbook->id_logbook,
                'tanggal' => $logbook->tanggal,
                'kegiatan' => $logbook->nama_kegiatan,
                'deskripsi' => $logbook->deskripsi,
                'status' => $logbook->status,
                'bukti' => $logbook->bukti_kegiatan,
                'catatan_mentor' => $logbook->catatan_mentor,
                'waktu_mulai' => $logbook->waktu_mulai ?? null,
                'waktu_selesai' => $logbook->waktu_selesai ?? null,
                'created_at' => $logbook->created_at,
                'updated_at' => $logbook->updated_at,
            ]
        ]);
    }

    public function stats($pesertaId)
    {
        $mentor = auth()->user()->pegawai;
        if (!$mentor) {
            return response()->json(['message' => 'Mentor tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $mentor->id_pegawai)
            ->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak valid'], 403);
        }

        $logbookTotal = Logbook::where('id_pesertamagang', $pesertaId)->count();
        $logbookPending = Logbook::where('id_pesertamagang', $pesertaId)
            ->where('status', 'belum diverifikasi')
            ->count();

        $absensiTotal = Absensi::where('id_pesertamagang', $pesertaId)->count();

        return response()->json([
            'data' => [
                'logbook_total' => $logbookTotal,
                'logbook_pending' => $logbookPending,
                'absensi_total' => $absensiTotal,
            ]
        ]);
    }

    // Verifikasi logbook
    public function verify(Request $request)
    {
        $request->validate([
            'logbook_id' => 'required|integer',
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        $mentor = auth()->user()->pegawai;
        if (!$mentor) {
            return response()->json(['message' => 'Mentor tidak ditemukan'], 404);
        }

        $logbook = Logbook::where('id_logbook', $request->logbook_id)->first();
        if (!$logbook) {
            return response()->json(['message' => 'Logbook tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $logbook->id_pesertamagang)
            ->where('id_pegawai', $mentor->id_pegawai)
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Anda tidak berhak memverifikasi logbook ini'], 403);
        }

        $statusDb = $request->status === 'approved' ? 'diverifikasi' : 'ditolak';

        $logbook->status = $statusDb;
        $logbook->catatan_mentor = $request->catatan ?? '';
        $logbook->save();

        return response()->json(['message' => 'Logbook berhasil diverifikasi']);
    }
}

<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
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
        $logbook->catatan_mentor = $request->catatan;
        $logbook->save();

        return response()->json(['message' => 'Logbook berhasil diverifikasi']);
    }
}

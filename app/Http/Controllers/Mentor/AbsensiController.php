<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pesertamagang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = auth()->user()->pegawai->id_pegawai;

        // tanggal yang dipilih mentor
        $tanggal = $request->tanggal 
            ? Carbon::parse($request->tanggal)->toDateString()
            : now()->toDateString();

        // ambil semua peserta bimbingan mentor
        $pesertaList = Pesertamagang::where('id_pegawai', $pegawaiId)->get();

        $result = [];

        foreach ($pesertaList as $peserta) {

            // cek apakah peserta ini absen di tanggal tsb
            $absen = Absensi::where('id_pesertamagang', $peserta->id_pesertamagang)
                ->whereDate('waktu_absen', $tanggal)
                ->first();

            if ($absen) {
                // ADA ABSENSI
                $result[] = [
                    'nama'   => $peserta->nama,
                    'status' => $absen->status, 
                    'waktu'  => $absen->waktu_absen,
                    'lokasi' => $absen->lokasi,
                    'bukti'  => $absen->bukti_kegiatan,
                ];
            } else {
                // TIDAK ADA ABSENSI = ALPHA
                $result[] = [
                    'nama'   => $peserta->nama,
                    'status' => 'alpha',
                    'waktu'  => null,
                    'lokasi' => null,
                    'bukti'  => null,
                ];
            }
        }

        return response()->json($result);
    }

    public function byPeserta(Request $request, $pesertaId)
    {
        $pegawaiId = auth()->user()->pegawai->id_pegawai;

        // Pastikan peserta ini bimbingan mentor tsb
        $isValid = \App\Models\Pesertamagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $pegawaiId)
            ->exists();

        if (!$isValid) {
            return response()->json([
                'message' => 'Peserta tidak valid'
            ], 403);
        }

        // Ambil absensi peserta ini
        $query = \App\Models\Absensi::where('id_pesertamagang', $pesertaId);

        // FILTER TANGGAL (dari UI)
        if ($request->date) {
            $query->whereDate('waktu_absen', $request->date);
        }

        $absensi = $query
            ->orderBy('waktu_absen', 'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'id'           => $a->id_absensi,
                    'tanggal'      => $a->waktu_absen->toDateString(),
                    'waktu_submit' => $a->waktu_absen->format('H:i'),
                    'status'       => $a->status,
                    'lokasi'       => $a->lokasi,
                    'bukti'        => $a->bukti_kegiatan,
                    'koordinat'    => null,
                    'keterangan'   => null,
                    'created_at'   => $a->created_at,
                ];
            });

        return response()->json([
            'data' => $absensi
        ]);
    }
}

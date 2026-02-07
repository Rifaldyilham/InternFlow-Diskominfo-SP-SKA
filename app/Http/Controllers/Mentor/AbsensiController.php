<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pesertamagang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('id_user', $user->id_user)->first();

        if (!$pegawai) {
            return response()->json([
                'data' => []
            ]);
        }
        
        $pegawaiId = $pegawai->id_pegawai;

        // tanggal yang dipilih mentor (opsional)
        $tanggal = $request->tanggal
            ? Carbon::parse($request->tanggal)->toDateString()
            : null;

        // ambil semua peserta bimbingan mentor
        $pesertaList = Pesertamagang::where('id_pegawai', $pegawaiId)->get();

        $pesertaIds = $pesertaList->pluck('id_pesertamagang');
        $result = [];

        if ($tanggal) {
            $absenMap = collect();

            if ($pesertaIds->isNotEmpty()) {
                $absenMap = Absensi::whereIn('id_pesertamagang', $pesertaIds)
                    ->whereDate('waktu_absen', $tanggal)
                    ->get()
                    ->keyBy('id_pesertamagang');
            }

            foreach ($pesertaList as $peserta) {
                if ($peserta->tanggal_mulai) {
                    $mulai = Carbon::parse($peserta->tanggal_mulai)->toDateString();
                    if ($tanggal < $mulai) {
                        continue;
                    }
                }
                if ($peserta->tanggal_selesai) {
                    $selesai = Carbon::parse($peserta->tanggal_selesai)->toDateString();
                    if ($tanggal > $selesai) {
                        continue;
                    }
                }

                $absen = $absenMap->get($peserta->id_pesertamagang);

                if ($absen) {
                    $waktuAbsen = $absen->waktu_absen instanceof \Carbon\Carbon
                        ? $absen->waktu_absen
                        : Carbon::parse($absen->waktu_absen);
                   
                    $result[] = [
                        'id'           => $absen->id_absensi,
                        'nama'         => $peserta->nama,
                        'tanggal'      => $waktuAbsen ? $waktuAbsen->toDateString() : $tanggal,
                        'waktu_submit' => $waktuAbsen ? $waktuAbsen->format('H:i') : null,
                        'status'       => $absen->status,
                        'lokasi'       => $absen->lokasi,
                        'bukti'        => $absen->bukti_kegiatan,
                        'created_at'   => $absen->created_at,
                    ];
                } else {
                    // TIDAK ADA ABSENSI = ALPHA (hanya untuk tanggal yang dipilih)
                    $result[] = [
                        'id'           => null,
                        'nama'         => $peserta->nama,
                        'tanggal'      => $tanggal,
                        'waktu_submit' => null,
                        'status'       => 'alpha',
                        'lokasi'       => null,
                        'bukti'        => null,
                        'created_at'   => null,
                    ];
                }
            }
        } else {
            // Jika tidak ada filter tanggal, tampilkan semua absensi peserta (tanpa alpha)
            if ($pesertaIds->isNotEmpty()) {
                $absensi = Absensi::whereIn('id_pesertamagang', $pesertaIds)
                    ->orderBy('waktu_absen', 'desc')
                    ->get();

                foreach ($absensi as $absen) {
                    $waktuAbsen = $absen->waktu_absen instanceof \Carbon\Carbon
                        ? $absen->waktu_absen
                        : Carbon::parse($absen->waktu_absen);

                    $peserta = $pesertaList->firstWhere('id_pesertamagang', $absen->id_pesertamagang);

                    $result[] = [
                        'id'           => $absen->id_absensi,
                        'nama'         => $peserta?->nama,
                        'tanggal'      => $waktuAbsen ? $waktuAbsen->toDateString() : null,
                        'waktu_submit' => $waktuAbsen ? $waktuAbsen->format('H:i') : null,
                        'status'       => $absen->status,
                        'lokasi'       => $absen->lokasi,
                        'bukti'        => $absen->bukti_kegiatan,
                        'created_at'   => $absen->created_at,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $result
        ]);
    }

    public function byPeserta(Request $request, $pesertaId)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('id_user', $user->id_user)->first();

        if (!$pegawai) {
            return response()->json([
                'message' => 'Data mentor tidak ditemukan'
            ], 404);
        }
        
        $pegawaiId = $pegawai->id_pegawai;

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
                $waktu = $a->waktu_absen instanceof \Carbon\Carbon
                    ? $a->waktu_absen
                    : ($a->waktu_absen ? Carbon::parse($a->waktu_absen) : null);
                return [
                    'id'           => $a->id_absensi,
                    'tanggal'      => $waktu ? $waktu->toDateString() : null,
                    'waktu_submit' => $waktu ? $waktu->format('H:i') : null,
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

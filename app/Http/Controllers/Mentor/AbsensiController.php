<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PesertaMagang;
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
        $tanggalInput = $request->tanggal ?: $request->date;
        $tanggal = $tanggalInput
            ? Carbon::parse($tanggalInput)->toDateString()
            : null;

        // ambil semua peserta bimbingan mentor
        $pesertaList = PesertaMagang::where('id_pegawai', $pegawaiId)->get();

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
            // Jika tidak ada filter tanggal, tampilkan histori harian (termasuk alpha)
            if ($pesertaIds->isNotEmpty()) {
                $today = Carbon::today()->toDateString();
                $absensi = Absensi::whereIn('id_pesertamagang', $pesertaIds)
                    ->whereDate('waktu_absen', '<=', $today)
                    ->get();

                $absensiByPeserta = $absensi->groupBy('id_pesertamagang');

                foreach ($pesertaList as $peserta) {
                    $rowsPeserta = $absensiByPeserta->get($peserta->id_pesertamagang, collect());

                    $startDate = $peserta->tanggal_mulai
                        ? Carbon::parse($peserta->tanggal_mulai)->toDateString()
                        : null;
                    $endDate = $peserta->tanggal_selesai
                        ? Carbon::parse($peserta->tanggal_selesai)->toDateString()
                        : $today;

                    if ($endDate > $today) {
                        $endDate = $today;
                    }

                    if (!$startDate) {
                        $firstAbsensi = $rowsPeserta
                            ->sortBy('waktu_absen')
                            ->first();
                        $startDate = $firstAbsensi
                            ? Carbon::parse($firstAbsensi->waktu_absen)->toDateString()
                            : $today;
                    }

                    if ($startDate > $endDate) {
                        continue;
                    }

                    $absenByDate = $rowsPeserta->keyBy(function ($a) {
                        return Carbon::parse($a->waktu_absen)->toDateString();
                    });

                    for ($cursor = Carbon::parse($startDate); $cursor->lte(Carbon::parse($endDate)); $cursor->addDay()) {
                        $currentDate = $cursor->toDateString();
                        $absen = $absenByDate->get($currentDate);

                        if ($absen) {
                            $waktuAbsen = $absen->waktu_absen instanceof \Carbon\Carbon
                                ? $absen->waktu_absen
                                : Carbon::parse($absen->waktu_absen);

                            $result[] = [
                                'id'           => $absen->id_absensi,
                                'nama'         => $peserta->nama,
                                'tanggal'      => $waktuAbsen ? $waktuAbsen->toDateString() : $currentDate,
                                'waktu_submit' => $waktuAbsen ? $waktuAbsen->format('H:i') : null,
                                'status'       => $absen->status,
                                'lokasi'       => $absen->lokasi,
                                'bukti'        => $absen->bukti_kegiatan,
                                'created_at'   => $absen->created_at,
                            ];
                        } else {
                            $result[] = [
                                'id'           => null,
                                'nama'         => $peserta->nama,
                                'tanggal'      => $currentDate,
                                'waktu_submit' => null,
                                'status'       => 'alpha',
                                'lokasi'       => null,
                                'bukti'        => null,
                                'created_at'   => null,
                            ];
                        }
                    }
                }
            }

            usort($result, function ($a, $b) {
                if ($a['tanggal'] === $b['tanggal']) {
                    return strcmp($a['nama'] ?? '', $b['nama'] ?? '');
                }
                return strcmp($b['tanggal'] ?? '', $a['tanggal'] ?? '');
            });
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
        $peserta = \App\Models\PesertaMagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $pegawaiId)
            ->first();

        if (!$peserta) {
            return response()->json([
                'message' => 'Peserta tidak valid'
            ], 403);
        }

        $tanggalInput = $request->date ?: $request->tanggal;
        $tanggal = $tanggalInput
            ? Carbon::parse($tanggalInput)->toDateString()
            : null;

        if ($tanggal) {
            if ($peserta->tanggal_mulai) {
                $mulai = Carbon::parse($peserta->tanggal_mulai)->toDateString();
                if ($tanggal < $mulai) {
                    return response()->json(['data' => []]);
                }
            }
            if ($peserta->tanggal_selesai) {
                $selesai = Carbon::parse($peserta->tanggal_selesai)->toDateString();
                if ($tanggal > $selesai) {
                    return response()->json(['data' => []]);
                }
            }

            $absen = \App\Models\Absensi::where('id_pesertamagang', $pesertaId)
                ->whereDate('waktu_absen', $tanggal)
                ->first();

            if (!$absen) {
                return response()->json([
                    'data' => [[
                        'id'           => null,
                        'tanggal'      => $tanggal,
                        'waktu_submit' => null,
                        'status'       => 'alpha',
                        'lokasi'       => null,
                        'bukti'        => null,
                        'koordinat'    => null,
                        'keterangan'   => null,
                        'created_at'   => null,
                    ]]
                ]);
            }

            $absensi = collect([$absen])->map(function ($a) {
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
        } else {
            $today = Carbon::today()->toDateString();
            $rows = \App\Models\Absensi::where('id_pesertamagang', $pesertaId)
                ->whereDate('waktu_absen', '<=', $today)
                ->get();

            $startDate = $peserta->tanggal_mulai
                ? Carbon::parse($peserta->tanggal_mulai)->toDateString()
                : null;
            $endDate = $peserta->tanggal_selesai
                ? Carbon::parse($peserta->tanggal_selesai)->toDateString()
                : $today;

            if ($endDate > $today) {
                $endDate = $today;
            }

            if (!$startDate) {
                $firstAbsensi = $rows->sortBy('waktu_absen')->first();
                $startDate = $firstAbsensi
                    ? Carbon::parse($firstAbsensi->waktu_absen)->toDateString()
                    : $today;
            }

            $mapped = collect();
            if ($startDate <= $endDate) {
                $absenByDate = $rows->keyBy(function ($a) {
                    return Carbon::parse($a->waktu_absen)->toDateString();
                });

                for ($cursor = Carbon::parse($endDate); $cursor->gte(Carbon::parse($startDate)); $cursor->subDay()) {
                    $currentDate = $cursor->toDateString();
                    $a = $absenByDate->get($currentDate);

                    if ($a) {
                        $waktu = $a->waktu_absen instanceof \Carbon\Carbon
                            ? $a->waktu_absen
                            : ($a->waktu_absen ? Carbon::parse($a->waktu_absen) : null);
                        $mapped->push([
                            'id'           => $a->id_absensi,
                            'tanggal'      => $waktu ? $waktu->toDateString() : $currentDate,
                            'waktu_submit' => $waktu ? $waktu->format('H:i') : null,
                            'status'       => $a->status,
                            'lokasi'       => $a->lokasi,
                            'bukti'        => $a->bukti_kegiatan,
                            'koordinat'    => null,
                            'keterangan'   => null,
                            'created_at'   => $a->created_at,
                        ]);
                    } else {
                        $mapped->push([
                            'id'           => null,
                            'tanggal'      => $currentDate,
                            'waktu_submit' => null,
                            'status'       => 'alpha',
                            'lokasi'       => null,
                            'bukti'        => null,
                            'koordinat'    => null,
                            'keterangan'   => null,
                            'created_at'   => null,
                        ]);
                    }
                }
            }

            $absensi = $mapped;
        }

        return response()->json([
            'data' => $absensi
        ]);
    }
}

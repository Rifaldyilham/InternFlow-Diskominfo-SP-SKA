<?php

namespace App\Http\Controllers\PesertaMagang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiPesertaController extends Controller
{
    public function index () 
    {
        $user = auth()->user();

        if (!$user->peserta) {
            return view('peserta.absensi', [
                'infoMessage' => 'Silahkan mengajukan magang terlebih dahulu.',
                'total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0
            ]);
        }

        if ($user->peserta->status_verifikasi !== 'terverifikasi') {
            return view('peserta.absensi', [
                'infoMessage' => 'Pengajuan sudah masuk. Silahkan tunggu verifikasi admin.',
                'total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0
            ]);
        }

        $pesertaId = auth()->user()->peserta->id_pesertamagang;

        $total = Absensi::where('id_pesertamagang', $pesertaId)->count();

        $hadir = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'hadir')
            ->count();

        $izin = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'izin')
            ->count();

        $sakit = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'sakit')
            ->count();

        $alpha = max(0, $total - ($hadir + $izin + $sakit));


        $alreadyAbsen = Absensi::where('id_pesertamagang', $pesertaId)
            ->whereDate('waktu_absen', now()->toDateString())
            ->exists();

        $infoMessage = null;
        if ($alreadyAbsen) {
            $infoMessage = 'Anda sudah absen hari ini.';
        }

        return view('peserta.absensi', compact(
            'total',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'infoMessage'
        ));

    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',

            'bukti_kegiatan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'alasan' => 'required_if:status,izin,sakit|string|min:10',

            'lokasi' => 'nullable|string',
        ]);

        $peserta = auth()->user()->peserta;

        if ($peserta->tanggal_mulai && now()->toDateString() < $peserta->tanggal_mulai) {
            return response()->json([
                'message' => 'Belum waktunya absen. Absensi dimulai tanggal ' . \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y')
            ], 422);
        }

        if ($peserta->tanggal_selesai && now()->toDateString() > $peserta->tanggal_selesai) {
            return response()->json([
                'message' => 'Masa magang sudah selesai. Anda tidak bisa absen lagi.'
            ], 422);
        }

        $path = $request->file('bukti_kegiatan')
            ->store('bukti-absensi', 'public');

        $pesertaId = auth()->user()->peserta->id_pesertamagang;

        $alreadyAbsen = Absensi::where('id_pesertamagang', $pesertaId)
            ->whereDate('waktu_absen', now()->toDateString())
            ->exists();

        if ($alreadyAbsen) {
            return response()->json(['message' => 'Anda sudah absen hari ini'], 409);
        }

        Absensi::create([
            'id_pesertamagang' => auth()->user()->peserta->id_pesertamagang,
            'waktu_absen'      => now(),
            'status'           => $request->status,
            'lokasi'           => $request->lokasi,
            'bukti_kegiatan'   => $path,
            'alasan'           => $request->alasan,
        ]);

        return response()->json(['message' => 'Absen berhasil']);
    }

}

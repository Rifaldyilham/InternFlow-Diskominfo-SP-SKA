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
            return redirect()
                ->route('peserta.pendaftaran')
                ->with('error', 'Silahkan daftar magang terlebih dahulu.');
        }

        if ($user->peserta->status_verifikasi !== 'terverifikasi') {
            return redirect()
                ->route('peserta.dashboard')
                ->with('error', 'Anda belum diverifikasi admin.');
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

        return view('peserta.absensi', compact(
            'total',
            'hadir',
            'izin',
            'sakit',
            'alpha'
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

        $path = $request->file('bukti_kegiatan')
            ->store('bukti-absensi', 'public');

        Absensi::create([
            'id_pesertamagang' => auth()->user()->peserta->id_pesertamagang,
            'id_pegawai'       => auth()->user()->peserta->id_pegawai,
            'waktu_absen'      => now(),
            'status'           => $request->status,
            'lokasi'           => $request->lokasi,
            'bukti_kegiatan'   => $path,
            'alasan'           => $request->alasan,
        ]);

        return response()->json(['message' => 'Absen berhasil']);
    }

}

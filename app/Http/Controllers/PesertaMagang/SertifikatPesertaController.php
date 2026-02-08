<?php

namespace App\Http\Controllers\PesertaMagang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\PesertaMagang;
use App\Models\Sertifikat;

class SertifikatPesertaController extends Controller
{
    // GET /api/peserta/sertifikat
    public function detail()
    {
        $peserta = $this->getPeserta();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $sertifikat = Sertifikat::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$sertifikat) {
            return response()->json([
                'data' => [
                    'tersedia' => false,
                ]
            ]);
        }

        $fileInfo = $this->buildFileInfo($sertifikat->file_sertifikat, $sertifikat->created_at);

        return response()->json([
            'data' => [
                'tersedia' => true,
                'id' => $sertifikat->id_sertifikat,
                'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                'tanggal_terbit' => $sertifikat->tanggal_terbit,
                'nama' => $fileInfo['nama'],
                'ukuran' => $fileInfo['ukuran'],
                'tanggal_upload' => $fileInfo['tanggal_upload'],
                'url' => $fileInfo['url'],
            ]
        ]);
    }

    // GET /api/peserta/sertifikat/download
    public function download()
    {
        $peserta = $this->getPeserta();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $sertifikat = Sertifikat::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$sertifikat) {
            return response()->json(['message' => 'Sertifikat belum tersedia'], 404);
        }

        if (!$sertifikat->file_sertifikat || !Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            return response()->json(['message' => 'File sertifikat tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($sertifikat->file_sertifikat);
    }

    private function getPeserta()
    {
        $user = Auth::user();
        if (!$user) return null;

        return PesertaMagang::where('id_user', $user->id_user)
            ->orderByDesc('created_at')
            ->first();
    }

    private function buildFileInfo($path, $createdAt)
    {
        if (!$path) return null;

        $exists = Storage::disk('public')->exists($path);
        $size = $exists ? Storage::disk('public')->size($path) : 0;
        $sizeMb = $size > 0 ? number_format($size / 1024 / 1024, 2) . ' MB' : '-';

        $baseName = basename($path);
        if (preg_match('/^sertifikat_\\d+_\\d+_(.+)$/', $baseName, $m)) {
            $baseName = $m[1];
        }

        return [
            'nama' => $baseName,
            'ukuran' => $sizeMb,
            'tanggal_upload' => $createdAt ? Carbon::parse($createdAt)->format('Y-m-d') : null,
            'url' => $exists ? Storage::url($path) : null,
        ];
    }
}

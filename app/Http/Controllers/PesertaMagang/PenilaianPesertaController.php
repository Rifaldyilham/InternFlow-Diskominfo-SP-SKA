<?php

namespace App\Http\Controllers\PesertaMagang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\PesertaMagang;
use App\Models\Penilaian;

class PenilaianPesertaController extends Controller
{
    // GET /api/peserta/penilaian
    public function detail()
    {
        $peserta = $this->getPeserta();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $penilaian = Penilaian::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$penilaian) {
            return response()->json([
                'data' => [
                    'tersedia' => false,
                ]
            ]);
        }

        $fileInfo = $this->buildFileInfo($penilaian->filePenilaian, $penilaian->created_at);

        return response()->json([
            'data' => [
                'tersedia' => true,
                'id' => $penilaian->id_penilaian,
                'nama' => $fileInfo['nama'],
                'ukuran' => $fileInfo['ukuran'],
                'tanggal_upload' => $fileInfo['tanggal_upload'],
                'url' => $fileInfo['url'],
            ]
        ]);
    }

    // GET /api/peserta/penilaian/download
    public function download()
    {
        $peserta = $this->getPeserta();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $penilaian = Penilaian::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$penilaian) {
            return response()->json(['message' => 'File penilaian belum tersedia'], 404);
        }

        if (!$penilaian->filePenilaian || !Storage::disk('public')->exists($penilaian->filePenilaian)) {
            return response()->json(['message' => 'File penilaian tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($penilaian->filePenilaian);
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
        // Hilangkan prefix penamaan internal agar nama tampil seperti asli
        if (preg_match('/^penilaian_\\d+_\\d+_(.+)$/', $baseName, $m)) {
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

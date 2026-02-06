<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\PesertaMagang;
use App\Models\Sertifikat;

class SertifikatController extends Controller
{
    // GET /api/admin/peserta/sertifikat
    public function peserta(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);

        $peserta = PesertaMagang::with('sertifikat')
            ->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $peserta->map(function ($item) {
            $sertifikat = $item->sertifikat;
            $fileInfo = $sertifikat ? $this->buildFileInfo($sertifikat->file_sertifikat, $sertifikat->created_at) : null;

            return [
                'id' => $item->id_pesertamagang,
                'nama' => $item->nama,
                'nim' => $item->nim,
                'universitas' => $item->asal_univ,
                'program_studi' => $item->program_studi,
                'status_magang' => $item->status,
                'tanggal_mulai' => $item->tanggal_mulai,
                'tanggal_selesai' => $item->tanggal_selesai,
                'sertifikat' => $fileInfo ? [
                    'id' => $sertifikat->id_sertifikat,
                    'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                    'tanggal_terbit' => $sertifikat->tanggal_terbit,
                    'nama_file' => $fileInfo['nama'],
                    'ukuran_file' => $fileInfo['ukuran'],
                    'url' => $fileInfo['url'],
                    'upload_date' => $fileInfo['tanggal_upload'],
                ] : null
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $peserta->currentPage(),
                'per_page' => $peserta->perPage(),
                'total' => $peserta->total(),
                'last_page' => $peserta->lastPage(),
            ],
        ]);
    }

    // POST /api/admin/sertifikat/upload
    public function upload(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertamagang,id_pesertamagang',
            'nomor_sertifikat' => 'required|string|max:100',
            'tanggal_terbit' => 'required|date',
            'file_sertifikat' => 'required|file|mimes:pdf|max:10240',
        ]);

        $peserta = PesertaMagang::where('id_pesertamagang', $request->peserta_id)->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $file = $request->file('file_sertifikat');
        $fileName = 'sertifikat_' . $peserta->id_pesertamagang . '_' . time() . '.pdf';
        $path = $file->storeAs('sertifikat', $fileName, 'public');

        $sertifikat = Sertifikat::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if ($sertifikat && $sertifikat->file_sertifikat) {
            Storage::disk('public')->delete($sertifikat->file_sertifikat);
        }

        $sertifikat = Sertifikat::updateOrCreate(
            ['id_pesertamagang' => $peserta->id_pesertamagang],
            [
                'nomor_sertifikat' => $request->nomor_sertifikat,
                'tanggal_terbit' => $request->tanggal_terbit,
                'file_sertifikat' => $path,
            ]
        );

        return response()->json([
            'message' => 'Sertifikat berhasil diupload',
            'data' => [
                'id' => $sertifikat->id_sertifikat,
                'peserta_id' => $peserta->id_pesertamagang,
                'file' => $this->buildFileInfo($sertifikat->file_sertifikat, $sertifikat->created_at),
            ],
        ]);
    }

    // GET /api/admin/sertifikat/{pesertaId}
    public function show($pesertaId)
    {
        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $sertifikat = Sertifikat::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$sertifikat) {
            return response()->json(['message' => 'Sertifikat belum tersedia'], 404);
        }

        $fileInfo = $this->buildFileInfo($sertifikat->file_sertifikat, $sertifikat->created_at);

        return response()->json([
            'data' => [
                'id' => $sertifikat->id_sertifikat,
                'peserta_id' => $peserta->id_pesertamagang,
                'nama_file' => $fileInfo['nama'],
                'ukuran_file' => $fileInfo['ukuran'],
                'tanggal_terbit' => $sertifikat->tanggal_terbit,
                'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                'url' => $fileInfo['url'],
                'created_at' => $sertifikat->created_at,
            ],
        ]);
    }

    // DELETE /api/admin/sertifikat/{pesertaId}
    public function destroy($pesertaId)
    {
        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)->first();
        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $sertifikat = Sertifikat::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$sertifikat) {
            return response()->json(['message' => 'Sertifikat tidak ditemukan'], 404);
        }

        if ($sertifikat->file_sertifikat) {
            Storage::disk('public')->delete($sertifikat->file_sertifikat);
        }

        $sertifikat->delete();

        return response()->json(['message' => 'Sertifikat berhasil dihapus']);
    }

    // GET /api/admin/sertifikat/download/{id}
    public function download($id)
    {
        $sertifikat = Sertifikat::where('id_sertifikat', $id)->first();
        if (!$sertifikat) {
            $sertifikat = Sertifikat::where('id_pesertamagang', $id)->first();
        }

        if (!$sertifikat) {
            return response()->json(['message' => 'Sertifikat tidak ditemukan'], 404);
        }

        if (!$sertifikat->file_sertifikat || !Storage::disk('public')->exists($sertifikat->file_sertifikat)) {
            return response()->json(['message' => 'File sertifikat tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($sertifikat->file_sertifikat);
    }

    private function buildFileInfo($path, $createdAt)
    {
        if (!$path) return null;

        $exists = Storage::disk('public')->exists($path);
        $size = $exists ? Storage::disk('public')->size($path) : 0;
        $sizeMb = $size > 0 ? number_format($size / 1024 / 1024, 2) . ' MB' : '-';

        return [
            'nama' => basename($path),
            'ukuran' => $sizeMb,
            'tanggal_upload' => $createdAt ? Carbon::parse($createdAt)->format('Y-m-d') : null,
            'url' => $exists ? Storage::url($path) : null,
        ];
    }
}

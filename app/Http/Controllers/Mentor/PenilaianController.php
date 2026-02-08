<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\PesertaMagang;
use App\Models\Penilaian;
use Illuminate\Support\Str;

class PenilaianController extends Controller
{
    public function stats()
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json(['data' => [
                'total' => 0,
                'sudah' => 0,
                'belum' => 0,
            ]]);
        }

        $pesertaSelesai = PesertaMagang::where('id_pegawai', $pegawai->id_pegawai)
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', Carbon::today()->toDateString())
            ->pluck('id_pesertamagang');

        $total = $pesertaSelesai->count();
        $sudah = Penilaian::whereIn('id_pesertamagang', $pesertaSelesai)->count();
        $belum = max(0, $total - $sudah);

        return response()->json(['data' => [
            'total' => $total,
            'sudah' => $sudah,
            'belum' => $belum,
        ]]);
    }

    public function peserta(Request $request)
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
            ]);
        }

        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);

        $peserta = PesertaMagang::with('penilaian')
            ->where('id_pegawai', $pegawai->id_pegawai)
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', Carbon::today()->toDateString())
            ->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $data = $peserta->map(function ($item) {
            $penilaian = $item->penilaian;
            $fileInfo = $penilaian ? $this->buildFileInfo($penilaian->filePenilaian, $penilaian->created_at) : null;

            return [
                'id' => $item->id_pesertamagang,
                'nama' => $item->nama,
                'nim' => $item->nim,
                'universitas' => $item->asal_univ,
                'program_studi' => $item->program_studi,
                'tanggal_mulai' => $item->tanggal_mulai,
                'tanggal_selesai' => $item->tanggal_selesai,
                'status_magang' => 'selesai',
                'status_penilaian' => $penilaian ? 'sudah' : 'belum',
                'file_penilaian' => $fileInfo ? [
                    'nama' => $fileInfo['nama'],
                    'ukuran' => $fileInfo['ukuran'],
                    'tanggal_upload' => $fileInfo['tanggal_upload'],
                    'url' => $fileInfo['url'],
                ] : null,
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

    public function upload(Request $request)
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json(['message' => 'Data mentor tidak ditemukan'], 404);
        }

        $request->validate([
            'peserta_id' => 'required|exists:pesertamagang,id_pesertamagang',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $peserta = PesertaMagang::where('id_pesertamagang', $request->peserta_id)
            ->where('id_pegawai', $pegawai->id_pegawai)
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        if (!$peserta->tanggal_selesai || Carbon::parse($peserta->tanggal_selesai)->gt(Carbon::today())) {
            return response()->json(['message' => 'Peserta belum menyelesaikan magang'], 400);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        // Pastikan hanya nama file tanpa path dan karakter berbahaya
        $originalName = Str::of($originalName)->afterLast('\\')->afterLast('/')->toString();
        $fileName = 'penilaian_' . $peserta->id_pesertamagang . '_' . time() . '_' . $originalName;
        $path = $file->storeAs('penilaian', $fileName, 'public');

        $penilaian = Penilaian::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if ($penilaian && $penilaian->filePenilaian && $penilaian->filePenilaian !== $path) {
            Storage::disk('public')->delete($penilaian->filePenilaian);
        }

        $penilaian = Penilaian::updateOrCreate(
            ['id_pesertamagang' => $peserta->id_pesertamagang],
            [
                'id_pegawai' => $pegawai->id_pegawai,
                'filePenilaian' => $path,
                'status' => 1,
            ]
        );

        return response()->json([
            'message' => 'File penilaian berhasil diupload',
            'data' => [
                'id' => $penilaian->id_penilaian,
                'peserta_id' => $peserta->id_pesertamagang,
                'file' => $this->buildFileInfo($penilaian->filePenilaian, $penilaian->created_at),
            ],
        ]);
    }

    public function show($pesertaId)
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json(['message' => 'Data mentor tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $pegawai->id_pegawai)
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $penilaian = Penilaian::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$penilaian) {
            return response()->json(['message' => 'File penilaian belum tersedia'], 404);
        }

        $fileInfo = $this->buildFileInfo($penilaian->filePenilaian, $penilaian->created_at);

        return response()->json([
            'data' => [
                'id' => $penilaian->id_penilaian,
                'peserta_id' => $peserta->id_pesertamagang,
                'nama_file' => $fileInfo['nama'],
                'ukuran_file' => $fileInfo['ukuran'],
                'tanggal_upload' => $fileInfo['tanggal_upload'],
                'url' => $fileInfo['url'],
            ],
        ]);
    }

    public function destroy($pesertaId)
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json(['message' => 'Data mentor tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $pesertaId)
            ->where('id_pegawai', $pegawai->id_pegawai)
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }

        $penilaian = Penilaian::where('id_pesertamagang', $peserta->id_pesertamagang)->first();
        if (!$penilaian) {
            return response()->json(['message' => 'File penilaian tidak ditemukan'], 404);
        }

        if ($penilaian->filePenilaian) {
            Storage::disk('public')->delete($penilaian->filePenilaian);
        }

        $penilaian->delete();

        return response()->json(['message' => 'File penilaian berhasil dihapus']);
    }

    public function download($id)
    {
        $pegawai = $this->getMentorPegawai();
        if (!$pegawai) {
            return response()->json(['message' => 'Data mentor tidak ditemukan'], 404);
        }

        $penilaian = Penilaian::where('id_penilaian', $id)->first();
        if (!$penilaian) {
            $penilaian = Penilaian::where('id_pesertamagang', $id)->first();
        }

        if (!$penilaian) {
            return response()->json(['message' => 'File penilaian tidak ditemukan'], 404);
        }

        $peserta = PesertaMagang::where('id_pesertamagang', $penilaian->id_pesertamagang)
            ->where('id_pegawai', $pegawai->id_pegawai)
            ->first();

        if (!$peserta) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        if (!$penilaian->filePenilaian || !Storage::disk('public')->exists($penilaian->filePenilaian)) {
            return response()->json(['message' => 'File penilaian tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($penilaian->filePenilaian);
    }

    private function getMentorPegawai()
    {
        $user = Auth::user();
        if (!$user) return null;
        return Pegawai::where('id_user', $user->id_user)->first();
    }

    private function buildFileInfo($path, $createdAt)
    {
        if (!$path) return null;

        $exists = Storage::disk('public')->exists($path);
        $size = $exists ? Storage::disk('public')->size($path) : 0;
        $sizeMb = $size > 0 ? number_format($size / 1024 / 1024, 2) . ' MB' : '-';

        $baseName = basename($path);
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

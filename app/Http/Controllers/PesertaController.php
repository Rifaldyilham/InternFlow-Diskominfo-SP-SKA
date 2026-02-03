<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePesertaRequest;
use Illuminate\Http\Request;
use App\Models\PesertaMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Bidang;

class PesertaController extends Controller
{
    public function store(StorePesertaRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $data = $request->only([
            'nama',
            'email',
            'nim',
            'no_telp',
        ]);

        $data['id_user'] = $user->id_user;
        $data['asal_univ'] = $request->universitas;
        $data['program_studi'] = $request->jurusan;
        $data['tanggal_mulai'] = $request->tanggal_mulai;
        $data['tanggal_selesai'] = $request->tanggal_selesai;
        $data['alasan'] = $request->alasan;
        $data['bidang_pilihan'] = $request->bidang_pilihan;
        $data['id_bidang'] = null;

        // ✅ PERBAIKAN: Validasi dan simpan file dengan benar
        try {
            // Validasi dan upload surat
            if ($request->hasFile('surat_file')) {
                $file = $request->file('surat_file');
                
                // Validasi bahwa file benar-benar PDF
                if ($file->getClientMimeType() !== 'application/pdf') {
                    return redirect()->back()->withErrors([
                        'surat_file' => 'File harus berupa PDF'
                    ])->withInput();
                }
                
                // Validasi size (max 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->withErrors([
                        'surat_file' => 'Ukuran file maksimal 5MB'
                    ])->withInput();
                }
                
                // Generate nama file yang unik
                $fileName = 'surat_' . time() . '_' . str_replace(' ', '_', $request->nama) . '.pdf';
                $path = $file->storeAs('peserta/surat_penempatan', $fileName, 'public');
                
                // Verifikasi file tersimpan
                if (!Storage::disk('public')->exists($path)) {
                    throw new \Exception('Gagal menyimpan file surat');
                }
                
                $data['surat_penempatan_path'] = $path;
            }

            // Validasi dan upload CV
            if ($request->hasFile('cv_file')) {
                $file = $request->file('cv_file');
                
                // Validasi bahwa file benar-benar PDF
                if ($file->getClientMimeType() !== 'application/pdf') {
                    return redirect()->back()->withErrors([
                        'cv_file' => 'File harus berupa PDF'
                    ])->withInput();
                }
                
                // Validasi size (max 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()->withErrors([
                        'cv_file' => 'Ukuran file maksimal 5MB'
                    ])->withInput();
                }
                
                // Generate nama file yang unik
                $fileName = 'cv_' . time() . '_' . str_replace(' ', '_', $request->nama) . '.pdf';
                $path = $file->storeAs('peserta/cv', $fileName, 'public');
                
                // Verifikasi file tersimpan
                if (!Storage::disk('public')->exists($path)) {
                    throw new \Exception('Gagal menyimpan file CV');
                }
                
                $data['cv_path'] = $path;
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'file_error' => 'Gagal mengupload file: ' . $e->getMessage()
            ])->withInput();
        }

        // Set default id_pegawai to null (not yet assigned)
        $data['id_pegawai'] = $request->input('id_pegawai', null);

        $data['status'] = 'aktif';
        $data['status_verifikasi'] = 'pending';

        $peserta = PesertaMagang::create($data);

        return redirect()->back()->with('success', 'Pendaftaran peserta berhasil.');
    }

    public function create()
    {
        $bidang = Bidang::where('status', 'aktif')->get();

        return view('peserta.pendaftaran', compact('bidang'));
    }

}

class PesertaMagangController extends Controller
{
    public function index()
    {
        $peserta = PesertaMagang::latest()->get();
        return view('admin.peserta.index', compact('peserta'));
    }

    public function show($id)
    {
        $peserta = PesertaMagang::findOrFail($id);
        return view('admin.peserta.show', compact('peserta'));
    }

    public function approve($id)
    {
        $peserta = PesertaMagang::findOrFail($id);
        $peserta->update([
            'status_verifikasi' => 'terverifikasi',
            'status' => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Peserta berhasil diterima');
    }

    public function reject(Request $request, $id)
    {
        $peserta = PesertaMagang::findOrFail($id);
        $peserta->update([
            'status_verifikasi' => 'ditolak',
            'status' => 'nonaktif',
            'catatan_verifikasi' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Peserta berhasil ditolak');
    }

}

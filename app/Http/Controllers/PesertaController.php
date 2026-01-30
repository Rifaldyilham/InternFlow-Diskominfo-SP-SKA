<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePesertaRequest;
use Illuminate\Http\Request;
use App\Models\PesertaMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // If user has associated pegawai or bidang, you may want to set id_bidang accordingly
        if ($user->pegawai && $user->pegawai->id_bidang) {
            $data['id_bidang'] = $user->pegawai->id_bidang;
        } else {
            // Fallback: leave null or set default; PesertaMagang migration requires id_bidang, so set 0 if missing
            // $data['id_bidang'] = $request->input('id_bidang', 0);
            $data['id_bidang'] = null;
        }

        // Handle file uploads
        if ($request->hasFile('surat_file')) {
            $path = $request->file('surat_file')
                ->store('peserta/surat_penempatan', 'public');
            $data['surat_penempatan_path'] = $path;
        }

        if ($request->hasFile('cv_file')) {
            $path = $request->file('cv_file')
                ->store('peserta/cv', 'public');
            $data['cv_path'] = $path;
        }

        // Set default id_pegawai to null (not yet assigned)
        $data['id_pegawai'] = $request->input('id_pegawai', null);

        $data['status'] = 'aktif';
        $data['status_verifikasi'] = 'pending';

        // dd($data);

        $peserta = PesertaMagang::create($data);

        return redirect()->back()->with('success', 'Pendaftaran peserta berhasil.');
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

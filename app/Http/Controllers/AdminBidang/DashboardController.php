<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaMagang;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        // Validasi: pegawai harus ada dan harus punya id_bidang
        if (!$pegawai || !$pegawai->id_bidang) {
            return redirect('/login')->with('error', 'Akun Anda belum ditugaskan ke bidang. Hubungi admin.');
        }

        $idBidang = $pegawai->id_bidang;

        $peserta = PesertaMagang::where('id_bidang', $idBidang)
            ->with('pegawai')
            ->get();

        $mentor = Pegawai::where('id_bidang', $idBidang)
            ->whereHas('user', function ($a) {
                $a->where('id_role', 3);
            })
            ->get();

        return view('admin-bidang.mentor', compact('peserta', 'mentor'));
    }

    public function penempatan()
    {
        $pegawai = Auth::user()->pegawai;

        // Validasi: pegawai harus ada dan harus punya id_bidang
        if (!$pegawai || !$pegawai->id_bidang) {
            return redirect('/login')->with('error', 'Akun Anda belum ditugaskan ke bidang. Hubungi admin.');
        }

        $idBidang = $pegawai->id_bidang;

        $peserta = PesertaMagang::where('id_bidang', $idBidang)
            ->with('pegawai')
            ->get();

        $mentor = Pegawai::where('id_bidang', $idBidang)
            ->whereHas('user', function ($a) {
                $a->where('id_role', 3);
            })
            ->get();

        return view('admin-bidang.penempatan', compact('peserta', 'mentor'));
    }

    public function assignMentor(Request $request)
    {
        $request->validate([
            'id_pesertamagang' => 'required',
            'id_pegawai' => 'required',
        ]);

        PesertaMagang::where('id_pesertamagang', $request->id_pesertamagang)
            ->update([
                'id_pegawai' => $request->id_pegawai
            ]);

        return back()->with('Success', 'Mentor berhasil ditentukan');
    }
}

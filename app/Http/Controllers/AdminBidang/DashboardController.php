<?php

namespace App\Http\Controllers\AdminBidang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesertamagang;
use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index()
    {
        $idBidang = auth()->user()->pegawai->id_bidang;

        $peserta = Pesertamagang::where('id_bidang', $idBidang)
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

        Pesertamagang::where('id_pesertamagang', $request->id_pesertamagang)
            ->update([
                'id_pegawai' => $request->id_pegawai
            ]);

        return back()->with('Success', 'Mentor berhasil ditentukan');
    }


}

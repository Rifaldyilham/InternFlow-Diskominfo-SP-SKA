<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\PesertaMagang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;



class ManajemenBidangController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
        {
            $bidang = Bidang::withCount([
                 'pesertaMagang as terisi'
        ])->get();

        return view('admin.manajemenbidang', compact('bidang'));
    }

    public function pesertaMenunggu($idBidang): JsonResponse
    {
        $bidang = Bidang::findOrFail($idBidang);

        $peserta = PesertaMagang::where('status_verifikasi', 'terverifikasi')
            ->whereNull('id_bidang')
            ->where('bidang_pilihan', $bidang->nama_bidang) // atau nama_bidang
            ->get();

        return response()->json([
            'bidang' => $bidang,
            'peserta' => $peserta
        ]);
    }
    public function tempatkan(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required',
            'bidang_id' => 'required'
        ]);

        $bidang = Bidang::findOrFail($request->bidang_id);

        $terisi = PesertaMagang::where('id_bidang', $bidang->id)->count();
        

        if ($terisi >= $bidang->kuota) {
            return response()->json([
                'message' => 'Kuota bidang penuh'
            ], 422);
        }

        PesertaMagang::where('id_pesertamagang', $request->peserta_id)
            ->update([
                'id_bidang' => $bidang->id
            ]);

        return response()->json(['message' => 'Peserta berhasil ditempatkan']);
    }

    }
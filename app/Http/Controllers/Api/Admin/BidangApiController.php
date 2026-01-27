<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangApiController extends Controller
{
    // GET: ambil semua bidang
    public function index()
    {
         $bidang = Bidang::with('admin')
        ->withCount([
            'peserta as peserta_aktif' => function ($q) {
                $q->where('status', 'aktif');
            }
        ])->get();

        $bidang->each(function ($b) {
            if ($b->kuota > 0 && $b->peserta_aktif >= $b->kuota) {
                $b->status = 'penuh';
            }
        });

        return $bidang;
    }

    // POST: tambah bidang
    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:100',
            'deskripsi'   => 'nullable|string',
            'kuota'       => 'required|integer|min:1',
            'status'      => 'required|in:aktif,nonaktif,penuh',
            'id_admin'    => 'nullable|exists:users,id_user',
        ]);

        Bidang::create($request->only([
            'nama_bidang', 'deskripsi', 'kuota', 'status', 'id_admin'
        ]));

        return response()->json(['message' => 'Bidang berhasil ditambahkan']);
    }

    // PUT: update bidang
    public function update(Request $request, $id)
    {
        $bidang = Bidang::findOrFail($id);

        $bidang->update($request->only([
            'nama_bidang', 'deskripsi', 'kuota', 'status', 'id_admin'
        ]));

        return response()->json(['message' => 'Bidang berhasil diperbarui']);
    }

    // DELETE
    public function destroy($id)
    {
        Bidang::findOrFail($id)->delete();

        return response()->json(['message' => 'Bidang dihapus']);
    }

    public function show($id)
    {
        $bidang = Bidang::withCount([
            'peserta as peserta_aktif' => function ($q) {
                $q->where('status', 'aktif');
            }
        ])->with('admin')->findOrFail($id);

        if ($bidang->kuota > 0 && $bidang->peserta_aktif >= $bidang->kuota) {
            $bidang->status = 'penuh';
        }

        return $bidang;
    }

}

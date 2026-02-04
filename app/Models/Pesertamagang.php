<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesertaMagang extends Model
{
    protected $table = 'pesertamagang';
    protected $primaryKey = 'id_pesertamagang';

    protected $fillable = [
        'id_user',
        'id_bidang',
        'id_pegawai',
        'email',
        'nama',
        'nim',
        'asal_univ',
        'program_studi',
        'no_telp',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'bidang_pilihan',
        'surat_penempatan_path',
        'cv_path',
        'status',
        'status_verifikasi',
        'catatan_verifikasi'
    ];

    // Eager loading untuk relasi
    protected $with = ['bidang', 'pegawai', 'bidangPilihan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function bidangPilihan()
    {
        return $this->belongsTo(Bidang::class, 'bidang_pilihan', 'id_bidang');
    }

    public function bidangPenempatan()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    // Relasi untuk peserta yang dibimbing oleh mentor
    public function pesertamagang()
    {
        return $this->hasMany(PesertaMagang::class, 'id_pegawai', 'id_pegawai');
    }

    // Helper method untuk mendapatkan data mentor
    public function getMentorInfo()
    {
        if (!$this->pegawai) {
            return null;
        }

        return [
            'nama' => $this->pegawai->nama,
            'nip' => $this->pegawai->nip,
            'bidang' => $this->pegawai->bidang ? $this->pegawai->bidang->nama_bidang : 'Tidak ada'
        ];
    }

    // Helper method untuk mendapatkan progress
    public function getProgressInfo()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return [
                'progress' => 0,
                'hari_berjalan' => 0,
                'hari_total' => 0,
                'hari_tersisa' => 0
            ];
        }

        $start = \Carbon\Carbon::parse($this->tanggal_mulai);
        $end = \Carbon\Carbon::parse($this->tanggal_selesai);
        $today = \Carbon\Carbon::now();

        if ($today->lt($start)) {
            return [
                'progress' => 0,
                'hari_berjalan' => 0,
                'hari_total' => $start->diffInDays($end),
                'hari_tersisa' => $start->diffInDays($end)
            ];
        }

        if ($today->gt($end)) {
            return [
                'progress' => 100,
                'hari_berjalan' => $start->diffInDays($end),
                'hari_total' => $start->diffInDays($end),
                'hari_tersisa' => 0
            ];
        }

        $totalDays = $start->diffInDays($end);
        $daysPassed = $start->diffInDays($today);
        $progress = ($daysPassed / $totalDays) * 100;
        $remainingDays = $today->diffInDays($end);

        return [
            'progress' => min(100, max(0, $progress)),
            'hari_berjalan' => $daysPassed,
            'hari_total' => $totalDays,
            'hari_tersisa' => $remainingDays
        ];
    }

}

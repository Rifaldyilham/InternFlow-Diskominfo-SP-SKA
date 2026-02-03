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

}

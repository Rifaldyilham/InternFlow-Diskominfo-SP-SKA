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
        'status'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }
}
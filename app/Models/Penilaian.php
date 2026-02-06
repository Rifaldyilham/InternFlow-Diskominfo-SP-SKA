<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';

    protected $fillable = [
        'id_pesertamagang',
        'id_pegawai',
        'filePenilaian',
        'status',
    ];

    public function peserta()
    {
        return $this->belongsTo(PesertaMagang::class, 'id_pesertamagang', 'id_pesertamagang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}

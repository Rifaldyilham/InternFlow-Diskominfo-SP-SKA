<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_pesertamagang',
        'waktu_absen',
        'status',
        'lokasi',
        'bukti_kegiatan',
        'alasan'
    ];

    public function peserta()
    {
        return $this->belongsTo(Pesertamagang::class, 'id_pesertamagang', 'id_pesertamagang');
    }
}

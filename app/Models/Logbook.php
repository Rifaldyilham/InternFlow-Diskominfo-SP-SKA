<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'id_logbook';

    protected $fillable = [
        'id_pesertamagang',
        'nama_kegiatan',
        'tanggal',
        'deskripsi',
        'bukti_kegiatan',
        'status',
        'catatan_mentor'
    ];

    public function peserta()
    {
        return $this->belongsTo(Pesertamagang::class, 'id_pesertamagang', 'id_pesertamagang');
    }
}

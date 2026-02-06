<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    protected $primaryKey = 'id_sertifikat';

    protected $fillable = [
        'id_pesertamagang',
        'nomor_sertifikat',
        'tanggal_terbit',
        'file_sertifikat'
    ];

    public function peserta()
    {
        return $this->belongsTo(PesertaMagang::class, 'id_pesertamagang', 'id_pesertamagang');
    }
}

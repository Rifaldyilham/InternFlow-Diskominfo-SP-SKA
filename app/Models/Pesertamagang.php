<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pesertamagang extends Model
{
    protected $table = 'pesertamagang';
    protected $primaryKey = 'id_pesertamagang';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }
}
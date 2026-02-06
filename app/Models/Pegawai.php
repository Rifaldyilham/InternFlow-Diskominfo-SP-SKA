<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_user',
        'id_bidang',
        'nama',
        'nip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'id_bidang', 'id_bidang');
    }

    public function pesertamagang()
    {
        return $this->hasMany(PesertaMagang::class, 'id_pegawai', 'id_pegawai');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_pegawai', 'id_pegawai');
    }
}

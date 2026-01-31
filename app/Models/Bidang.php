<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $table = 'bidang';
    protected $primaryKey = 'id_bidang';

    protected $fillable = [
        'nama_bidang',
        'deskripsi',
        'kuota',
        'status',
        'id_admin'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_user');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaMagang::class, 'id_bidang', 'id_bidang');
    }
        // app/Models/Bidang.php
    public function pesertaMagang()
    {
        return $this->hasMany(PesertaMagang::class, 'id_bidang', 'id_bidang');
    }
}

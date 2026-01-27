<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_roles';
    public $timestamps = false;

    protected $fillable = [
        'nama_role'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_roles');
    }
}

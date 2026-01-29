<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class UserInternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'name' => 'Admin Kepegawaian',
            'email' => 'kepegawaian@diskominfo.ac.id',
            'password' => Hash::make('AdminKepegawaian100'),
            'id_role' => 1,
            'status_aktif' => 1,
        ]);
    }
}

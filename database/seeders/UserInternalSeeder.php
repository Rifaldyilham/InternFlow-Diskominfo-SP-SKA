<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
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
        // Admin Kepegawaian
        $adminKepegawaian = User::create([
            'name' => 'Admin Kepegawaian',
            'email' => 'kepegawaian@diskominfo.ac.id',
            'password' => Hash::make('AdminKepegawaian100'),
            'id_role' => 1,
            'status_aktif' => 1,
        ]);

        // Admin Bidang - Statistika
        $adminBidang = User::create([
            'name' => 'Admin Bidang Statistika',
            'email' => 'bidangstatistika@diskominfo.ac.id',
            'password' => Hash::make('AdminBidang100'),
            'id_role' => 2,
            'status_aktif' => 1,
        ]);

        // Buat pegawai untuk Admin Bidang (Bidang Statistika = ID 2)
        Pegawai::create([
            'id_user' => $adminBidang->id_user,
            'id_bidang' => 2, // Bidang Statistika
            'nama' => 'Admin Bidang Statistika',
            'nip' => '1234567890001',
        ]);

        // Mentor 1
        $mentor1 = User::create([
            'name' => 'Andi Wijaya',
            'email' => 'andi.wijaya@diskominfo.ac.id',
            'password' => Hash::make('MentorPassword100'),
            'id_role' => 3,
            'status_aktif' => 1,
        ]);

        Pegawai::create([
            'id_user' => $mentor1->id_user,
            'id_bidang' => 2, // Bidang Statistika
            'nama' => 'Andi Wijaya',
            'nip' => '1234567890002',
        ]);

        // Mentor 2
        $mentor2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@diskominfo.ac.id',
            'password' => Hash::make('MentorPassword100'),
            'id_role' => 3,
            'status_aktif' => 1,
        ]);

        Pegawai::create([
            'id_user' => $mentor2->id_user,
            'id_bidang' => 2, // Bidang Statistika
            'nama' => 'Budi Santoso',
            'nip' => '1234567890003',
        ]);

        // Mentor 3
        $mentor3 = User::create([
            'name' => 'Citra Dewi',
            'email' => 'citra.dewi@diskominfo.ac.id',
            'password' => Hash::make('MentorPassword100'),
            'id_role' => 3,
            'status_aktif' => 1,
        ]);

        Pegawai::create([
            'id_user' => $mentor3->id_user,
            'id_bidang' => 2, // Bidang Statistika
            'nama' => 'Citra Dewi',
            'nip' => '1234567890004',
        ]);
    }
}

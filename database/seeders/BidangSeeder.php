<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Bidang::insert([
        [
            'nama_bidang' => 'Teknologi dan Informatika',
            'deskripsi' => null,
            'kuota' => 10,
            'status' => 'aktif',
            'id_admin' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_bidang' => 'Statistika',
            'deskripsi' => null,
            'kuota' => 10,
            'status' => 'aktif',
            'id_admin' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_bidang' => 'Komunikasi Publik dan Media',
            'deskripsi' => null,
            'kuota' => 10,
            'status' => 'aktif',
            'id_admin' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nama_bidang' => 'Sekretariat',
            'deskripsi' => null,
            'kuota' => 10,
            'status' => 'aktif',
            'id_admin' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    }
}

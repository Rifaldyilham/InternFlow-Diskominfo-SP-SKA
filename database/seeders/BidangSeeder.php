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
            ['nama_bidang'=> 'Teknologi dan Informatika'],
            ['nama_bidang'=> 'Statistika'],
            ['nama_bidang'=> 'Komunikasi Publik dan Media'],
            ['nama_bidang'=> 'Sekretariat'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nama_role'=>'Admin Kepegawaian'],
            ['nama_role'=>'Admin Bidang'],
            ['nama_role'=>'Mentor'],
            ['nama_role'=>'Peserta Magang']
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            // periode magang
            $table->date('tanggal_mulai')->nullable()->after('no_telp');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');

            // alasan memilih bidang
            $table->text('alasan')->nullable()->after('tanggal_selesai');

            // pilihan bidang peserta (BELUM penempatan)
            $table->unsignedBigInteger('bidang_pilihan')->nullable()->after('alasan');

            // foreign key ke tabel bidang
            $table->foreign('bidang_pilihan')
                  ->references('id_bidang')
                  ->on('bidang')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            table->dropForeign(['bidang_pilihan']);

            $table->dropColumn([
                'tanggal_mulai',
                'tanggal_selesai',
                'alasan',
                'bidang_pilihan'
            ]);
        });
    }
};

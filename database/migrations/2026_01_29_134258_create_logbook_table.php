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
        Schema::create('logbook', function (Blueprint $table) {
            $table->id('id_logbook');
            $table->unsignedBigInteger('id_pesertamagang');
            $table->unsignedBigInteger('id_pegawai');
            $table->string('nama_kegiatan',100);
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->string('bukti_kegiatan');
            $table->enum('status',['belum diverifikasi','diverifikasi','ditolak']);
            $table->timestamps();

            $table->foreign('id_pesertamagang')
                ->references('id_pesertamagang')
                ->on('pesertamagang')
                ->onDelete('cascade');

            $table->foreign('id_pegawai')
                ->references('id_pegawai')
                ->on('pegawai')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
};

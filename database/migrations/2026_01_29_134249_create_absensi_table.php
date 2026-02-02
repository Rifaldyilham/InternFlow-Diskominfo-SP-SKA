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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_pesertamagang');
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->datetime('waktu_absen')->useCurrent;
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha']);
            $table->string('lokasi')->nullable();
            $table->string('bukti_kegiatan')->nullable();
            $table->text('alasan')->nullable();
            $table->timestamps();

            $table->foreign('id_pesertamagang')
                ->references('id_pesertamagang')
                ->on('pesertamagang')
                ->onDelete('cascade');

            $table->foreign('id_pegawai')
                ->references('id_pegawai')
                ->on('pegawai')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};

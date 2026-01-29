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
            $table->datetime('tanggal')->useCurrent();
            $table->enum('status hadir', ['hadir', 'izin', 'sakit', 'alpha']);
            $table->string('bukti_kegiatan');
            $table->timestamps();

            $table->foreign('id_pesertamagang')
                ->references('id_pesertamagang')
                ->on('pesertamagang')
                ->onDelete('cascade');
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

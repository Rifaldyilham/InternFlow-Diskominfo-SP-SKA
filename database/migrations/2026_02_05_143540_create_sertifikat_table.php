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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id('id_sertifikat');
            $table->unsignedBigInteger('id_pesertamagang');
            $table->string('nomor_sertifikat', 100);
            $table->datetime('tanggal_terbit')->useCurrent;
            $table->string('file_sertifikat');
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
        Schema::dropIfExists('sertifikat');
    }
};

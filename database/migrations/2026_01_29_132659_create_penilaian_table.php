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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id('id_penilaian');
            $table->unsignedBigInteger('id_pesertamagang');
            $table->unsignedBigInteger('id_pegawai');
            $table->string('filePenilaian');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('penilaian');
    }
};

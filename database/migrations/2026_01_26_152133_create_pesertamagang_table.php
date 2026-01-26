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
        Schema::create('pesertamagang', function (Blueprint $table) {
               $table->id('id_pesertamagang');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_bidang');
            $table->unsignedBigInteger('id_pegawai');
            $table->string('email', 100);
            $table->string('nama', 100);
            $table->string('nim', 20);
            $table->string('asal_univ', 100);
            $table->string('program_studi', 100);
            $table->string('no_telp', 15);
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_bidang')
                ->references('id_bidang')
                ->on('bidang')
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
        Schema::dropIfExists('pesertamagang');
    }
};

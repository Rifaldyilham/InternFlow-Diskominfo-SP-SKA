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
        Schema::create('pegawai', function (Blueprint $table) {
             $table->id('id_pegawai');
             $table->unsignedBigInteger('id_user');
             $table->unsignedBigInteger('id_bidang');
             $table->string('nama', 100);
             $table->string('nip', 20);
             $table->timestamps();


            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_bidang')
                ->references('id_bidang')
                ->on('bidang')
                ->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};

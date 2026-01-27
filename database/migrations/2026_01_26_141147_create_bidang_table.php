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
        Schema::create('bidang', function (Blueprint $table) {
            $table->id('id_bidang');
            $table->string('nama_bidang', 100);
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->default(0);
            $table->enum('status', ['aktif','nonaktif','penuh'])->default('aktif');

            $table->unsignedBigInteger('id_admin')->nullable(); // admin bidang
            $table->timestamps();

            $table->foreign('id_admin')
                ->references('id_user')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang');
    }
};

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
            // Drop foreign key, make column nullable, then re-add foreign key
            if (Schema::hasColumn('pesertamagang', 'id_pegawai')) {
                $table->dropForeign(['id_pegawai']);
                $table->unsignedBigInteger('id_pegawai')->nullable()->change();
                $table->foreign('id_pegawai')
                    ->references('id_pegawai')
                    ->on('pegawai')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            if (Schema::hasColumn('pesertamagang', 'id_pegawai')) {
                $table->dropForeign(['id_pegawai']);
                $table->unsignedBigInteger('id_pegawai')->nullable(false)->change();
                $table->foreign('id_pegawai')
                    ->references('id_pegawai')
                    ->on('pegawai')
                    ->onDelete('cascade');
            }
        });
    }
};

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
            if (!Schema::hasColumn('pesertamagang', 'status_verifikasi')) {
                $table->enum('status_verifikasi', ['pending', 'terverifikasi', 'ditolak'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('pesertamagang', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            if (Schema::hasColumn('pesertamagang', 'catatan_verifikasi')) {
                $table->dropColumn('catatan_verifikasi');
            }
            if (Schema::hasColumn('pesertamagang', 'status_verifikasi')) {
                $table->dropColumn('status_verifikasi');
            }
        });
    }
};

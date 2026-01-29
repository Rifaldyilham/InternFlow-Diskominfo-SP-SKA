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
            // Drop old columns if they exist
            if (Schema::hasColumn('pesertamagang', 'ktp_path')) {
                $table->dropColumn('ktp_path');
            }
            if (Schema::hasColumn('pesertamagang', 'ijazah_path')) {
                $table->dropColumn('ijazah_path');
            }

            // Add new columns
            if (!Schema::hasColumn('pesertamagang', 'surat_penempatan_path')) {
                $table->string('surat_penempatan_path')->nullable();
            }
            if (!Schema::hasColumn('pesertamagang', 'cv_path')) {
                $table->string('cv_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            if (Schema::hasColumn('pesertamagang', 'surat_penempatan_path')) {
                $table->dropColumn('surat_penempatan_path');
            }
            if (Schema::hasColumn('pesertamagang', 'cv_path')) {
                $table->dropColumn('cv_path');
            }

            $table->string('ktp_path')->nullable();
            $table->string('ijazah_path')->nullable();
        });
    }
};

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
            if (!Schema::hasColumn('pesertamagang', 'ktp_path')) {
                $table->string('ktp_path')->nullable()->after('no_telp');
            }
            if (!Schema::hasColumn('pesertamagang', 'ijazah_path')) {
                $table->string('ijazah_path')->nullable()->after('ktp_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertamagang', function (Blueprint $table) {
            if (Schema::hasColumn('pesertamagang', 'ijazah_path')) {
                $table->dropColumn('ijazah_path');
            }
            if (Schema::hasColumn('pesertamagang', 'ktp_path')) {
                $table->dropColumn('ktp_path');
            }
        });
    }
};

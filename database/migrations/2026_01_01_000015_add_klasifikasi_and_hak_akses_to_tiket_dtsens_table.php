<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->string('file_klasifikasi_dtsen')->nullable();
            $table->string('nama_file_klasifikasi_dtsen')->nullable();
            $table->string('file_hak_akses')->nullable();
            $table->string('nama_file_hak_akses')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->dropColumn([
                'file_klasifikasi_dtsen',
                'nama_file_klasifikasi_dtsen',
                'file_hak_akses',
                'nama_file_hak_akses',
            ]);
        });
    }
};

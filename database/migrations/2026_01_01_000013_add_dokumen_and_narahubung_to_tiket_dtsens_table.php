<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            // 5 Berkas Dokumen Permintaan OPD
            $table->string('file_kak')->nullable();
            $table->string('nama_file_kak')->nullable();
            
            $table->string('file_surat_permohonan')->nullable();
            $table->string('nama_file_surat_permohonan')->nullable();
            
            $table->string('file_ropa')->nullable();
            $table->string('nama_file_ropa')->nullable();
            
            $table->string('file_dpia')->nullable();
            $table->string('nama_file_dpia')->nullable();
            
            $table->string('file_sktm')->nullable();
            $table->string('nama_file_sktm')->nullable();

            // Kontak Narahubung Permohonan
            $table->string('nama_narahubung', 150)->nullable();
            $table->string('no_narahubung', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->dropColumn([
                'file_kak',
                'nama_file_kak',
                'file_surat_permohonan',
                'nama_file_surat_permohonan',
                'file_ropa',
                'nama_file_ropa',
                'file_dpia',
                'nama_file_dpia',
                'file_sktm',
                'nama_file_sktm',
                'nama_narahubung',
                'no_narahubung',
            ]);
        });
    }
};


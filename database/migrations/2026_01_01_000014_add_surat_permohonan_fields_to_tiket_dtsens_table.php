<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->string('no_surat_permohonan', 150)->nullable();
            $table->date('tgl_surat_permohonan')->nullable();
            $table->string('perihal', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->dropColumn([
                'no_surat_permohonan',
                'tgl_surat_permohonan',
                'perihal',
            ]);
        });
    }
};

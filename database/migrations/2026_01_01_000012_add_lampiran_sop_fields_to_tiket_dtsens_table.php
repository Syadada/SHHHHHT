<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            // Header & Penomoran Naskah SOP
            $table->string('nomor_naskah', 150)->nullable()->default('555/07-SOP/DISKOMINFO SP/2026');
            $table->string('no_agenda_disposisi', 100)->nullable();
            $table->date('tgl_permintaan')->nullable();
            $table->date('tgl_diterima')->nullable();

            // Penugasan
            $table->string('no_surat_tugas', 150)->nullable();
            $table->date('tgl_surat_tugas')->nullable();

            // Deskripsi Pekerjaan & Pelaksana
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->string('penanggung_jawab', 150)->nullable()->default('Chobir Sabbaha, S.Kom., M.T.');
            $table->string('tempat_pelaksanaan', 200)->nullable()->default('Gedung Diskominfo SP Provinsi Banten (KP3B Serang)');
            $table->string('pelaksana_1', 150)->nullable()->default('Tia Fitria Saumi, S.Kom.');
            $table->string('pelaksana_2', 150)->nullable()->default('Ahmad Fauzi, S.T.');
            $table->string('pelaksana_3', 150)->nullable();
            $table->string('pelaksana_4', 150)->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->string('sop_rujukan', 150)->nullable()->default('555/07-SOP/DISKOMINFO SP/2026');

            // Hasil Pekerjaan & Matriks Tambahan
            $table->text('hasil_pekerjaan')->nullable();
            $table->json('data_sop_extra')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_naskah',
                'no_agenda_disposisi',
                'tgl_permintaan',
                'tgl_diterima',
                'no_surat_tugas',
                'tgl_surat_tugas',
                'deskripsi_pekerjaan',
                'penanggung_jawab',
                'tempat_pelaksanaan',
                'pelaksana_1',
                'pelaksana_2',
                'pelaksana_3',
                'pelaksana_4',
                'tgl_mulai',
                'tgl_selesai',
                'sop_rujukan',
                'hasil_pekerjaan',
                'data_sop_extra',
            ]);
        });
    }
};


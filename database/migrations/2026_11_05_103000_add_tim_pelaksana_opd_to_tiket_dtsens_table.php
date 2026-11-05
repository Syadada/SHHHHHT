<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            // Detail NIP & Jabatan Tim Walidata
            $table->string('nip_penanggung_jawab', 100)->nullable()->default('19780218 200501 1 009');
            $table->string('nip_pelaksana_1', 100)->nullable()->default('19930812 202012 2 008');
            $table->string('jabatan_pelaksana_1', 150)->nullable()->default('Pranata Komputer Ahli Muda');
            $table->string('nip_pelaksana_2', 100)->nullable()->default('19890514 201503 1 004');
            $table->string('jabatan_pelaksana_2', 150)->nullable()->default('Statistisi Ahli Pertama');
            $table->string('nip_pelaksana_3', 100)->nullable();
            $table->string('jabatan_pelaksana_3', 150)->nullable();
            $table->string('nip_pelaksana_4', 100)->nullable();
            $table->string('jabatan_pelaksana_4', 150)->nullable();

            // Tim Pelaksana / Perwakilan OPD yang Hadir
            $table->string('pelaksana_opd_1', 150)->nullable();
            $table->string('nip_pelaksana_opd_1', 100)->nullable();
            $table->string('jabatan_pelaksana_opd_1', 150)->nullable();

            $table->string('pelaksana_opd_2', 150)->nullable();
            $table->string('nip_pelaksana_opd_2', 100)->nullable();
            $table->string('jabatan_pelaksana_opd_2', 150)->nullable();

            $table->string('pelaksana_opd_3', 150)->nullable();
            $table->string('nip_pelaksana_opd_3', 100)->nullable();
            $table->string('jabatan_pelaksana_opd_3', 150)->nullable();

            $table->string('pelaksana_opd_4', 150)->nullable();
            $table->string('nip_pelaksana_opd_4', 100)->nullable();
            $table->string('jabatan_pelaksana_opd_4', 150)->nullable();

            // Keterangan Kehadiran / Berita Acara Pertemuan
            $table->text('keterangan_kehadiran')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tiket_dtsens', function (Blueprint $table) {
            $table->dropColumn([
                'nip_penanggung_jawab',
                'nip_pelaksana_1',
                'jabatan_pelaksana_1',
                'nip_pelaksana_2',
                'jabatan_pelaksana_2',
                'nip_pelaksana_3',
                'jabatan_pelaksana_3',
                'nip_pelaksana_4',
                'jabatan_pelaksana_4',
                'pelaksana_opd_1',
                'nip_pelaksana_opd_1',
                'jabatan_pelaksana_opd_1',
                'pelaksana_opd_2',
                'nip_pelaksana_opd_2',
                'jabatan_pelaksana_opd_2',
                'pelaksana_opd_3',
                'nip_pelaksana_opd_3',
                'jabatan_pelaksana_opd_3',
                'pelaksana_opd_4',
                'nip_pelaksana_opd_4',
                'jabatan_pelaksana_opd_4',
                'keterangan_kehadiran',
            ]);
        });
    }
};


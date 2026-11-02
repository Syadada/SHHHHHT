<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketDtsen extends Model
{
    use HasFactory;

    protected $table = 'tiket_dtsens';

    protected $fillable = [
        'nomor_tiket',
        'nama_instansi',
        'no_surat_edaran',
        'nama_pemohon',
        'kontak_pemohon',
        'email_pemohon',
        'tujuan_pemanfaatan',
        'variabel_dtsen',
        'file_surat',
        'nama_file_asli',
        'ukuran_file',
        'ekstensi_file',
        'hash_sha256',
        'current_step',
        'step1_done',
        'step2_done',
        'step3_done',
        'step4_done',
        'step5_done',
        'status',
        'catatan_admin',
        'step1_at',
        'step2_at',
        'step3_at',
        'step4_at',
        'step5_at',
        // Lampiran I SOP Form Fields
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
    ];

    protected $casts = [
        'variabel_dtsen' => 'array',
        'data_sop_extra' => 'array',
        'tgl_permintaan' => 'date',
        'tgl_diterima' => 'date',
        'tgl_surat_tugas' => 'date',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'step1_done' => 'boolean',
        'step2_done' => 'boolean',
        'step3_done' => 'boolean',
        'step4_done' => 'boolean',
        'step5_done' => 'boolean',
        'step1_at' => 'datetime',
        'step2_at' => 'datetime',
        'step3_at' => 'datetime',
        'step4_at' => 'datetime',
        'step5_at' => 'datetime',
    ];

    /**
     * Definisi Tahap Progress Bagi Pakai Data DTSEN (Progress 1, 2, 3, dst.)
     */
    public static array $stepsDefinition = [
        1 => [
            'id' => 1,
            'title' => 'Progress 1',
            'desc' => 'Tahap proses ke-1',
            'pic' => 'Admin Layanan DTSEN'
        ],
        2 => [
            'id' => 2,
            'title' => 'Progress 2',
            'desc' => 'Tahap proses ke-2',
            'pic' => 'Tim Pengelola Data & Statistik'
        ],
        3 => [
            'id' => 3,
            'title' => 'Progress 3',
            'desc' => 'Tahap proses ke-3',
            'pic' => 'Walidata DTSEN'
        ],
        4 => [
            'id' => 4,
            'title' => 'Progress 4',
            'desc' => 'Tahap proses ke-4',
            'pic' => 'Tim Teknis IT & Keamanan Siber'
        ],
        5 => [
            'id' => 5,
            'title' => 'Progress 5',
            'desc' => 'Tahap proses ke-5',
            'pic' => 'Admin Layanan DTSEN'
        ],
    ];

    /**
     * Hitung persentase progress keseluruhan (0 - 100%)
     */
    public function getProgressPercentAttribute(): int
    {
        $doneCount = 0;
        if ($this->step1_done) $doneCount++;
        if ($this->step2_done) $doneCount++;
        if ($this->step3_done) $doneCount++;
        if ($this->step4_done) $doneCount++;
        if ($this->step5_done) $doneCount++;

        if ($doneCount === 5) return 100;
        if ($doneCount === 0) return 10;
        return (int) round(($doneCount / 5) * 100);
    }

    /**
     * Cek apakah step tertentu sudah centang hijau
     */
    public function isStepDone(int $step): bool
    {
        $col = "step{$step}_done";
        return (bool) ($this->$col ?? false);
    }

    /**
     * Cek apakah step tertentu sedang aktif / diproses saat ini
     */
    public function isStepActive(int $step): bool
    {
        if ($this->isStepDone($step)) {
            return false;
        }
        
        // Aktif jika step ini adalah step pertama yang belum selesai
        for ($i = 1; $i < $step; $i++) {
            if (!$this->isStepDone($i)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Label status aktif saat ini
     */
    public function getCurrentStatusLabelAttribute(): string
    {
        if ($this->step5_done) {
            return 'SELESAI (Semua Progress Selesai)';
        }
        if ($this->step4_done) {
            return 'Sedang Berjalan: Progress 5';
        }
        if ($this->step3_done) {
            return 'Sedang Berjalan: Progress 4';
        }
        if ($this->step2_done) {
            return 'Sedang Berjalan: Progress 3';
        }
        if ($this->step1_done) {
            return 'Sedang Berjalan: Progress 2';
        }
        return 'Sedang Berjalan: Progress 1';
    }

    public function pengolahan()
    {
        return $this->hasOne(PengolahanDtsen::class, 'tiket_dtsen_id');
    }

    public function hakAkses()
    {
        return $this->hasMany(HakAksesDtsen::class, 'tiket_dtsen_id');
    }

    public function pengamanan()
    {
        return $this->hasOne(PengamananDtsen::class, 'tiket_dtsen_id');
    }

    public function ropa()
    {
        return $this->hasOne(RopaDtsen::class, 'tiket_dtsen_id');
    }

    public function dpia()
    {
        return $this->hasOne(DpiaDtsen::class, 'tiket_dtsen_id');
    }

    public function riwayatBagiData()
    {
        return $this->hasMany(RiwayatBagiDataDtsen::class, 'tiket_dtsen_id');
    }
}


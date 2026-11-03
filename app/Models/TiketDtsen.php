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
        'no_surat_permohonan',
        'tgl_surat_permohonan',
        'perihal',
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
        // 5 Berkas Dokumen OPD & Narahubung
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
        'file_klasifikasi_dtsen',
        'nama_file_klasifikasi_dtsen',
        'file_hak_akses',
        'nama_file_hak_akses',
        'nama_narahubung',
        'no_narahubung',
        'current_step',
        'step1_done',
        'step2_done',
        'step3_done',
        'step4_done',
        'step5_done',
        'status',
        'catatan_admin',
        'items_revisi',
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
        'items_revisi' => 'array',
        'data_sop_extra' => 'array',
        'tgl_permintaan' => 'date',
        'tgl_surat_permohonan' => 'date',
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
     * Definisi 4 Tahap Progress Bagi Pakai Data DTSEN untuk Sisi OPD
     * 1. Pengajuan
     * 2. Pengolahan
     * 3. Transfer
     * 4. Selesai
     */
    public static array $opdStepsDefinition = [
        1 => [
            'id' => 1,
            'title' => 'Pengajuan',
            'desc' => 'Pengajuan berkas permohonan layanan bagi pakai DTSEN',
        ],
        2 => [
            'id' => 2,
            'title' => 'Pengolahan',
            'desc' => 'Pemenuhan dokumen dan pengolahan data mikro DTSEN',
        ],
        3 => [
            'id' => 3,
            'title' => 'Transfer',
            'desc' => 'Penyaluran dan transfer dataset terpadu secara aman',
        ],
        4 => [
            'id' => 4,
            'title' => 'Selesai',
            'desc' => 'Pengesahan Berita Acara Serah Terima (BAST) dan layanan selesai',
        ],
    ];

    /**
     * Definisi Tahap Alur Kerja Sisi Admin (Untuk sementara Proses 1 & 2)
     */
    public static array $adminStepsDefinition = [
        1 => [
            'id' => 1,
            'title' => 'Pemenuhan Dokumen',
            'desc' => 'Verifikasi dan pemenuhan kelengkapan dokumen KAK, Surat Permohonan, ROPA/DPIA/SKTM',
            'pic' => 'Admin Walidata DTSEN'
        ],
        2 => [
            'id' => 2,
            'title' => 'Pengolahan DTSEN',
            'desc' => 'Pengolahan data dan pemilahan variabel data mikro DTSEN sesuai kebutuhan',
            'pic' => 'Tim Pengelola Data & Statistik'
        ],
    ];

    /**
     * Definisi Legacy 5 Tahap Progress
     */
    public static array $stepsDefinition = [
        1 => [
            'id' => 1,
            'title' => 'Pemenuhan Dokumen',
            'desc' => 'Pemeriksaan berkas permohonan',
            'pic' => 'Admin Walidata DTSEN'
        ],
        2 => [
            'id' => 2,
            'title' => 'Pengolahan DTSEN',
            'desc' => 'Pengolahan data DTSEN',
            'pic' => 'Tim Pengelola Data & Statistik'
        ],
        3 => [
            'id' => 3,
            'title' => 'Transfer Data',
            'desc' => 'Transfer dataset aman',
            'pic' => 'Walidata DTSEN'
        ],
        4 => [
            'id' => 4,
            'title' => 'Pengesahan BAST',
            'desc' => 'Penyusunan Berita Acara',
            'pic' => 'Tim Teknis IT'
        ],
        5 => [
            'id' => 5,
            'title' => 'Selesai',
            'desc' => 'Layanan tuntas',
            'pic' => 'Admin Layanan DTSEN'
        ],
    ];

    /**
     * Hitung persentase progress sisi OPD (4 Tahap: 25%, 50%, 75%, 100%)
     */
    public function getOpdProgressPercentAttribute(): int
    {
        if ($this->isOpdStepDone(4)) return 100;
        if ($this->isOpdStepDone(3)) return 75;
        if ($this->isOpdStepDone(2)) return 50;
        if ($this->isOpdStepDone(1)) return 25;
        return 10;
    }

    /**
     * Cek apakah tahap OPD tertentu sudah selesai / centang hijau
     */
    public function isOpdStepDone(int $step): bool
    {
        return match ($step) {
            1 => $this->status !== 'PERLU_PERBAIKAN',
            2 => (bool) ($this->step1_done && $this->step2_done), // Otomatis centang jika Admin Proses 1 & 2 selesai
            3 => (bool) $this->step3_done,
            4 => (bool) ($this->step4_done || $this->step5_done || $this->status === 'SELESAI'),
            default => false,
        };
    }

    /**
     * Cek apakah tahap OPD tertentu sedang aktif / diproses saat ini
     */
    public function isOpdStepActive(int $step): bool
    {
        if ($this->isOpdStepDone($step)) {
            return false;
        }

        return match ($step) {
            1 => $this->status === 'PERLU_PERBAIKAN',
            2 => $this->isOpdStepDone(1) && !$this->isOpdStepDone(2),
            3 => $this->isOpdStepDone(2) && !$this->isOpdStepDone(3),
            4 => $this->isOpdStepDone(3) && !$this->isOpdStepDone(4),
            default => false,
        };
    }

    /**
     * Label status aktif untuk sisi OPD
     */
    public function getCurrentOpdStatusLabelAttribute(): string
    {
        if ($this->status === 'PERLU_PERBAIKAN') {
            return 'Perlu Perbaikan Berkas Permohonan';
        }
        if ($this->isOpdStepDone(4)) {
            return 'SELESAI (Semua Tahapan Selesai)';
        }
        if ($this->isOpdStepDone(3)) {
            return 'Sedang Berjalan: Tahap 4 (Pengesahan BAST)';
        }
        if ($this->isOpdStepDone(2)) {
            return 'Sedang Berjalan: Tahap 3 (Transfer Data)';
        }
        if ($this->step1_done) {
            return 'Sedang Berjalan: Tahap 2 (Pengolahan DTSEN)';
        }
        return 'Sedang Berjalan: Tahap 2 (Verifikasi Berkas & Pemenuhan Dokumen)';
    }

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

    /**
     * Memeriksa apakah suatu item/dokumen ditandai revisi oleh admin
     */
    public function isItemRevisi(string $key): bool
    {
        if ($this->status !== 'PERLU_PERBAIKAN') {
            return false;
        }
        return in_array($key, (array) ($this->items_revisi ?? []));
    }

    /**
     * Label representasi teks untuk item revisi
     */
    public static function getLabelItemRevisi(string $key): string
    {
        $map = [
            'surat_permohonan' => 'Surat Permohonan',
            'kak' => 'Berkas KAK',
            'sktm' => 'SK Tim Pelaksana',
            'ropa' => 'Berkas ROPA',
            'dpia' => 'Berkas DPIA',
            'klasifikasi_dtsen' => 'Klasifikasi DTSEN',
            'hak_akses' => 'Hak Akses',
            'variabel_dtsen' => 'Variabel DTSEN',
            'narahubung' => 'Kontak Narahubung',
        ];
        return $map[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}


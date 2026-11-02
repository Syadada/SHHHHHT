@extends('layouts.admin')

@section('title', 'Edit Permintaan Layanan #' . $tiket->nomor_tiket . ' — Bagi Pakai DTSEN')

@section('content')
@php
    $extra = is_array($tiket->data_sop_extra) ? $tiket->data_sop_extra : [];
@endphp
<div class="form-create-shell">

    <!-- Breadcrumb Navigasi Admin -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}">Layanan Bagi Pakai DTSEN</a>
        <span class="separator">/</span>
        <span class="current">Edit Permintaan #{{ $tiket->nomor_tiket }}</span>
    </nav>

    <!-- Header info banner -->
    <div class="dash-card form-group-mb-20">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                    <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fi fi-rr-edit" style="color: var(--blue-primary);"></i>
                        <span>Edit Permintaan Layanan Bagi Pakai DTSEN</span>
                    </h1>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px;">
                        #{{ $tiket->nomor_tiket }}
                    </span>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="fi fi-rr-document"></i> Format SOP: FR.01-SOP.DTSEN-09/DKISP
                    </span>
                </div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">
                    Perbarui data formulir pemenuhan bagi pakai DTSEN (Lampiran I SOP). Seluruh data tersimpan akan otomatis menjadi basis data tampilan dan ekspor berkas Word resmi (.docx).
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="{{ route('admin.bagi-pakai-dtsen.formulir', $tiket->id) }}" target="_blank" class="btn-pill-primary" style="font-size: 12px; padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fi fi-rr-document-signed"></i> Pratinjau Formulir SOP
                </a>
                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; text-decoration: none;">
                    <i class="fi fi-rr-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Alert Error jika Ada -->
    @if(isset($errors) && $errors->any())
        <div class="form-error-banner">
            <div class="form-error-title">
                <i class="fi fi-rr-cross-circle"></i> Mohon Periksa Isian Anda:
            </div>
            <ul class="form-error-list">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bagi-pakai-dtsen.update', $tiket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. HEADER DOKUMEN & STATUS LAYANAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <h2 class="section-head-title">1. Header Dokumen & Status Permintaan</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nomor Naskah Internal Bidang</label>
                    <input type="text" 
                           name="nomor_naskah" 
                           class="form-text-input" 
                           value="{{ old('nomor_naskah', $tiket->nomor_naskah) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Status Permintaan Layanan</label>
                    <select name="status" class="form-text-input" style="font-size: 13px; padding: 8px 12px;">
                        <option value="DIPROSES" {{ old('status', $tiket->status) === 'DIPROSES' ? 'selected' : '' }}>Sedang Berjalan (DIPROSES)</option>
                        <option value="SELESAI" {{ old('status', $tiket->status) === 'SELESAI' ? 'selected' : '' }}>Selesai 100% (SELESAI)</option>
                        <option value="PERLU_PERBAIKAN" {{ old('status', $tiket->status) === 'PERLU_PERBAIKAN' ? 'selected' : '' }}>Perlu Perbaikan (REVISI)</option>
                    </select>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">No. Agenda Disposisi</label>
                    <input type="text" 
                           name="no_agenda_disposisi" 
                           class="form-text-input" 
                           value="{{ old('no_agenda_disposisi', $tiket->no_agenda_disposisi) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">SOP Rujukan</label>
                    <input type="text" 
                           name="sop_rujukan" 
                           class="form-text-input" 
                           value="{{ old('sop_rujukan', $tiket->sop_rujukan) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>
        </div>

        <!-- 2. PERMINTAAN LAYANAN (INSTANSI & NARAHUBUNG) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <h2 class="section-head-title">2. Permintaan Layanan (Instansi & Narahubung)</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nama Instansi / Lembaga Pemohon</label>
                    <input type="text" 
                           name="nama_instansi" 
                           id="nama_instansi"
                           class="form-text-input" 
                           value="{{ old('nama_instansi', $tiket->nama_instansi) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada" 
                           required>
                </div>

                <div>
                    <label class="form-group-label">Nomor Surat Permintaan / Edaran Resmi</label>
                    <input type="text" 
                           name="no_surat_edaran" 
                           class="form-text-input" 
                           value="{{ old('no_surat_edaran', $tiket->no_surat_edaran) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada" 
                           required>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Tanggal Permintaan</label>
                    <input type="date" 
                           name="tgl_permintaan" 
                           class="form-text-input" 
                           value="{{ old('tgl_permintaan', $tiket->tgl_permintaan ? $tiket->tgl_permintaan->format('Y-m-d') : '') }}">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Diterima Walidata</label>
                    <input type="date" 
                           name="tgl_diterima" 
                           class="form-text-input" 
                           value="{{ old('tgl_diterima', $tiket->tgl_diterima ? $tiket->tgl_diterima->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Nama Lengkap Narahubung / PIC</label>
                    <input type="text" 
                           name="nama_pemohon" 
                           id="nama_pemohon"
                           class="form-text-input" 
                           value="{{ old('nama_pemohon', $tiket->nama_pemohon) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Kontak Telepon / WhatsApp</label>
                    <input type="text" 
                           name="kontak_pemohon" 
                           id="kontak_pemohon"
                           class="form-text-input" 
                           value="{{ old('kontak_pemohon', $tiket->kontak_pemohon) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Alamat Email Resmi Narahubung</label>
                <input type="email" 
                       name="email_pemohon" 
                       id="email_pemohon"
                       class="form-text-input" 
                       value="{{ old('email_pemohon', $tiket->email_pemohon) }}" 
                       placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
            </div>

            <div style="margin-top: 18px;">
                <label class="form-group-label">Tujuan Pemanfaatan Data DTSEN</label>
                <textarea name="tujuan_pemanfaatan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('tujuan_pemanfaatan', $tiket->tujuan_pemanfaatan) }}</textarea>
            </div>
        </div>

        <!-- 3. PENUGASAN & DESKRIPSI PEKERJAAN (WALIDATA) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <h2 class="section-head-title">3. Penugasan & Deskripsi Pekerjaan (Walidata)</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nomor Surat Tugas</label>
                    <input type="text" 
                           name="no_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('no_surat_tugas', $tiket->no_surat_tugas) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Surat Tugas</label>
                    <input type="date" 
                           name="tgl_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('tgl_surat_tugas', $tiket->tgl_surat_tugas ? $tiket->tgl_surat_tugas->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Deskripsi Pekerjaan</label>
                <input type="text" 
                       name="deskripsi_pekerjaan" 
                       class="form-text-input" 
                       value="{{ old('deskripsi_pekerjaan', $tiket->deskripsi_pekerjaan) }}" 
                       placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Penanggung Jawab (Pejabat Administrator)</label>
                    <input type="text" 
                           name="penanggung_jawab" 
                           class="form-text-input" 
                           value="{{ old('penanggung_jawab', $tiket->penanggung_jawab) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Tempat Pelaksanaan</label>
                    <input type="text" 
                           name="tempat_pelaksanaan" 
                           class="form-text-input" 
                           value="{{ old('tempat_pelaksanaan', $tiket->tempat_pelaksanaan) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Tanggal Mulai Pelaksanaan</label>
                    <input type="date" 
                           name="tgl_mulai" 
                           class="form-text-input" 
                           value="{{ old('tgl_mulai', $tiket->tgl_mulai ? $tiket->tgl_mulai->format('Y-m-d') : '') }}">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Selesai Pelaksanaan</label>
                    <input type="date" 
                           name="tgl_selesai" 
                           class="form-text-input" 
                           value="{{ old('tgl_selesai', $tiket->tgl_selesai ? $tiket->tgl_selesai->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Pelaksana 1</label>
                    <input type="text" 
                           name="pelaksana_1" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_1', $tiket->pelaksana_1) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Pelaksana 2</label>
                    <input type="text" 
                           name="pelaksana_2" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_2', $tiket->pelaksana_2) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Pelaksana 3</label>
                    <input type="text" 
                           name="pelaksana_3" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_3', $tiket->pelaksana_3) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Pelaksana 4</label>
                    <input type="text" 
                           name="pelaksana_4" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_4', $tiket->pelaksana_4) }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>
        </div>

        <!-- 4. VARIABEL DTSEN YANG DIMINTA -->
        <div class="form-section-card">
            <div class="section-head-box-tight">
                <h2 class="section-head-title">4. Variabel DTSEN yang Diminta</h2>

                <button type="button" onclick="tambahBarisVariabel()" class="btn-pill-outline revisi-btn-add">
                    <i class="fi fi-rr-plus"></i> + Tambah Kolom Isian
                </button>
            </div>

            <div id="containerVariabel" style="margin-top: 16px;">
                @php
                    $vars = old('variabel_dtsen', is_array($tiket->variabel_dtsen) ? $tiket->variabel_dtsen : []);
                    if (count($vars) < 1) $vars = [''];
                @endphp

                @foreach($vars as $idx => $v)
                    <div class="var-row-item">
                        <div class="var-num-chip">{{ $idx + 1 }}</div>
                        <input type="text" 
                               name="variabel_dtsen[]" 
                               class="form-text-input" 
                               value="{{ $v }}" 
                               placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                        @if(count($vars) > 1)
                            <button type="button" onclick="hapusBarisVariabel(this)" class="btn-del-var" title="Hapus baris variabel ini"><i class="fi fi-rr-cross-small"></i></button>
                        @else
                            <div class="var-spacer-col"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 5. PEMENUHAN DOKUMEN & MATRIKS PELAKSANAAN SOP -->
        <div class="form-section-card">
            <div class="section-head-box">
                <h2 class="section-head-title">5. Pemenuhan Dokumen & Matriks Pelaksanaan SOP</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Dokumen KAK (Kerangka Acuan Kerja)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_kak" class="form-text-input" style="width: 140px;">
                            <option value="Ada" {{ old('status_kak', $extra['status_kak'] ?? 'Ada') === 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Tidak ada" {{ old('status_kak', $extra['status_kak'] ?? 'Ada') === 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_kak" class="form-text-input" value="{{ old('nomor_kak', $extra['nomor_kak'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Penetapan Tim Pelaksana</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_tim" class="form-text-input" style="width: 140px;">
                            <option value="Ada" {{ old('status_tim', $extra['status_tim'] ?? 'Ada') === 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Tidak ada" {{ old('status_tim', $extra['status_tim'] ?? 'Ada') === 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_tim" class="form-text-input" value="{{ old('nomor_tim', $extra['nomor_tim'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Dokumen ROPA DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_ropa" class="form-text-input" style="width: 140px;">
                            <option value="Sesuai" {{ old('status_ropa', $extra['status_ropa'] ?? 'Sesuai') === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Belum Sesuai" {{ old('status_ropa', $extra['status_ropa'] ?? 'Sesuai') === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_ropa" class="form-text-input" value="{{ old('nomor_ropa', $extra['nomor_ropa'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Dokumen DPIA DTSEN (Penilaian Dampak PDP)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_dpia" class="form-text-input" style="width: 140px;">
                            <option value="Sesuai" {{ old('status_dpia', $extra['status_dpia'] ?? 'Sesuai') === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Belum Sesuai" {{ old('status_dpia', $extra['status_dpia'] ?? 'Sesuai') === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_dpia" class="form-text-input" value="{{ old('nomor_dpia', $extra['nomor_dpia'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Klasifikasi DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_klasifikasi" class="form-text-input" style="width: 160px;">
                            <option value="Sesuai (Data Terbatas)" {{ old('status_klasifikasi', $extra['status_klasifikasi'] ?? 'Sesuai (Data Terbatas)') === 'Sesuai (Data Terbatas)' ? 'selected' : '' }}>Sesuai (Data Terbatas)</option>
                            <option value="Terbuka" {{ old('status_klasifikasi', $extra['status_klasifikasi'] ?? '') === 'Terbuka' ? 'selected' : '' }}>Terbuka</option>
                            <option value="Rahasia" {{ old('status_klasifikasi', $extra['status_klasifikasi'] ?? '') === 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                        </select>
                        <input type="text" name="nomor_klasifikasi" class="form-text-input" value="{{ old('nomor_klasifikasi', $extra['nomor_klasifikasi'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Hak Akses DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_hak_akses" class="form-text-input" style="width: 140px;">
                            <option value="Diverifikasi" {{ old('status_hak_akses', $extra['status_hak_akses'] ?? 'Diverifikasi') === 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                            <option value="Belum Sesuai" {{ old('status_hak_akses', $extra['status_hak_akses'] ?? '') === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_hak_akses" class="form-text-input" value="{{ old('nomor_hak_akses', $extra['nomor_hak_akses'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Jadwal Pelaksanaan Serah Terima</label>
                    <input type="date" name="jadwal_serah_terima" class="form-text-input" value="{{ old('jadwal_serah_terima', $extra['jadwal_serah_terima'] ?? '') }}">
                </div>

                <div>
                    <label class="form-group-label">No. Berita Acara Serah Terima (BAST)</label>
                    <input type="text" name="no_bast" class="form-text-input" value="{{ old('no_bast', $extra['no_bast'] ?? '') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>
        </div>

        <!-- 6. CATATAN PELAKSANA -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
                <h2 class="section-head-title">6. Catatan Pelaksana</h2>
            </div>

            <div>
                <textarea name="catatan_admin" 
                          rows="3" 
                          class="form-text-input" 
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('catatan_admin', $tiket->catatan_admin) }}</textarea>
            </div>
        </div>

        <!-- 7. HASIL PELAKSANAAN PEKERJAAN -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
                <h2 class="section-head-title">7. Hasil Pelaksanaan Pekerjaan</h2>
            </div>

            <div>
                <textarea name="hasil_pekerjaan" 
                          rows="3" 
                          class="form-text-input" 
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('hasil_pekerjaan', $tiket->hasil_pekerjaan) }}</textarea>
            </div>
        </div>

        <!-- 8. UNGGAH BERKAS LAMPIRAN SURAT EDARAN RESMI -->
        <div class="form-section-card">
            <div class="section-head-box">
                <h2 class="section-head-title">8. Lampiran Berkas Permohonan Resmi</h2>
            </div>

            @if($tiket->file_surat)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fi fi-rr-file-pdf" style="font-size: 22px; color: #dc2626;"></i>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">
                                {{ $tiket->nama_file_asli ?: 'Berkas Surat Edaran' }}
                            </div>
                            <div style="font-size: 11px; color: #64748b;">
                                Ukuran: {{ $tiket->ukuran_file ?: '-' }} | SHA-256: <code>{{ substr($tiket->hash_sha256, 0, 16) }}...</code>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.riwayat.download', $tiket->id) }}" class="btn-pill-outline" style="font-size: 11px; padding: 4px 10px; text-decoration: none;">
                        <i class="fi fi-rr-download"></i> Unduh Berkas
                    </a>
                </div>
            @endif

            <div class="upload-drop-zone" onclick="document.getElementById('inputSuratFile').click()">
                <div class="upload-icon-lg">
                    <i class="fi fi-rr-cloud-upload-alt"></i>
                </div>
                <div class="upload-title" id="fileDisplayTitle">
                    {{ $tiket->file_surat ? 'Klik untuk mengganti berkas surat permohonan' : 'Klik untuk memilih berkas surat permohonan dinas' }}
                </div>
                <div class="upload-subtitle" id="fileDisplaySubtitle">
                    Format file: .pdf, .docx, .doc (Maksimal 50 MB)
                </div>
                <input type="file" 
                       name="file_surat" 
                       id="inputSuratFile" 
                       class="d-none" 
                       accept=".pdf,.doc,.docx"
                       onchange="updateFilePreview(this)">
            </div>
        </div>

        <!-- TOMBOL AKSI SUBMIT -->
        <div class="submit-actions-row">
            <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline btn-cancel-action">
                &larr; Batal & Kembali ke Daftar
            </a>

            <button type="submit" class="btn-pill-primary btn-submit-action">
                <i class="fi fi-rr-check"></i>
                <span>Simpan Perubahan Formulir Layanan SOP</span>
            </button>
        </div>

    </form>

</div>

@section('scripts')
<script>
    function tambahBarisVariabel() {
        const container = document.getElementById('containerVariabel');
        const count = container.querySelectorAll('.var-row-item').length + 1;

        const row = document.createElement('div');
        row.className = 'var-row-item';
        row.innerHTML = `
            <div class="var-num-chip">${count}</div>
            <input type="text" 
                   name="variabel_dtsen[]" 
                   class="form-text-input" 
                   placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
            <button type="button" onclick="hapusBarisVariabel(this)" class="btn-del-var" title="Hapus baris variabel ini"><i class="fi fi-rr-cross-small"></i></button>
        `;
        container.appendChild(row);
        urutkanNomor();
    }

    function hapusBarisVariabel(btn) {
        const row = btn.closest('.var-row-item');
        row.remove();
        urutkanNomor();
    }

    function urutkanNomor() {
        const rows = document.querySelectorAll('#containerVariabel .var-row-item');
        rows.forEach((r, idx) => {
            const chip = r.querySelector('.var-num-chip');
            if (chip) chip.innerText = idx + 1;
            const input = r.querySelector('input');
            if (input && !input.value) {
                input.placeholder = 'Isi jika ada, atau beri tanda strip (-) bila tidak ada';
            }
        });
    }

    function updateFilePreview(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            document.getElementById('fileDisplayTitle').innerHTML = `<strong>${file.name}</strong> (${sizeMb} MB)`;
            document.getElementById('fileDisplaySubtitle').innerHTML = `<span class="upload-ready-text"><i class="fi fi-rr-check"></i> Berkas baru siap diunggah</span>`;
        }
    }
</script>
@endsection
@endsection

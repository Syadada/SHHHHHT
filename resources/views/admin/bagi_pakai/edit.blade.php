@extends('layouts.admin')

@section('title', 'Edit Permintaan Layanan #' . $tiket->nomor_tiket . ' — Bagi Pakai DTSEN')

@section('content')
@php
    $extra = is_array($tiket->data_sop_extra) ? $tiket->data_sop_extra : [];
@endphp
<div class="form-create-shell">

    <!-- Breadcrumb Navigasi Admin -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Walidata</a>
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
                <div style="font-size: 13px; color: #64748b;">
                    Instansi Pemohon: <strong style="color: #0f172a;">{{ $tiket->nama_instansi }}</strong> &bull; Waktu Diajukan: {{ $tiket->created_at->format('d/m/Y H:i') }} WIB
                </div>
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
                <div class="section-head-icon">
                    <i class="fi fi-rr-settings-sliders"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Header Dokumen & Status Permintaan</h2>
                    <p class="section-head-desc">Pengaturan administrasi naskah dan status pemrosesan layanan di Walidata</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nomor Naskah Internal Bidang</label>
                    <input type="text" 
                           name="nomor_naskah" 
                           class="form-text-input" 
                           value="{{ old('nomor_naskah', $tiket->nomor_naskah) }}" 
                           placeholder="Contoh: 500.12.6.1/123-Diskominfo/2026">
                </div>

                <div>
                    <label class="form-group-label">Status Permintaan Layanan</label>
                    <select name="status" class="form-text-input" style="font-size: 13px; padding: 8px 12px; font-weight: 600;">
                        <option value="DIPROSES" {{ old('status', $tiket->status) === 'DIPROSES' ? 'selected' : '' }}>Sedang Berjalan (DIPROSES)</option>
                        <option value="SELESAI" {{ old('status', $tiket->status) === 'SELESAI' ? 'selected' : '' }}>Selesai 100% (SELESAI)</option>
                        <option value="PERLU_PERBAIKAN" {{ old('status', $tiket->status) === 'PERLU_PERBAIKAN' ? 'selected' : '' }}>Perlu Perbaikan Berkas (REVISI)</option>
                        <option value="PENDING" {{ old('status', $tiket->status) === 'PENDING' ? 'selected' : '' }}>Menunggu Verifikasi Berkas (PENDING)</option>
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
                           placeholder="Nomor agenda disposisi pimpinan">
                </div>

                <div>
                    <label class="form-group-label">SOP Rujukan</label>
                    <input type="text" 
                           name="sop_rujukan" 
                           class="form-text-input" 
                           value="{{ old('sop_rujukan', $tiket->sop_rujukan ?: $noSop) }}" 
                           placeholder="Standar Operasional Prosedur rujukan">
                </div>
            </div>
        </div>

        <!-- 2. PERMINTAAN LAYANAN (INSTANSI & NARAHUBUNG) - SESUAI SISI OPD -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Identitas Instansi & Narahubung</h2>
                    <p class="section-head-desc">Identitas pemohon resmi dan kontak penanggung jawab / narahubung OPD</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nama Instansi / Lembaga Pemohon <span class="form-required">*</span></label>
                    <input type="text" 
                           name="nama_instansi" 
                           id="nama_instansi"
                           class="form-text-input" 
                           value="{{ old('nama_instansi', $tiket->nama_instansi) }}" 
                           placeholder="Nama instansi pemohon resmi" 
                           required>
                </div>

                <div>
                    <label class="form-group-label">Nama Lengkap Narahubung / PIC <span class="form-required">*</span></label>
                    <input type="text" 
                           name="nama_narahubung" 
                           id="nama_narahubung"
                           class="form-text-input" 
                           value="{{ old('nama_narahubung', $tiket->nama_narahubung ?: $tiket->nama_pemohon) }}" 
                           placeholder="Nama lengkap narahubung pemohon"
                           required>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Nomor Telepon / WhatsApp Narahubung <span class="form-required">*</span></label>
                    <input type="text" 
                           name="no_narahubung" 
                           id="no_narahubung"
                           class="form-text-input" 
                           value="{{ old('no_narahubung', $tiket->no_narahubung ?: $tiket->kontak_pemohon) }}" 
                           placeholder="Nomor telepon / WhatsApp narahubung aktif"
                           required>
                </div>

                <div>
                    <label class="form-group-label">Alamat Email Resmi Narahubung</label>
                    <input type="email" 
                           name="email_pemohon" 
                           id="email_pemohon"
                           class="form-text-input" 
                           value="{{ old('email_pemohon', $tiket->email_pemohon) }}" 
                           placeholder="contoh: dinas@tasikmalayakota.go.id">
                </div>
            </div>
        </div>

        <!-- 3. BERKAS DOKUMEN PERMINTAAN & SURAT RESMI (PERSIS SESUAI SISI OPD) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-document"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Berkas Dokumen Permintaan & Surat Resmi</h2>
                    <p class="section-head-desc">Seluruh berkas fisik lampiran dan metadata surat permohonan yang diajukan oleh OPD</p>
                </div>
            </div>

            <div class="dtsen-filebox-grid">
                
                <!-- 1. Surat Permohonan (File Box & Input Fields) - Spans Full Width at Top -->
                <div class="dtsen-filebox-card dtsen-filebox-permohonan">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Surat Permohonan
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_surat_permohonan || $tiket->file_surat)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'surat_permohonan']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Surat Permohonan">
                                    <i class="fi fi-rr-download"></i> Unduh Berkas
                                </a>
                            @endif
                            <button type="button" 
                                    id="btnDelPermohonan" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputPermohonan', 'permohonanTitle', 'permohonanSub', 'btnDelPermohonan', 'permohonan', 'Pilih Berkas Surat Permohonan', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-req">Wajib</span>
                        </div>
                    </div>
                    <div class="dtsen-permohonan-body">
                        <!-- File Dropzone Permohonan -->
                        <div class="dtsen-file-dropzone dtsen-dropzone-permohonan" onclick="document.getElementById('inputPermohonan').click()">
                            <i class="fi fi-rr-cloud-upload-alt dtsen-dropzone-icon"></i>
                            <div class="dtsen-dropzone-title" id="permohonanTitle">
                                @if($tiket->file_surat_permohonan || $tiket->file_surat)
                                    <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_surat_permohonan ?: ($tiket->nama_file_asli ?: 'Surat Permohonan') }}</span>
                                @else
                                    Pilih Berkas Surat Permohonan
                                @endif
                            </div>
                            <div class="dtsen-dropzone-sub" id="permohonanSub">
                                Klik di sini untuk mengunggah berkas pengganti (PDF, DOC, DOCX Maks 500MB)
                            </div>
                            <input type="file" 
                                   name="file_surat_permohonan" 
                                   id="inputPermohonan" 
                                   class="d-none" 
                                   accept=".pdf,.doc,.docx" 
                                   onchange="handleFileChange(this, 'permohonanTitle', 'permohonanSub', 'btnDelPermohonan', 'permohonan')">
                        </div>

                        <!-- 3 Input Surat Permohonan: Nomor Surat, Tanggal, Perihal -->
                        <div class="dtsen-permohonan-meta-inputs">
                            <div>
                                <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                    Nomor Surat Permintaan / Edaran <span class="form-required">*</span>
                                </label>
                                <input type="text" 
                                       name="no_surat_permohonan" 
                                       class="form-text-input" 
                                       value="{{ old('no_surat_permohonan', $tiket->no_surat_permohonan ?: $tiket->no_surat_edaran) }}" 
                                       placeholder="Nomor surat permohonan resmi"
                                       style="padding: 8px 12px; font-size: 13px;"
                                       required>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                        Tanggal Surat Permintaan
                                    </label>
                                    <input type="date" 
                                           name="tgl_surat_permohonan" 
                                           class="form-text-input" 
                                           value="{{ old('tgl_surat_permohonan', $tiket->tgl_surat_permohonan ? $tiket->tgl_surat_permohonan->format('Y-m-d') : ($tiket->tgl_permintaan ? \Carbon\Carbon::parse($tiket->tgl_permintaan)->format('Y-m-d') : '')) }}"
                                           style="padding: 8px 12px; font-size: 13px;">
                                </div>
                                <div>
                                    <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                        Perihal / Tujuan Pemanfaatan
                                    </label>
                                    <input type="text" 
                                           name="perihal" 
                                           class="form-text-input" 
                                           value="{{ old('perihal', $tiket->perihal ?: $tiket->tujuan_pemanfaatan) }}" 
                                           placeholder="Perihal surat permohonan dinas"
                                           style="padding: 8px 12px; font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. KAK (File Box) -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            KAK (Kerangka Acuan Kerja)
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_kak)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'kak']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas KAK">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                            @endif
                            <button type="button" 
                                    id="btnDelKak" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputKak', 'kakTitle', 'kakSub', 'btnDelKak', 'kak', 'Pilih Berkas KAK', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-req">Wajib</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputKak').click()">
                        <i class="fi fi-rr-cloud-upload-alt dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="kakTitle">
                            @if($tiket->file_kak)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_kak ?: 'Surat KAK' }}</span>
                            @else
                                Pilih Berkas KAK
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="kakSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)</div>
                        <input type="file" 
                               name="file_kak" 
                               id="inputKak" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'kakTitle', 'kakSub', 'btnDelKak', 'kak')">
                    </div>
                </div>

                <!-- 3. SK Tim Pelaksana (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            SK Tim Pelaksana
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_sktm)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'sktm']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas SKTM">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                                <button type="button" 
                                        id="btnHapusSktm" 
                                        class="dtsen-file-delete-btn" 
                                        onclick="toggleHapusServerFile('sktm', 'hapus_file_sktm', 'sktmTitle', 'sktmSub', 'btnHapusSktm')" 
                                        title="Hapus berkas ini dari permohonan">
                                    <i class="fi fi-rr-trash"></i> Hapus Berkas
                                </button>
                                <input type="hidden" name="hapus_file_sktm" id="hapus_file_sktm" value="0">
                            @endif
                            <button type="button" 
                                    id="btnDelSktm" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputSktm', 'sktmTitle', 'sktmSub', 'btnDelSktm', 'sktm', 'Pilih Berkas SK Tim Pelaksana', 'PDF, DOC, DOCX, ZIP (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-opt">Opsional</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputSktm').click()">
                        <i class="fi fi-rr-file dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="sktmTitle">
                            @if($tiket->file_sktm)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_sktm ?: 'SK Tim Pelaksana' }}</span>
                            @else
                                Pilih Berkas SK Tim Pelaksana
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="sktmSub">Klik untuk ganti berkas (PDF, DOC, DOCX, ZIP Maks 500MB)</div>
                        <input type="file" 
                               name="file_sktm" 
                               id="inputSktm" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.zip" 
                               onchange="handleFileChange(this, 'sktmTitle', 'sktmSub', 'btnDelSktm', 'sktm')">
                    </div>
                </div>

                <!-- 4. ROPA (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            ROPA DTSEN
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_ropa)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'ropa']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas ROPA">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                                <button type="button" 
                                        id="btnHapusRopa" 
                                        class="dtsen-file-delete-btn" 
                                        onclick="toggleHapusServerFile('ropa', 'hapus_file_ropa', 'ropaTitle', 'ropaSub', 'btnHapusRopa')" 
                                        title="Hapus berkas ini dari permohonan">
                                    <i class="fi fi-rr-trash"></i> Hapus Berkas
                                </button>
                                <input type="hidden" name="hapus_file_ropa" id="hapus_file_ropa" value="0">
                            @endif
                            <button type="button" 
                                    id="btnDelRopa" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputRopa', 'ropaTitle', 'ropaSub', 'btnDelRopa', 'ropa', 'Pilih Berkas ROPA', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-opt">Opsional</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputRopa').click()">
                        <i class="fi fi-rr-shield dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="ropaTitle">
                            @if($tiket->file_ropa)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_ropa ?: 'ROPA DTSEN' }}</span>
                            @else
                                Pilih Berkas ROPA
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="ropaSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)</div>
                        <input type="file" 
                               name="file_ropa" 
                               id="inputRopa" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'ropaTitle', 'ropaSub', 'btnDelRopa', 'ropa')">
                    </div>
                </div>

                <!-- 5. DPIA (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            DPIA DTSEN
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_dpia)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'dpia']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas DPIA">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                                <button type="button" 
                                        id="btnHapusDpia" 
                                        class="dtsen-file-delete-btn" 
                                        onclick="toggleHapusServerFile('dpia', 'hapus_file_dpia', 'dpiaTitle', 'dpiaSub', 'btnHapusDpia')" 
                                        title="Hapus berkas ini dari permohonan">
                                    <i class="fi fi-rr-trash"></i> Hapus Berkas
                                </button>
                                <input type="hidden" name="hapus_file_dpia" id="hapus_file_dpia" value="0">
                            @endif
                            <button type="button" 
                                    id="btnDelDpia" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputDpia', 'dpiaTitle', 'dpiaSub', 'btnDelDpia', 'dpia', 'Pilih Berkas DPIA', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-opt">Opsional</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputDpia').click()">
                        <i class="fi fi-rr-lock dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="dpiaTitle">
                            @if($tiket->file_dpia)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_dpia ?: 'DPIA DTSEN' }}</span>
                            @else
                                Pilih Berkas DPIA
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="dpiaSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)</div>
                        <input type="file" 
                               name="file_dpia" 
                               id="inputDpia" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'dpiaTitle', 'dpiaSub', 'btnDelDpia', 'dpia')">
                    </div>
                </div>

                <!-- 6. Klasifikasi DTSEN (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Klasifikasi DTSEN
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_klasifikasi_dtsen)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'klasifikasi_dtsen']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Klasifikasi">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                                <button type="button" 
                                        id="btnHapusKlasifikasi" 
                                        class="dtsen-file-delete-btn" 
                                        onclick="toggleHapusServerFile('klasifikasi', 'hapus_file_klasifikasi_dtsen', 'klasifikasiTitle', 'klasifikasiSub', 'btnHapusKlasifikasi')" 
                                        title="Hapus berkas ini dari permohonan">
                                    <i class="fi fi-rr-trash"></i> Hapus Berkas
                                </button>
                                <input type="hidden" name="hapus_file_klasifikasi_dtsen" id="hapus_file_klasifikasi_dtsen" value="0">
                            @endif
                            <button type="button" 
                                    id="btnDelKlasifikasi" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputKlasifikasi', 'klasifikasiTitle', 'klasifikasiSub', 'btnDelKlasifikasi', 'klasifikasi', 'Pilih Berkas Klasifikasi DTSEN', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-opt">Opsional</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputKlasifikasi').click()">
                        <i class="fi fi-rr-document-signed dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="klasifikasiTitle">
                            @if($tiket->file_klasifikasi_dtsen)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_klasifikasi_dtsen ?: 'Klasifikasi DTSEN' }}</span>
                            @else
                                Pilih Berkas Klasifikasi DTSEN
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="klasifikasiSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)</div>
                        <input type="file" 
                               name="file_klasifikasi_dtsen" 
                               id="inputKlasifikasi" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'klasifikasiTitle', 'klasifikasiSub', 'btnDelKlasifikasi', 'klasifikasi')">
                    </div>
                </div>

                <!-- 7. Hak Akses (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Hak Akses
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($tiket->file_hak_akses)
                                <a href="{{ route('tiket.download-dokumen', ['id' => $tiket->id, 'tipe' => 'hak_akses']) }}" target="_blank" class="dtsen-file-download-btn" style="color: #166534; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Hak Akses">
                                    <i class="fi fi-rr-download"></i> Unduh
                                </a>
                                <button type="button" 
                                        id="btnHapusHakAkses" 
                                        class="dtsen-file-delete-btn" 
                                        onclick="toggleHapusServerFile('hakAkses', 'hapus_file_hak_akses', 'hakAksesTitle', 'hakAksesSub', 'btnHapusHakAkses')" 
                                        title="Hapus berkas ini dari permohonan">
                                    <i class="fi fi-rr-trash"></i> Hapus Berkas
                                </button>
                                <input type="hidden" name="hapus_file_hak_akses" id="hapus_file_hak_akses" value="0">
                            @endif
                            <button type="button" 
                                    id="btnDelHakAkses" 
                                    class="dtsen-file-delete-btn" 
                                    style="display: none;" 
                                    onclick="clearNewFile('inputHakAkses', 'hakAksesTitle', 'hakAksesSub', 'btnDelHakAkses', 'hakAkses', 'Pilih Berkas Hak Akses', 'PDF, DOC, DOCX (Maks 500MB)')" 
                                    title="Batalkan berkas baru yang dipilih">
                                <i class="fi fi-rr-trash"></i> Batal Ganti
                            </button>
                            <span class="dtsen-badge-opt">Opsional</span>
                        </div>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputHakAkses').click()">
                        <i class="fi fi-rr-key dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="hakAksesTitle">
                            @if($tiket->file_hak_akses)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_hak_akses ?: 'Hak Akses' }}</span>
                            @else
                                Pilih Berkas Hak Akses
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="hakAksesSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)</div>
                        <input type="file" 
                               name="file_hak_akses" 
                               id="inputHakAkses" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'hakAksesTitle', 'hakAksesSub', 'btnDelHakAkses', 'hakAkses')">
                    </div>
                </div>

            </div>
        </div>

        <!-- 4. VARIABEL DTSEN YANG DIMINTA -->
        <div class="form-section-card">
            <div class="section-head-box-tight" style="margin-bottom: 4px;">
                <h2 class="section-head-title">Variabel DTSEN yang Diminta</h2>
            </div>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
                Pilih tema dataset di kotak sebelah kiri, lalu beri tanda centang (checklist) pada variabel data DTSEN yang dimohonkan OPD.
            </p>

            @include('partials.selector_variabel_dtsen', ['selectedVars' => old('variabel_dtsen', $tiket->variabel_dtsen ?? [])])
        </div>

        <!-- 5. PENUGASAN & DESKRIPSI PEKERJAAN (WALIDATA) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-briefcase"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Penugasan & Pelaksanaan Pekerjaan (Walidata)</h2>
                    <p class="section-head-desc">Informasi penugasan tim pelaksana dan tanggal disposisi layanan</p>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16">
                <div>
                    <label class="form-group-label">Tanggal Permintaan</label>
                    <input type="date" 
                           name="tgl_permintaan" 
                           class="form-text-input" 
                           value="{{ old('tgl_permintaan', $tiket->tgl_permintaan ? $tiket->tgl_permintaan->format('Y-m-d') : ($tiket->tgl_surat_permohonan ? $tiket->tgl_surat_permohonan->format('Y-m-d') : '')) }}">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Diterima Walidata</label>
                    <input type="date" 
                           name="tgl_diterima" 
                           class="form-text-input" 
                           value="{{ old('tgl_diterima', $tiket->tgl_diterima ? $tiket->tgl_diterima->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nomor Surat Tugas Tim</label>
                    <input type="text" 
                           name="no_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('no_surat_tugas', $tiket->no_surat_tugas) }}" 
                           placeholder="Contoh: 800/123-Diskominfo/2026">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Surat Tugas</label>
                    <input type="date" 
                           name="tgl_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('tgl_surat_tugas', $tiket->tgl_surat_tugas ? $tiket->tgl_surat_tugas->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Penanggung Jawab Kegiatan (Walidata)</label>
                    <input type="text" 
                           name="penanggung_jawab" 
                           class="form-text-input" 
                           value="{{ old('penanggung_jawab', $tiket->penanggung_jawab ?: 'Chobir Sabbaha, S.Kom., M.T.') }}" 
                           placeholder="Chobir Sabbaha, S.Kom., M.T.">
                </div>

                <div>
                    <label class="form-group-label">NIP Penanggung Jawab Walidata</label>
                    <input type="text" 
                           name="nip_penanggung_jawab" 
                           class="form-text-input" 
                           value="{{ old('nip_penanggung_jawab', $tiket->nip_penanggung_jawab ?: '19780218 200501 1 009') }}" 
                           placeholder="19780218 200501 1 009">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Tempat / Lokasi Pelaksanaan</label>
                    <input type="text" 
                           name="tempat_pelaksanaan" 
                           class="form-text-input" 
                           value="{{ old('tempat_pelaksanaan', $tiket->tempat_pelaksanaan ?: 'Gedung Diskominfo SP Provinsi Banten (KP3B Serang)') }}" 
                           placeholder="Gedung Diskominfo SP Provinsi Banten / Daring">
                </div>

                <div>
                    <label class="form-group-label">SOP Rujukan Layanan</label>
                    <input type="text" 
                           name="sop_rujukan" 
                           class="form-text-input" 
                           value="{{ old('sop_rujukan', $tiket->sop_rujukan ?: '555/07-SOP/DISKOMINFO SP/2026') }}" 
                           placeholder="555/07-SOP/DISKOMINFO SP/2026">
                </div>
            </div>

            <!-- Tim Pelaksana Walidata -->
            <div style="margin-top: 24px; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fi fi-rr-users-alt" style="color: #2563eb; font-size: 16px;"></i>
                    <span>Tim Pelaksana dari Walidata (Diskominfo SP Provinsi Banten)</span>
                </div>
                <p style="font-size: 12px; color: #64748b; margin: 0 0 14px 0;">Personel teknis dan verifikator dari Walidata yang bertugas memproses dan menyerahkan data.</p>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <!-- Pelaksana 1 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #1e40af; background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Pelaksana 1 (Utama)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_1" class="form-text-input" value="{{ old('pelaksana_1', $tiket->pelaksana_1 ?: 'Tia Fitria Saumi, S.Kom.') }}" placeholder="Tia Fitria Saumi, S.Kom.">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_1" class="form-text-input" value="{{ old('nip_pelaksana_1', $tiket->nip_pelaksana_1 ?: '19930812 202012 2 008') }}" placeholder="19930812 202012 2 008">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan</label>
                                <input type="text" name="jabatan_pelaksana_1" class="form-text-input" value="{{ old('jabatan_pelaksana_1', $tiket->jabatan_pelaksana_1 ?: 'Pranata Komputer Ahli Muda') }}" placeholder="Pranata Komputer Ahli Muda">
                            </div>
                        </div>
                    </div>

                    <!-- Pelaksana 2 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #1e40af; background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Pelaksana 2 (Statistisi / Verifikator)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_2" class="form-text-input" value="{{ old('pelaksana_2', $tiket->pelaksana_2 ?: 'Ahmad Fauzi, S.T.') }}" placeholder="Ahmad Fauzi, S.T.">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_2" class="form-text-input" value="{{ old('nip_pelaksana_2', $tiket->nip_pelaksana_2 ?: '19890514 201503 1 004') }}" placeholder="19890514 201503 1 004">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan</label>
                                <input type="text" name="jabatan_pelaksana_2" class="form-text-input" value="{{ old('jabatan_pelaksana_2', $tiket->jabatan_pelaksana_2 ?: 'Statistisi Ahli Pertama') }}" placeholder="Statistisi Ahli Pertama">
                            </div>
                        </div>
                    </div>

                    <!-- Pelaksana 3 (Opsional) -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Pelaksana 3 (Opsional)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_3" class="form-text-input" value="{{ old('pelaksana_3', $tiket->pelaksana_3) }}" placeholder="Nama Pelaksana 3">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_3" class="form-text-input" value="{{ old('nip_pelaksana_3', $tiket->nip_pelaksana_3) }}" placeholder="NIP Pelaksana 3">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan</label>
                                <input type="text" name="jabatan_pelaksana_3" class="form-text-input" value="{{ old('jabatan_pelaksana_3', $tiket->jabatan_pelaksana_3) }}" placeholder="Jabatan Pelaksana 3">
                            </div>
                        </div>
                    </div>

                    <!-- Pelaksana 4 (Opsional) -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Pelaksana 4 (Opsional)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_4" class="form-text-input" value="{{ old('pelaksana_4', $tiket->pelaksana_4) }}" placeholder="Nama Pelaksana 4">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_4" class="form-text-input" value="{{ old('nip_pelaksana_4', $tiket->nip_pelaksana_4) }}" placeholder="NIP Pelaksana 4">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan</label>
                                <input type="text" name="jabatan_pelaksana_4" class="form-text-input" value="{{ old('jabatan_pelaksana_4', $tiket->jabatan_pelaksana_4) }}" placeholder="Jabatan Pelaksana 4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim Pelaksana / Orang yang Hadir dari OPD -->
            <div style="margin-top: 20px; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                    <i class="fi fi-rr-user-check" style="color: #2563eb; font-size: 16px;"></i>
                    <span>Tim Pelaksana / Perwakilan OPD yang Hadir (Pihak Pemohon)</span>
                </div>
                <p style="font-size: 12px; color: #64748b; margin: 0 0 14px 0;">Daftar perwakilan atau pejabat/staf OPD yang hadir dalam pertemuan, serah terima data, dan tercatat di lembar formulir pemenuhan.</p>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <!-- OPD 1 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #1e40af; background: #dbeafe; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Perwakilan OPD 1 (PIC Utama / Penerima Data)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_opd_1" class="form-text-input" value="{{ old('pelaksana_opd_1', $tiket->pelaksana_opd_1 ?: ($tiket->nama_narahubung ?: $tiket->nama_pemohon)) }}" placeholder="Nama PIC Hadir">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_opd_1" class="form-text-input" value="{{ old('nip_pelaksana_opd_1', $tiket->nip_pelaksana_opd_1 ?: ($tiket->nip_narahubung ?: ($tiket->nip_pemohon ?: ''))) }}" placeholder="NIP PIC Hadir">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan / Peran</label>
                                <input type="text" name="jabatan_pelaksana_opd_1" class="form-text-input" value="{{ old('jabatan_pelaksana_opd_1', $tiket->jabatan_pelaksana_opd_1 ?: ($tiket->jabatan_narahubung ?: ($tiket->jabatan_pemohon ?: 'Narahubung / Staf OPD'))) }}" placeholder="Jabatan PIC Hadir">
                            </div>
                        </div>
                    </div>

                    <!-- OPD 2 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Perwakilan OPD 2 (Pendamping)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_opd_2" class="form-text-input" value="{{ old('pelaksana_opd_2', $tiket->pelaksana_opd_2) }}" placeholder="Nama Perwakilan Hadir 2 (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_opd_2" class="form-text-input" value="{{ old('nip_pelaksana_opd_2', $tiket->nip_pelaksana_opd_2) }}" placeholder="NIP (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan / Peran</label>
                                <input type="text" name="jabatan_pelaksana_opd_2" class="form-text-input" value="{{ old('jabatan_pelaksana_opd_2', $tiket->jabatan_pelaksana_opd_2) }}" placeholder="Jabatan (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- OPD 3 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Perwakilan OPD 3 (Pendamping)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_opd_3" class="form-text-input" value="{{ old('pelaksana_opd_3', $tiket->pelaksana_opd_3) }}" placeholder="Nama Perwakilan Hadir 3 (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_opd_3" class="form-text-input" value="{{ old('nip_pelaksana_opd_3', $tiket->nip_pelaksana_opd_3) }}" placeholder="NIP (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan / Peran</label>
                                <input type="text" name="jabatan_pelaksana_opd_3" class="form-text-input" value="{{ old('jabatan_pelaksana_opd_3', $tiket->jabatan_pelaksana_opd_3) }}" placeholder="Jabatan (opsional)">
                            </div>
                        </div>
                    </div>

                    <!-- OPD 4 -->
                    <div style="background: #ffffff; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <span style="display: inline-block; font-size: 11px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; margin-bottom: 8px;">Perwakilan OPD 4 (Pendamping)</span>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Nama & Gelar</label>
                                <input type="text" name="pelaksana_opd_4" class="form-text-input" value="{{ old('pelaksana_opd_4', $tiket->pelaksana_opd_4) }}" placeholder="Nama Perwakilan Hadir 4 (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">NIP</label>
                                <input type="text" name="nip_pelaksana_opd_4" class="form-text-input" value="{{ old('nip_pelaksana_opd_4', $tiket->nip_pelaksana_opd_4) }}" placeholder="NIP (opsional)">
                            </div>
                            <div>
                                <label class="form-group-label" style="font-size: 11px;">Jabatan / Peran</label>
                                <input type="text" name="jabatan_pelaksana_opd_4" class="form-text-input" value="{{ old('jabatan_pelaksana_opd_4', $tiket->jabatan_pelaksana_opd_4) }}" placeholder="Jabatan (opsional)">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 14px;">
                    <label class="form-group-label">Keterangan Kehadiran / Catatan Pertemuan</label>
                    <input type="text" name="keterangan_kehadiran" class="form-text-input" value="{{ old('keterangan_kehadiran', $tiket->keterangan_kehadiran) }}" placeholder="Contoh: Hadir tatap muka di KP3B Serang / Hadir secara daring melalui Zoom Meeting">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Jadwal Mulai Pekerjaan</label>
                    <input type="date" 
                           name="tgl_mulai" 
                           class="form-text-input" 
                           value="{{ old('tgl_mulai', $tiket->tgl_mulai ? $tiket->tgl_mulai->format('Y-m-d') : '') }}">
                </div>

                <div>
                    <label class="form-group-label">Jadwal Selesai Pekerjaan</label>
                    <input type="date" 
                           name="tgl_selesai" 
                           class="form-text-input" 
                           value="{{ old('tgl_selesai', $tiket->tgl_selesai ? $tiket->tgl_selesai->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div style="margin-top: 18px;">
                <label class="form-group-label">Deskripsi / Ruang Lingkup Pekerjaan</label>
                <textarea name="deskripsi_pekerjaan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Uraian pekerjaan pengolahan data mikro DTSEN...">{{ old('deskripsi_pekerjaan', $tiket->deskripsi_pekerjaan) }}</textarea>
            </div>
        </div>

        <!-- 6. PEMENUHAN DOKUMEN & MATRIKS PELAKSANAAN SOP -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-list-check"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Pemenuhan Dokumen & Matriks Pelaksanaan SOP</h2>
                    <p class="section-head-desc">Pemeriksaan pemenuhan instrumen data dan administrasi SOP DTSEN</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Dokumen KAK (Kerangka Acuan Kerja)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_kak" class="form-text-input" style="width: 140px;">
                            <option value="Ada" {{ old('status_kak', $extra['status_kak'] ?? 'Ada') === 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Tidak ada" {{ old('status_kak', $extra['status_kak'] ?? 'Ada') === 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_kak" class="form-text-input" value="{{ old('nomor_kak', $extra['nomor_kak'] ?? ($tiket->no_surat_permohonan ?: $tiket->no_surat_edaran)) }}" placeholder="Nomor / Keterangan KAK">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Penetapan Tim Pelaksana</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_tim" class="form-text-input" style="width: 140px;">
                            <option value="Ada" {{ old('status_tim', $extra['status_tim'] ?? 'Ada') === 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Tidak ada" {{ old('status_tim', $extra['status_tim'] ?? 'Ada') === 'Tidak ada' ? 'selected' : '' }}>Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_tim" class="form-text-input" value="{{ old('nomor_tim', $extra['nomor_tim'] ?? $tiket->no_surat_tugas) }}" placeholder="Nomor SK Tim Pelaksana">
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
                        <input type="text" name="nomor_ropa" class="form-text-input" value="{{ old('nomor_ropa', $extra['nomor_ropa'] ?? ('ROPA-DTSEN-' . date('Y'))) }}" placeholder="Nomor ROPA">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Dokumen DPIA DTSEN (Penilaian Dampak PDP)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_dpia" class="form-text-input" style="width: 140px;">
                            <option value="Sesuai" {{ old('status_dpia', $extra['status_dpia'] ?? 'Sesuai') === 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Belum Sesuai" {{ old('status_dpia', $extra['status_dpia'] ?? 'Sesuai') === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_dpia" class="form-text-input" value="{{ old('nomor_dpia', $extra['nomor_dpia'] ?? ('DPIA-DTSEN-' . date('Y'))) }}" placeholder="Nomor DPIA">
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
                        <input type="text" name="nomor_klasifikasi" class="form-text-input" value="{{ old('nomor_klasifikasi', $extra['nomor_klasifikasi'] ?? ('KLAS-DTSEN-' . date('Y'))) }}" placeholder="Nomor Klasifikasi">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Hak Akses DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_hak_akses" class="form-text-input" style="width: 140px;">
                            <option value="Diverifikasi" {{ old('status_hak_akses', $extra['status_hak_akses'] ?? 'Diverifikasi') === 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                            <option value="Belum Sesuai" {{ old('status_hak_akses', $extra['status_hak_akses'] ?? '') === 'Belum Sesuai' ? 'selected' : '' }}>Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_hak_akses" class="form-text-input" value="{{ old('nomor_hak_akses', $extra['nomor_hak_akses'] ?? ('HA-DTSEN-' . date('Y'))) }}" placeholder="Nomor Hak Akses">
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
                    <input type="text" name="no_bast" class="form-text-input" value="{{ old('no_bast', $extra['no_bast'] ?? '') }}" placeholder="Nomor register BAST">
                </div>
            </div>
        </div>

        <!-- 7. CATATAN PELAKSANA & HASIL PEKERJAAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-comment-alt"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Catatan Pelaksana & Hasil Pekerjaan</h2>
                    <p class="section-head-desc">Catatan hasil evaluasi Walidata dan ringkasan pekerjaan layanan bagi pakai</p>
                </div>
            </div>

            <div class="form-group-mb-16">
                <label class="form-group-label">Catatan Hasil Pemeriksaan / Pelaksana (Tampil di Sisi OPD)</label>
                <textarea name="catatan_admin" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Catatan walidata untuk instansi pemohon...">{{ old('catatan_admin', $tiket->catatan_admin) }}</textarea>
            </div>

            <div style="margin-top: 18px;">
                <label class="form-group-label">Hasil Pelaksanaan Pekerjaan (Format SOP)</label>
                <textarea name="hasil_pekerjaan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Ringkasan hasil pelaksanaan layanan bagi pakai...">{{ old('hasil_pekerjaan', $tiket->hasil_pekerjaan) }}</textarea>
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

<script>
// Data berkas server awal
const serverFiles = {
    permohonan: "{{ $tiket->nama_file_surat_permohonan ?: ($tiket->nama_file_asli ?: '') }}",
    kak: "{{ $tiket->nama_file_kak ?: '' }}",
    sktm: "{{ $tiket->nama_file_sktm ?: '' }}",
    ropa: "{{ $tiket->nama_file_ropa ?: '' }}",
    dpia: "{{ $tiket->nama_file_dpia ?: '' }}",
    klasifikasi: "{{ $tiket->nama_file_klasifikasi_dtsen ?: '' }}",
    hakAkses: "{{ $tiket->nama_file_hak_akses ?: '' }}"
};

function handleFileChange(input, titleId, subId, btnDelId, key) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        const titleEl = document.getElementById(titleId);
        const subEl = document.getElementById(subId);
        const btnDel = btnDelId ? document.getElementById(btnDelId) : null;
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #0b57d0; font-weight: 700;">✓ Berkas Baru: ${file.name}</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `<span class="dtsen-ready-badge">Berkas pengganti terpilih (${sizeMb} MB)</span>`;
        }
        if (btnDel) {
            btnDel.style.display = 'inline-flex';
        }
    }
}

function clearNewFile(inputId, titleId, subId, btnDelId, key, defaultTitle, defaultSub) {
    const input = document.getElementById(inputId);
    const titleEl = document.getElementById(titleId);
    const subEl = document.getElementById(subId);
    const btnDel = document.getElementById(btnDelId);
    if (input) {
        input.value = '';
    }
    if (btnDel) {
        btnDel.style.display = 'none';
    }
    const serverName = serverFiles[key];
    if (serverName && serverName !== '') {
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #059669; font-weight: 700;">✓ Berkas Ada: ${serverName}</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `Klik di sini untuk mengunggah berkas pengganti (PDF, DOC, DOCX Maks 500MB)`;
        }
    } else {
        if (titleEl) {
            titleEl.innerHTML = defaultTitle;
        }
        if (subEl) {
            subEl.innerHTML = defaultSub;
        }
    }
}

function toggleHapusServerFile(key, inputHapusId, titleId, subId, btnHapusId) {
    const inputHapus = document.getElementById(inputHapusId);
    const titleEl = document.getElementById(titleId);
    const subEl = document.getElementById(subId);
    const btnHapus = document.getElementById(btnHapusId);
    const serverName = serverFiles[key];

    if (!inputHapus) return;

    if (inputHapus.value === '0') {
        inputHapus.value = '1';
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #dc2626; font-weight: 700;">✕ Berkas akan dihapus saat disimpan</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `<span style="color: #e11d48; font-size: 11.5px;">Klik "Batal Hapus" jika ingin mempertahankan berkas ini</span>`;
        }
        if (btnHapus) {
            btnHapus.innerHTML = `<i class="fi fi-rr-undo"></i> Batal Hapus`;
            btnHapus.style.background = '#f1f5f9';
            btnHapus.style.color = '#334155';
            btnHapus.style.borderColor = '#cbd5e1';
        }
    } else {
        inputHapus.value = '0';
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #059669; font-weight: 700;">✓ Berkas Ada: ${serverName}</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `Klik untuk ganti berkas (PDF, DOC, DOCX Maks 500MB)`;
        }
        if (btnHapus) {
            btnHapus.innerHTML = `<i class="fi fi-rr-trash"></i> Hapus Berkas`;
            btnHapus.style.background = '#fff1f2';
            btnHapus.style.color = '#e11d48';
            btnHapus.style.borderColor = '#fecdd3';
        }
    }
}
</script>
@endsection

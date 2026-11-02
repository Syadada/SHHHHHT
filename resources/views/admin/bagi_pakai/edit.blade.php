@extends('layouts.admin')

@section('title', 'Edit Permintaan Layanan #' . $tiket->nomor_tiket . ' — Bagi Pakai DTSEN')

@section('content')
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
                        <i class="fi fi-rr-document"></i> SOP: {{ $noSop }}
                    </span>
                </div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">
                    Perbarui data instansi, variabel data, berkas permohonan, atau status tahapan tiket (ID Unik: <strong>#{{ $tiket->nomor_tiket }}</strong>).
                </p>
            </div>
            <div>
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

        <!-- 1. IDENTITAS INSTANSI & SURAT PERMOHONAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Identitas Instansi & Status Permintaan</h2>
                    <p class="section-head-desc">Informasi instansi pemohon, status layanan, dan rujukan surat edaran</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <!-- Nama Instansi -->
                <div>
                    <label class="form-group-label">
                        Nama Instansi / Lembaga Pemohon <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="nama_instansi" 
                           id="nama_instansi"
                           class="form-text-input" 
                           value="{{ old('nama_instansi', $tiket->nama_instansi) }}" 
                           required>
                    <div class="form-help-hint">Nama Perangkat Daerah (OPD) pemohon layanan.</div>
                </div>

                <!-- Status Layanan -->
                <div>
                    <label class="form-group-label">
                        Status Permintaan Layanan <span class="form-required">*</span>
                    </label>
                    <select name="status" class="form-text-input" style="font-size: 13px; padding: 8px 12px;">
                        <option value="DIPROSES" {{ old('status', $tiket->status) === 'DIPROSES' ? 'selected' : '' }}>Sedang Berjalan (DIPROSES)</option>
                        <option value="SELESAI" {{ old('status', $tiket->status) === 'SELESAI' ? 'selected' : '' }}>Selesai 100% (SELESAI)</option>
                        <option value="PERLU_PERBAIKAN" {{ old('status', $tiket->status) === 'PERLU_PERBAIKAN' ? 'selected' : '' }}>Perlu Perbaikan (REVISI)</option>
                    </select>
                    <div class="form-help-hint">Status pemrosesan permohonan bagi pakai data.</div>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <!-- No Surat Edaran -->
                <div>
                    <label class="form-group-label">
                        Nomor Surat Edaran / Permohonan <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="no_surat_edaran" 
                           class="form-text-input" 
                           value="{{ old('no_surat_edaran', $tiket->no_surat_edaran) }}" 
                           required>
                </div>

                <!-- PIC Pemohon -->
                <div>
                    <label class="form-group-label">Nama PIC / Penanggung Jawab</label>
                    <input type="text" 
                           name="nama_pemohon" 
                           id="nama_pemohon"
                           class="form-text-input" 
                           value="{{ old('nama_pemohon', $tiket->nama_pemohon) }}">
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <!-- Kontak Telepon / WhatsApp -->
                <div>
                    <label class="form-group-label">Kontak Telepon / WhatsApp</label>
                    <input type="text" 
                           name="kontak_pemohon" 
                           id="kontak_pemohon"
                           class="form-text-input" 
                           value="{{ old('kontak_pemohon', $tiket->kontak_pemohon) }}" 
                           placeholder="Contoh: 081234567890">
                </div>

                <!-- Email Pemohon -->
                <div>
                    <label class="form-group-label">Alamat Email Resmi</label>
                    <input type="email" 
                           name="email_pemohon" 
                           id="email_pemohon"
                           class="form-text-input" 
                           value="{{ old('email_pemohon', $tiket->email_pemohon) }}" 
                           placeholder="nama@instansi.go.id">
                </div>
            </div>

            <!-- Tujuan Pemanfaatan Data -->
            <div style="margin-top: 18px;">
                <label class="form-group-label">Tujuan Pemanfaatan Data DTSEN</label>
                <textarea name="tujuan_pemanfaatan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Jelaskan secara singkat rencana pemanfaatan data DTSEN untuk program instansi Anda...">{{ old('tujuan_pemanfaatan', $tiket->tujuan_pemanfaatan) }}</textarea>
            </div>
        </div>

        <!-- 2. VARIABEL DTSEN YANG DIMINTA -->
        <div class="form-section-card">
            <div class="section-head-box-tight">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="section-head-icon">
                        <i class="fi fi-rr-list-check"></i>
                    </div>
                    <div>
                        <h2 class="section-head-title">
                            Variabel DTSEN yang Diminta <span class="form-required">*</span>
                        </h2>
                        <p class="section-head-desc">Atribut atau kolom data mikro DTSEN yang dimohonkan</p>
                    </div>
                </div>

                <button type="button" onclick="tambahBarisVariabel()" class="btn-pill-outline revisi-btn-add">
                    <i class="fi fi-rr-plus"></i> + Tambah Kolom Isian
                </button>
            </div>

            <p style="font-size: 13px; color: #475569; margin-bottom: 16px; line-height: 1.5;">
                Tuliskan atribut atau variabel data mikro DTSEN yang Anda butuhkan sesuai lampiran Surat Edaran resmi:
            </p>

            <div id="containerVariabel">
                @php
                    $vars = old('variabel_dtsen', is_array($tiket->variabel_dtsen) ? $tiket->variabel_dtsen : []);
                    if (count($vars) < 1) $vars = ['NIK, Nama Kepala Keluarga, Alamat Lengkap'];
                @endphp

                @foreach($vars as $idx => $v)
                    <div class="var-row-item">
                        <div class="var-num-chip">{{ $idx + 1 }}</div>
                        <input type="text" 
                               name="variabel_dtsen[]" 
                               class="form-text-input" 
                               value="{{ $v }}" 
                               placeholder="Nama atribut variabel DTSEN..." 
                               required>
                        @if(count($vars) > 1)
                            <button type="button" onclick="hapusBarisVariabel(this)" class="btn-del-var" title="Hapus baris variabel ini"><i class="fi fi-rr-cross-small"></i></button>
                        @else
                            <div class="var-spacer-col"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. UNGGAH BERKAS LAMPIRAN SURAT EDARAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-document"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Lampiran Surat Edaran / Permohonan Resmi</h2>
                    <p class="section-head-desc">Unggah scan surat dinas bertanda tangan jika ada pembaruan dokumen (Maks. 50MB)</p>
                </div>
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

            <!-- Catatan Admin -->
            <div style="margin-top: 20px;">
                <label class="form-group-label">Catatan Admin / Verifikator Walidata</label>
                <textarea name="catatan_admin" 
                          rows="2" 
                          class="form-text-input" 
                          placeholder="Catatan hasil verifikasi atau arahan perbaikan untuk OPD...">{{ old('catatan_admin', $tiket->catatan_admin) }}</textarea>
            </div>
        </div>

        <!-- TOMBOL AKSI SUBMIT -->
        <div class="submit-actions-row">
            <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline btn-cancel-action">
                &larr; Batal & Kembali ke Daftar
            </a>

            <button type="submit" class="btn-pill-primary btn-submit-action">
                <i class="fi fi-rr-check"></i>
                <span>Simpan Perubahan Permintaan Layanan</span>
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
                   placeholder="Variabel ${count}" 
                   required>
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
                input.placeholder = `Variabel ${idx + 1}`;
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

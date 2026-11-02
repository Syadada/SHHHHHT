@extends('layouts.admin')

@section('title', 'Buat Permintaan Layanan Bagi Pakai DTSEN — SOP: 555/07-SOP/DISKOMINFO SP/2026')

@section('content')
<div class="form-create-shell">

    <!-- Breadcrumb Navigasi Admin -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}">Layanan Bagi Pakai DTSEN</a>
        <span class="separator">/</span>
        <span class="current">Buat Permintaan Layanan</span>
    </nav>

    <!-- Header info banner -->
    <div class="dash-card form-group-mb-20">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                    <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fi fi-rr-share" style="color: var(--blue-primary);"></i>
                        <span>Layanan Bagi Pakai DTSEN — Buat Permintaan Baru</span>
                    </h1>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="fi fi-rr-document"></i> SOP: {{ $noSop }}
                    </span>
                </div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">
                    Fasilitasi pembuatan permohonan layanan bagi pakai data DTSEN atas nama OPD yang tidak dapat membuat tiket mandiri. Tiket ini menjadi <strong>ID Unik</strong> proses bagi pakai data.
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

    <form action="{{ route('admin.bagi-pakai-dtsen.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. IDENTITAS INSTANSI & SURAT PERMOHONAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Identitas Instansi & Surat Permohonan</h2>
                    <p class="section-head-desc">Informasi dasar instansi pemohon dan rujukan surat edaran resmi</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <!-- Nama Instansi (Bisa pilih dari akun OPD atau ketik manual) -->
                <div>
                    <label class="form-group-label">
                        Nama Instansi / Lembaga Pemohon <span class="form-required">*</span>
                    </label>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <select id="opd_select" class="form-text-input" onchange="fillOpdData(this)" style="font-size: 13px; padding: 8px 12px;">
                            <option value="">-- Pilih dari Akun Terdaftar atau Ketik Manual di Bawah --</option>
                            @foreach($opdUsers as $opd)
                                <option value="{{ $opd->nama_instansi }}" 
                                        data-pic="{{ $opd->name }}" 
                                        data-kontak="{{ $opd->nomor_kontak }}" 
                                        data-email="{{ $opd->email }}">
                                    {{ $opd->nama_instansi ?: $opd->username }} ({{ $opd->username }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" 
                               name="nama_instansi" 
                               id="nama_instansi"
                               class="form-text-input" 
                               value="{{ old('nama_instansi') }}" 
                               placeholder="Contoh: Dinas Sosial Kabupaten / Bappeda" 
                               required>
                    </div>
                    <div class="form-help-hint">Dapat memilih dari akun dinas terdaftar atau mengetik langsung.</div>
                </div>

                <!-- No Surat Edaran -->
                <div>
                    <label class="form-group-label">
                        Nomor Surat Edaran / Permohonan <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="no_surat_edaran" 
                           class="form-text-input" 
                           value="{{ old('no_surat_edaran') }}" 
                           placeholder="Contoh: SE/400.9/081/DINSOS/2026" 
                           required>
                    <div class="form-help-hint">Nomor surat dinas resmi yang diajukan ke Walidata.</div>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <!-- PIC Pemohon -->
                <div>
                    <label class="form-group-label">Nama PIC / Penanggung Jawab</label>
                    <input type="text" 
                           name="nama_pemohon" 
                           id="nama_pemohon"
                           class="form-text-input" 
                           value="{{ old('nama_pemohon') }}" 
                           placeholder="Nama Pejabat / Staf Pengelola Data">
                </div>

                <!-- Kontak WhatsApp & Email -->
                <div>
                    <label class="form-group-label">Kontak Telepon / WhatsApp</label>
                    <input type="text" 
                           name="kontak_pemohon" 
                           id="kontak_pemohon"
                           class="form-text-input" 
                           value="{{ old('kontak_pemohon') }}" 
                           placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Email Pemohon -->
            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Alamat Email Resmi</label>
                <input type="email" 
                       name="email_pemohon" 
                       id="email_pemohon"
                       class="form-text-input" 
                       value="{{ old('email_pemohon') }}" 
                       placeholder="nama@instansi.go.id">
            </div>

            <!-- Tujuan Pemanfaatan Data -->
            <div style="margin-top: 18px;">
                <label class="form-group-label">Tujuan Pemanfaatan Data DTSEN</label>
                <textarea name="tujuan_pemanfaatan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Jelaskan secara singkat rencana pemanfaatan data DTSEN untuk program instansi Anda...">{{ old('tujuan_pemanfaatan') }}</textarea>
            </div>
        </div>

        <!-- 2. VARIABEL DTSEN YANG DIMINTA (MINIMAL 5 FORM) -->
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
                        <p class="section-head-desc">Minimal 5 kolom isian variabel yang dibutuhkan</p>
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
                    $defaultVars = ['Variabel 1', 'Variabel 2', 'Variabel 3', 'Variabel 4', 'Variabel 5'];
                    $oldVars = old('variabel_dtsen', $defaultVars);
                    if (count($oldVars) < 5) {
                        for ($i = count($oldVars); $i < 5; $i++) {
                            $oldVars[] = 'Variabel ' . ($i + 1);
                        }
                    }
                @endphp

                @foreach($oldVars as $idx => $v)
                    <div class="var-row-item">
                        <div class="var-num-chip">{{ $idx + 1 }}</div>
                        <input type="text" 
                               name="variabel_dtsen[]" 
                               class="form-text-input" 
                               value="{{ $v }}" 
                               placeholder="Contoh: NIK, Nama, Alamat, Status Kesejahteraan..." 
                               required>
                        @if($idx >= 5)
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
                    <p class="section-head-desc">Unggah scan surat dinas bertanda tangan (Format: PDF / DOC, Maksimal 50MB)</p>
                </div>
            </div>

            <div class="upload-drop-zone" onclick="document.getElementById('inputSuratFile').click()">
                <div class="upload-icon-lg">
                    <i class="fi fi-rr-cloud-upload-alt"></i>
                </div>
                <div class="upload-title" id="fileDisplayTitle">
                    Klik untuk memilih berkas surat permohonan dinas
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
                <i class="fi fi-rr-paper-plane"></i>
                <span>Terbitkan Permintaan Bagi Pakai DTSEN Sekarang</span>
            </button>
        </div>

    </form>

</div>

@section('scripts')
<script>
    function fillOpdData(select) {
        const selected = select.options[select.selectedIndex];
        if (selected && selected.value) {
            document.getElementById('nama_instansi').value = selected.value;
            if (selected.getAttribute('data-pic')) {
                document.getElementById('nama_pemohon').value = selected.getAttribute('data-pic');
            }
            if (selected.getAttribute('data-kontak')) {
                document.getElementById('kontak_pemohon').value = selected.getAttribute('data-kontak');
            }
            if (selected.getAttribute('data-email')) {
                document.getElementById('email_pemohon').value = selected.getAttribute('data-email');
            }
        }
    }

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
            document.getElementById('fileDisplaySubtitle').innerHTML = `<span class="upload-ready-text"><i class="fi fi-rr-check"></i> Berkas siap diunggah</span>`;
        }
    }
</script>
@endsection
@endsection

@extends('layouts.opd')

@section('title', 'Permintaan Bagi Pakai DTSEN — Portal Perangkat Daerah')
@section('page_title', 'Permintaan Bagi Pakai')
@section('page_subtitle', 'Pengajuan Permohonan Bagi Pakai Data Terpadu Sosial Ekonomi Nasional')

@section('content')
<div class="form-create-shell">

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

    <form action="{{ route('formulir.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. IDENTITAS INSTANSI & SURAT EDARAN -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Identitas Instansi & Surat Permohonan</h2>
                    <p class="section-head-desc">Informasi dasar instansi pemohon dan rujukan surat edaran</p>
                </div>
            </div>

            <div class="form-2col-grid">
                <!-- Nama Instansi (Auto-filled) -->
                <div>
                    <label class="form-group-label">
                        Nama Instansi / Lembaga Pemohon <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="nama_instansi" 
                           class="form-text-input" 
                           value="{{ old('nama_instansi', Auth::user()->nama_instansi ?: Auth::user()->name) }}" 
                           required>
                    <div class="form-help-hint">Otomatis terisi sesuai akun login dinas Anda.</div>
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
                           class="form-text-input" 
                           value="{{ old('nama_pemohon', Auth::user()->name) }}" 
                           placeholder="Nama Pejabat / Staf Pengelola Data">
                </div>

                <!-- Kontak WhatsApp & Email -->
                <div>
                    <label class="form-group-label">Kontak Telepon / WhatsApp</label>
                    <input type="text" 
                           name="kontak_pemohon" 
                           class="form-text-input" 
                           value="{{ old('kontak_pemohon', Auth::user()->nomor_kontak) }}" 
                           placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Email Pemohon -->
            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Alamat Email Resmi</label>
                <input type="email" 
                       name="email_pemohon" 
                       class="form-text-input" 
                       value="{{ old('email_pemohon', Auth::user()->email) }}" 
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
                            <button type="button" onclick="hapusBarisVariabel(this)" class="btn-del-var" title="Hapus baris variabel ini">✕</button>
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
            <a href="{{ route('opd.dashboard') }}" class="btn-pill-outline btn-cancel-action">
                &larr; Batal & Kembali ke Dashboard
            </a>

            <button type="submit" class="btn-pill-primary btn-submit-action">
                <i class="fi fi-rr-paper-plane"></i>
                <span>Ajukan Permintaan Bagi Pakai Sekarang</span>
            </button>
        </div>

    </form>

</div>

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
            <button type="button" onclick="hapusBarisVariabel(this)" class="btn-del-var" title="Hapus baris variabel ini">✕</button>
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

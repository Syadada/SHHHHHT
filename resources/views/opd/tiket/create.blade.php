@extends('layouts.opd')

@section('title', 'Permintaan Bagi Pakai DTSEN — Portal Perangkat Daerah')
@section('page_title', 'Layanan')
@section('page_subtitle', 'Pengajuan Permohonan Bagi Pakai Data Terpadu Sosial Ekonomi Nasional')

@section('content')
<div class="form-create-shell">

    <!-- Flash Alert Error jika Ada -->
    @if(isset($errors) && $errors->any())
        <div class="form-error-banner" style="margin-bottom: 24px; padding: 16px 20px; background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 12px;">
            <div class="form-error-title" style="font-weight: 700; color: #dc2626; font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-cross-circle"></i> Mohon Periksa Isian Anda:
            </div>
            <ul class="form-error-list" style="margin: 0; padding-left: 20px; color: #b91c1c; font-size: 13px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('formulir.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- IDENTITAS INSTANSI & NARAHUBUNG -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-building"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Identitas Instansi & Narahubung</h2>
                    <p class="section-head-desc">Identitas pemohon resmi dan kontak narahubung yang bertanggung jawab</p>
                </div>
            </div>

            <!-- Nama Instansi (Auto-filled) -->
            <div class="form-group-mb-16">
                <label class="form-group-label">
                    Nama Instansi / Lembaga Pemohon
                </label>
                <input type="text" 
                       name="nama_instansi" 
                       class="form-text-input" 
                       value="{{ old('nama_instansi', Auth::user()->nama_instansi ?: Auth::user()->name) }}" 
                       readonly 
                       placeholder="Nama instansi terisi otomatis"
                       style="background: #f8fafc; font-weight: 700; color: #0f172a; cursor: not-allowed;">
            </div>

            <!-- Narahubung (Required) -->
            <div class="form-2col-grid" style="margin-top: 14px;">
                <!-- Nama Narahubung -->
                <div>
                    <label class="form-group-label">
                        Nama Narahubung <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="nama_narahubung" 
                           class="form-text-input" 
                           value="{{ old('nama_narahubung') }}" 
                           placeholder="Nama lengkap narahubung pemohon" 
                           required>
                </div>

                <!-- Nomor Narahubung -->
                <div>
                    <label class="form-group-label">
                        Nomor Narahubung <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="no_narahubung" 
                           class="form-text-input" 
                           value="{{ old('no_narahubung') }}" 
                           placeholder="Nomor WhatsApp aktif narahubung (contoh: 081234567890)" 
                           required>
                </div>
            </div>
        </div>

        <!-- Berkas Dokumen Permintaan -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-document"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Berkas Dokumen Permintaan</h2>
                    <p class="section-head-desc">Unggah berkas kelengkapan administrasi permohonan layanan bagi pakai</p>
                </div>
            </div>

            <div class="dtsen-filebox-grid">
                
                <!-- 1. Surat Permohonan (File Box & Input Fields) Required - Spans Full Width at Top -->
                <div class="dtsen-filebox-card dtsen-filebox-permohonan">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Surat Permohonan
                        </div>
                        <span class="dtsen-badge-req">Wajib</span>
                    </div>
                    <div class="dtsen-permohonan-body">
                        <!-- File Dropzone Permohonan -->
                        <div class="dtsen-file-dropzone dtsen-dropzone-permohonan" onclick="document.getElementById('inputPermohonan').click()">
                            <i class="fi fi-rr-cloud-upload-alt dtsen-dropzone-icon"></i>
                            <div class="dtsen-dropzone-title" id="permohonanTitle">Pilih Berkas Surat Permohonan</div>
                            <div class="dtsen-dropzone-sub" id="permohonanSub">PDF, DOC, DOCX (Maks 50MB)</div>
                            <input type="file" 
                                   name="file_surat_permohonan" 
                                   id="inputPermohonan" 
                                   class="d-none" 
                                   accept=".pdf,.doc,.docx" 
                                   required
                                   onchange="handleFileChange(this, 'permohonanTitle', 'permohonanSub')">
                        </div>

                        <!-- 3 Input Surat Permohonan: Nomor Surat, Tanggal, Perihal (Tanpa Nomor) -->
                        <div class="dtsen-permohonan-meta-inputs">
                            <div>
                                <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                    Nomor Surat
                                </label>
                                <input type="text" 
                                       name="no_surat_permohonan" 
                                       class="form-text-input" 
                                       value="{{ old('no_surat_permohonan', old('no_surat_edaran')) }}" 
                                       placeholder="Nomor surat permohonan resmi"
                                       style="padding: 8px 12px; font-size: 13px;">
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                        Tanggal Surat
                                    </label>
                                    <input type="date" 
                                           name="tgl_surat_permohonan" 
                                           class="form-text-input" 
                                           value="{{ old('tgl_surat_permohonan', date('Y-m-d')) }}"
                                           style="padding: 8px 12px; font-size: 13px;">
                                </div>
                                <div>
                                    <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                        Perihal
                                    </label>
                                    <input type="text" 
                                           name="perihal" 
                                           class="form-text-input" 
                                           value="{{ old('perihal') }}" 
                                           placeholder="Perihal surat permohonan"
                                           style="padding: 8px 12px; font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. KAK (File Box) Required -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            KAK
                        </div>
                        <span class="dtsen-badge-req">Wajib</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputKak').click()">
                        <i class="fi fi-rr-cloud-upload-alt dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="kakTitle">Pilih Berkas KAK</div>
                        <div class="dtsen-dropzone-sub" id="kakSub">PDF, DOC, DOCX (Maks 50MB)</div>
                        <input type="file" 
                               name="file_kak" 
                               id="inputKak" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               required
                               onchange="handleFileChange(this, 'kakTitle', 'kakSub')">
                    </div>
                </div>

                <!-- 3. SK Tim Pelaksana (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            SK Tim Pelaksana
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputSktm').click()">
                        <i class="fi fi-rr-file dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="sktmTitle">Pilih Berkas SK Tim Pelaksana</div>
                        <div class="dtsen-dropzone-sub" id="sktmSub">PDF, DOC, DOCX, ZIP (Maks 50MB)</div>
                        <input type="file" 
                               name="file_sktm" 
                               id="inputSktm" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.zip" 
                               onchange="handleFileChange(this, 'sktmTitle', 'sktmSub')">
                    </div>
                </div>

                <!-- 4. ROPA (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            ROPA
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputRopa').click()">
                        <i class="fi fi-rr-document dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="ropaTitle">Pilih Berkas ROPA</div>
                        <div class="dtsen-dropzone-sub" id="ropaSub">PDF, DOC, DOCX, XLSX (Maks 50MB)</div>
                        <input type="file" 
                               name="file_ropa" 
                               id="inputRopa" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.xlsx,.xls" 
                               onchange="handleFileChange(this, 'ropaTitle', 'ropaSub')">
                    </div>
                </div>

                <!-- 5. DPIA (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            DPIA
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputDpia').click()">
                        <i class="fi fi-rr-shield-check dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="dpiaTitle">Pilih Berkas DPIA</div>
                        <div class="dtsen-dropzone-sub" id="dpiaSub">PDF, DOC, DOCX, XLSX (Maks 50MB)</div>
                        <input type="file" 
                               name="file_dpia" 
                               id="inputDpia" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.xlsx,.xls" 
                               onchange="handleFileChange(this, 'dpiaTitle', 'dpiaSub')">
                    </div>
                </div>

            </div>
        </div>

        <!-- Variabel DTSEN yang Diminta -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-list-check"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Variabel DTSEN</h2>
                </div>
            </div>

            @include('partials.selector_variabel_dtsen', ['selectedVars' => old('variabel_dtsen', [])])
        </div>

        <!-- TOMBOL AKSI SUBMIT -->
        <div class="submit-actions-row">
            <a href="{{ route('opd.tiket.index') }}" class="btn-pill-outline btn-cancel-action">
                &larr; Batal & Kembali ke Layanan
            </a>

            <button type="submit" class="btn-pill-primary btn-submit-action">
                <i class="fi fi-rr-paper-plane"></i>
                <span>Ajukan Permintaan Bagi Pakai</span>
            </button>
        </div>

    </form>

</div>

<style>
.dtsen-filebox-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-top: 14px;
}
@media (max-width: 820px) {
    .dtsen-filebox-grid {
        grid-template-columns: 1fr;
    }
}
.dtsen-filebox-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
}
.dtsen-filebox-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}
.dtsen-filebox-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.dtsen-filebox-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.dtsen-badge-req {
    font-size: 10.5px;
    font-weight: 700;
    background: #fee2e2;
    color: #dc2626;
    padding: 2px 7px;
    border-radius: 6px;
    text-transform: uppercase;
}
.dtsen-badge-opt {
    font-size: 10.5px;
    font-weight: 600;
    background: #f1f5f9;
    color: #64748b;
    padding: 2px 7px;
    border-radius: 6px;
}
.dtsen-file-dropzone {
    border: 1.5px dashed #cbd5e1;
    border-radius: 10px;
    padding: 18px 14px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 110px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
}
.dtsen-file-dropzone:hover {
    background: #f1f5f9;
    border-color: #0b57d0;
}
.dtsen-dropzone-icon {
    font-size: 24px;
    color: #0b57d0;
    margin-bottom: 6px;
    display: block;
}
.dtsen-dropzone-title {
    font-size: 12.5px;
    font-weight: 600;
    color: #1e293b;
    word-break: break-all;
}
.dtsen-dropzone-sub {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 3px;
}
.dtsen-ready-badge {
    color: #16a34a;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.dtsen-filebox-permohonan {
    grid-column: 1 / -1;
}
.dtsen-permohonan-body {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 18px;
    align-items: stretch;
}
@media (max-width: 820px) {
    .dtsen-permohonan-body {
        grid-template-columns: 1fr;
        gap: 14px;
    }
}
.dtsen-dropzone-permohonan {
    height: 100%;
    min-height: 130px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 16px;
    box-sizing: border-box;
}
.dtsen-permohonan-meta-inputs {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 10px;
}
</style>

<script>
function handleFileChange(input, titleId, subId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        const titleEl = document.getElementById(titleId);
        const subEl = document.getElementById(subId);
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #0b57d0;">${file.name}</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `<span class="dtsen-ready-badge">✓ Berkas terpilih (${sizeMb} MB)</span>`;
        }
    }
}
</script>
@endsection

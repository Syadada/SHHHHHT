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
                
                <!-- Surat KAK (File Box) Required -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Surat KAK
                        </div>
                        <span class="dtsen-badge-req">Wajib</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputKak').click()">
                        <i class="fi fi-rr-cloud-upload-alt dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="kakTitle">Pilih Berkas Surat KAK</div>
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

                <!-- Surat Permohonan (File Box) Required -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Surat Permohonan
                        </div>
                        <span class="dtsen-badge-req">Wajib</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputPermohonan').click()">
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
                </div>

                <!-- ROPA (File Box) Opsional -->
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

                <!-- DPIA (File Box) Opsional -->
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

                <!-- SKTM (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            SKTM
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputSktm').click()">
                        <i class="fi fi-rr-file dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="sktmTitle">Pilih Berkas SKTM</div>
                        <div class="dtsen-dropzone-sub" id="sktmSub">PDF, DOC, DOCX, ZIP (Maks 50MB)</div>
                        <input type="file" 
                               name="file_sktm" 
                               id="inputSktm" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.zip" 
                               onchange="handleFileChange(this, 'sktmTitle', 'sktmSub')">
                    </div>
                </div>

            </div>
        </div>

        <!-- Variabel DTSEN yang Diminta (Dipisahkan & Opsional) -->
        <div class="form-section-card">
            <div class="section-head-box-tight" style="margin-bottom: 6px;">
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; flex-wrap: wrap; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="section-head-icon">
                            <i class="fi fi-rr-list-check"></i>
                        </div>
                        <div>
                            <h2 class="section-head-title">
                                Rincian Variabel DTSEN yang Diminta
                            </h2>
                            <p class="section-head-desc">Pilihan atribut data mikro keluarga dan anggota keluarga</p>
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 11px; font-weight: 700; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; border: 1px solid #cbd5e1;">
                            Opsional (Dapat Dilengkapi Nanti)
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin: 8px 0 14px; padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; font-size: 12.5px; color: #1e40af; line-height: 1.5; display: flex; align-items: center; gap: 10px;">
                <i class="fi fi-rr-info" style="font-size: 16px; flex-shrink: 0;"></i>
                <span><strong>Catatan Opsional:</strong> Pemilihan variabel bersifat opsional. Anda dapat mencentang variabel sekarang, atau dapat langsung mengajukan permohonan terlebih dahulu dan melengkapi variabel nanti saat verifikasi bersama Walidata.</span>
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
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 14px;
}
.dtsen-filebox-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
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
    padding: 18px 12px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
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

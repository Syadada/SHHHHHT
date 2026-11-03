@extends('layouts.opd')

@section('title', 'Perbaikan Permohonan Bagi Pakai DTSEN — Portal Perangkat Daerah')
@section('page_title', 'Layanan')
@section('page_subtitle', 'Perbaikan Dokumen Permohonan Layanan Bagi Pakai DTSEN')

@section('content')
<div class="form-create-shell">

    <!-- Banner Pemberitahuan Status Perbaikan & Catatan Admin Walidata -->
    <div style="background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 14px; padding: 20px 24px; margin-bottom: 24px;">
        <div style="display: flex; align-items: flex-start; gap: 14px; flex-wrap: wrap;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fi fi-rr-exclamation"></i>
            </div>
            <div style="flex: 1; min-width: 280px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                    <span style="font-size: 16px; font-weight: 800; color: #991b1b;">
                        PERBAIKAN PERMOHONAN DIPERLUKAN
                    </span>
                    <span class="ticket-tag-chip" style="background: #fee2e2; color: #991b1b; font-weight: 700; border: 1px solid #fca5a5;">
                        #{{ $tiket->nomor_tiket }}
                    </span>
                </div>
                <div style="font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 12px;">
                    Admin Walidata meminta perbaikan pada kelengkapan berkas atau data permohonan tiket Anda. Silakan baca catatan koreksi di bawah, lakukan perbaikan isian formulir atau unggah berkas pengganti yang telah disesuaikan, kemudian klik <strong>Kirim Ulang Permohonan ke Walidata</strong>.
                </div>
                <div style="padding: 12px 16px; background: #ffffff; border: 1px solid #fda4af; border-radius: 10px; font-size: 13px; color: #991b1b; line-height: 1.5;">
                    <strong style="color: #b91c1c;"><i class="fi fi-rr-comment-alt"></i> Catatan Koreksi Walidata:</strong><br>
                    {{ $tiket->catatan_admin }}
                </div>
            </div>
        </div>
    </div>

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

    <form action="{{ route('opd.tiket.update', $tiket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. IDENTITAS INSTANSI & NARAHUBUNG -->
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

            <!-- Nama Instansi (Auto-filled & Readonly) -->
            <div class="form-group-mb-16">
                <label class="form-group-label">
                    Nama Instansi / Lembaga Pemohon
                </label>
                <input type="text" 
                       name="nama_instansi" 
                       class="form-text-input" 
                       value="{{ old('nama_instansi', $tiket->nama_instansi) }}" 
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
                           value="{{ old('nama_narahubung', $tiket->nama_narahubung ?: $tiket->nama_pemohon) }}" 
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
                           value="{{ old('no_narahubung', $tiket->no_narahubung ?: $tiket->kontak_pemohon) }}" 
                           placeholder="Nomor WhatsApp aktif narahubung (contoh: 081234567890)" 
                           required>
                </div>
            </div>
        </div>

        <!-- 2. BERKAS DOKUMEN PERMINTAAN (Bisa Unggah Pengganti atau Pertahankan Berkas Lama) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-document"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Berkas Dokumen Permintaan</h2>
                    <p class="section-head-desc">Periksa dan unggah berkas pengganti yang telah disesuaikan dengan catatan Walidata (kosongkan jika berkas lama sudah benar)</p>
                </div>
            </div>

            <div class="dtsen-filebox-grid">
                
                <!-- 1. Surat Permohonan (File Box & Input Fields) - Spans Full Width at Top -->
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
                            <div class="dtsen-dropzone-title" id="permohonanTitle">
                                @if($tiket->file_surat_permohonan || $tiket->file_surat)
                                    <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_surat_permohonan ?: ($tiket->nama_file_asli ?: 'Surat Permohonan') }}</span>
                                @else
                                    Pilih Berkas Surat Permohonan
                                @endif
                            </div>
                            <div class="dtsen-dropzone-sub" id="permohonanSub">
                                Klik di sini untuk mengunggah berkas pengganti (PDF, DOC, DOCX Maks 50MB)
                            </div>
                            <input type="file" 
                                   name="file_surat_permohonan" 
                                   id="inputPermohonan" 
                                   class="d-none" 
                                   accept=".pdf,.doc,.docx" 
                                   onchange="handleFileChange(this, 'permohonanTitle', 'permohonanSub')">
                        </div>

                        <!-- 3 Input Surat Permohonan: Nomor Surat, Tanggal, Perihal -->
                        <div class="dtsen-permohonan-meta-inputs">
                            <div>
                                <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                    Nomor Surat
                                </label>
                                <input type="text" 
                                       name="no_surat_permohonan" 
                                       class="form-text-input" 
                                       value="{{ old('no_surat_permohonan', $tiket->no_surat_permohonan ?: $tiket->no_surat_edaran) }}" 
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
                                           value="{{ old('tgl_surat_permohonan', $tiket->tgl_surat_permohonan ? $tiket->tgl_surat_permohonan->format('Y-m-d') : ($tiket->tgl_permintaan ? \Carbon\Carbon::parse($tiket->tgl_permintaan)->format('Y-m-d') : '')) }}"
                                           style="padding: 8px 12px; font-size: 13px;">
                                </div>
                                <div>
                                    <label class="form-group-label" style="font-size: 12px; margin-bottom: 4px;">
                                        Perihal
                                    </label>
                                    <input type="text" 
                                           name="perihal" 
                                           class="form-text-input" 
                                           value="{{ old('perihal', $tiket->perihal) }}" 
                                           placeholder="Perihal surat permohonan"
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
                            KAK
                        </div>
                        <span class="dtsen-badge-req">Wajib</span>
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
                        <div class="dtsen-dropzone-sub" id="kakSub">Klik untuk ganti berkas (PDF, DOC, DOCX Maks 50MB)</div>
                        <input type="file" 
                               name="file_kak" 
                               id="inputKak" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
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
                        <div class="dtsen-dropzone-title" id="sktmTitle">
                            @if($tiket->file_sktm)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_sktm ?: 'SK Tim Pelaksana' }}</span>
                            @else
                                Pilih Berkas SK Tim Pelaksana
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="sktmSub">Klik untuk ganti (PDF, DOC, DOCX, ZIP Maks 50MB)</div>
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
                        <div class="dtsen-dropzone-title" id="ropaTitle">
                            @if($tiket->file_ropa)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_ropa ?: 'Dokumen ROPA' }}</span>
                            @else
                                Pilih Berkas ROPA
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="ropaSub">Klik untuk ganti (PDF, DOC, DOCX, XLSX Maks 50MB)</div>
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
                        <div class="dtsen-dropzone-title" id="dpiaTitle">
                            @if($tiket->file_dpia)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_dpia ?: 'Dokumen DPIA' }}</span>
                            @else
                                Pilih Berkas DPIA
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="dpiaSub">Klik untuk ganti (PDF, DOC, DOCX, XLSX Maks 50MB)</div>
                        <input type="file" 
                               name="file_dpia" 
                               id="inputDpia" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.xlsx,.xls" 
                               onchange="handleFileChange(this, 'dpiaTitle', 'dpiaSub')">
                    </div>
                </div>

                <!-- 6. Klasifikasi DTSEN (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Klasifikasi DTSEN
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
                    </div>
                    <div class="dtsen-file-dropzone" onclick="document.getElementById('inputKlasifikasi').click()">
                        <i class="fi fi-rr-folder dtsen-dropzone-icon"></i>
                        <div class="dtsen-dropzone-title" id="klasifikasiTitle">
                            @if($tiket->file_klasifikasi_dtsen)
                                <span style="color: #059669; font-weight: 700;">✓ Berkas Ada: {{ $tiket->nama_file_klasifikasi_dtsen ?: 'Klasifikasi DTSEN' }}</span>
                            @else
                                Pilih Berkas Klasifikasi DTSEN
                            @endif
                        </div>
                        <div class="dtsen-dropzone-sub" id="klasifikasiSub">Klik untuk ganti (PDF, DOC, DOCX, XLSX Maks 50MB)</div>
                        <input type="file" 
                               name="file_klasifikasi_dtsen" 
                               id="inputKlasifikasi" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx,.xlsx,.xls" 
                               onchange="handleFileChange(this, 'klasifikasiTitle', 'klasifikasiSub')">
                    </div>
                </div>

                <!-- 7. Hak Akses (File Box) Opsional -->
                <div class="dtsen-filebox-card">
                    <div class="dtsen-filebox-header">
                        <div class="dtsen-filebox-title">
                            Hak Akses
                        </div>
                        <span class="dtsen-badge-opt">Opsional</span>
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
                        <div class="dtsen-dropzone-sub" id="hakAksesSub">Klik untuk ganti (PDF, DOC, DOCX Maks 50MB)</div>
                        <input type="file" 
                               name="file_hak_akses" 
                               id="inputHakAkses" 
                               class="d-none" 
                               accept=".pdf,.doc,.docx" 
                               onchange="handleFileChange(this, 'hakAksesTitle', 'hakAksesSub')">
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. VARIABEL DTSEN YANG DIMINTA -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-list-check"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Variabel DTSEN</h2>
                    <p class="section-head-desc">Sesuaikan variabel data mikro yang diajukan sesuai kebutuhan instansi</p>
                </div>
            </div>

            @include('partials.selector_variabel_dtsen', ['selectedVars' => old('variabel_dtsen', $tiket->variabel_dtsen ?? [])])
        </div>

        <!-- 4. CATATAN TINDAK LANJUT PERBAIKAN DARI OPD (Opsional) -->
        <div class="form-section-card">
            <div class="section-head-box">
                <div class="section-head-icon">
                    <i class="fi fi-rr-comment-alt-check"></i>
                </div>
                <div>
                    <h2 class="section-head-title">Catatan Perbaikan dari Perangkat Daerah</h2>
                    <p class="section-head-desc">Sampaikan penjelasan singkat mengenai perbaikan yang telah dilakukan untuk Admin Walidata</p>
                </div>
            </div>

            <div>
                <textarea name="catatan_opd" 
                          rows="3" 
                          class="form-text-input" 
                          placeholder="Contoh: Berkas KAK telah ditandatangani ulang oleh Kepala Dinas, dan nomor surat permohonan telah dikoreksi."
                          style="resize: vertical; font-size: 13px; line-height: 1.5;">{{ old('catatan_opd') }}</textarea>
            </div>
        </div>

        <!-- TOMBOL AKSI SUBMIT PERBAIKAN -->
        <div class="submit-actions-row">
            <a href="{{ route('opd.tiket.show', $tiket->id) }}" class="btn-pill-outline btn-cancel-action">
                &larr; Batal & Kembali ke Tiket
            </a>

            <button type="submit" class="btn-pill-primary btn-submit-action" style="background: #059669; border-color: #059669;">
                <i class="fi fi-rr-paper-plane"></i>
                <span>Kirim Ulang Permohonan ke Walidata</span>
            </button>
        </div>

    </form>

</div>

<script>
function handleFileChange(input, titleId, subId) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        const titleEl = document.getElementById(titleId);
        const subEl = document.getElementById(subId);
        if (titleEl) {
            titleEl.innerHTML = `<span style="color: #0b57d0; font-weight: 700;">✓ Berkas Baru: ${file.name}</span>`;
        }
        if (subEl) {
            subEl.innerHTML = `<span class="dtsen-ready-badge">Berkas pengganti terpilih (${sizeMb} MB)</span>`;
        }
    }
}
</script>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pengajuan Dokumen KAK</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Product+Sans:wght@400;700&family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet (Single CSS) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="form-page">

    <div class="form-shell">
        
        <!-- Standard White Form Card (24px Border Radius) -->
        <div class="form-card">
            
            <!-- Top Utility Bar: Badge & Language Selector -->
            <div class="form-top-bar">
                <div class="form-badge-system">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Sistem Pengajuan Dokumen KAK</span>
                </div>

                <div class="lang-selector-box">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <select id="langSwitcher" class="lang-dropdown" aria-label="Pilih Bahasa">
                        <option value="id">🇮🇩 Bahasa Indonesia</option>
                        <option value="en">🇬🇧 English</option>
                        <option value="su">🇮🇩 Basa Sunda</option>
                        <option value="jv">🇮🇩 Basa Jawa</option>
                    </select>
                </div>
            </div>

            <!-- Form Header at Top -->
            <div class="form-header">
                <h1 class="form-title" data-i18n="form_title">Formulir Pengajuan Dokumen KAK</h1>
                <p class="form-subtitle" data-i18n="form_subtitle">Silakan isi rincian identitas kegiatan dan lampirkan dokumen Kerangka Acuan Kerja Anda.</p>
            </div>

            <!-- Informational Notice Box -->
            <div class="form-notice-box">
                <span style="font-size: 20px; color: #1a73e8;"><i class="fi fi-rr-shield-check"></i></span>
                <div>
                    <span data-i18n="notice_text">Sertifikasi Integritas Otomatis: Dokumen yang Anda unggah akan diproses menggunakan Dual-Hash SHA-256 sebagai jaminan keaslian dan bukti validasi pada lembar pengesahan.</span>
                </div>
            </div>

            @if(session('info'))
                <div class="form-alert-success">
                    {{ session('info') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="form-alert-error">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('formulir.store') }}" method="POST" enctype="multipart/form-data" id="kakForm">
                @csrf

                <!-- 1. Judul KAK / Kegiatan (Full Width) -->
                <div class="form-group">
                    <label for="inputJudulKak" class="form-label">
                        <span data-i18n="label_judul">Judul KAK / Nama Kegiatan</span> <span class="form-required">*</span>
                    </label>
                    <input type="text" 
                           name="judul_kak" 
                           id="inputJudulKak" 
                           class="form-control" 
                           value="{{ old('judul_kak') }}" 
                           placeholder="Contoh: Pengadaan Perangkat Server & Mini Data Center 2026"
                           data-i18n-ph="ph_judul">
                    <small class="form-help" data-i18n="help_judul">Nama lengkap kegiatan kerangka acuan kerja (jika kosong, disesuaikan dari nama file).</small>
                </div>

                <!-- 2. Row 2 Kolom: Pengusul & Unit Kerja -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="input_penanggung_jawab" class="form-label">
                            <span data-i18n="label_pengusul">Nama Pengusul / PPTK</span> <span class="form-required">*</span>
                        </label>
                        <input type="text" 
                               name="penanggung_jawab" 
                               id="input_penanggung_jawab" 
                               class="form-control" 
                               value="{{ old('penanggung_jawab') }}" 
                               placeholder="Nama Pejabat Pelaksana Teknis Kegiatan"
                               data-i18n-ph="ph_pengusul">
                    </div>

                    <div class="form-group">
                        <label for="input_unit_kerja" class="form-label">
                            <span data-i18n="label_unit">Unit Kerja / Bidang</span> <span class="form-required">*</span>
                        </label>
                        <input type="text" 
                               name="unit_kerja" 
                               id="input_unit_kerja" 
                               class="form-control" 
                               value="{{ old('unit_kerja') }}" 
                               placeholder="Contoh: Bidang Infrastruktur & TI"
                               data-i18n-ph="ph_unit">
                    </div>
                </div>

                <!-- 3. Area Berkas Dokumen KAK (Dropzone) -->
                <div class="form-group">
                    <label class="form-label">
                        <span data-i18n="label_file">Berkas Dokumen KAK (File Attachment)</span> <span class="form-required">*</span>
                    </label>
                    
                    <div class="form-file-dropzone" id="gFileBox">
                        <input type="file" name="file_kak" id="gFileInput" required>
                        
                        <div class="form-file-prompt" id="gFilePrompt">
                            <div class="form-file-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                </svg>
                            </div>
                            <div class="form-file-title" data-i18n="title_file">Klik di sini untuk memilih berkas atau seret ke area ini</div>
                            <div class="form-file-subtitle" data-i18n="sub_file">Mendukung format: PDF, DOCX, XLSX, atau ZIP (Maksimal 500MB)</div>
                        </div>

                        <div class="form-file-selected" id="gFileSelected">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="form-badge-ext" id="gFileExtBadge">PDF</span>
                                <div style="text-align: left;">
                                    <div style="font-weight: 700; color: #1f1f1f; font-size: 14px;" id="gFileNameText">dokumen.pdf</div>
                                    <div style="font-size: 12px; color: #5e5e5e;" id="gFileSizeText">0 KB</div>
                                </div>
                            </div>
                            <span style="font-size: 12px; font-weight: 700; color: #146c2e;" data-i18n="selected_file">✓ Berkas Terpilih</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Klasifikasi Keamanan Dokumen -->
                <div class="form-group">
                    <label class="form-label" data-i18n="label_klasifikasi">Klasifikasi Dokumen</label>
                    <div class="form-choice-list">
                        <label class="form-choice-item">
                            <input type="radio" name="klasifikasi_dokumen" value="TERBATAS (Level 3)" checked>
                            <span data-i18n="opt_terbatas"><strong>TERBATAS (Level 3)</strong> — Rincian kegiatan & rencana anggaran internal SKPD</span>
                        </label>
                        <label class="form-choice-item">
                            <input type="radio" name="klasifikasi_dokumen" value="BIASA (Level 2)">
                            <span data-i18n="opt_biasa"><strong>BIASA (Level 2)</strong> — Terbuka untuk umum / pengumuman publik</span>
                        </label>
                        <label class="form-choice-item">
                            <input type="radio" name="klasifikasi_dokumen" value="RAHASIA (Level 4)">
                            <span data-i18n="opt_rahasia"><strong>RAHASIA (Level 4)</strong> — Spesifikasi pengadaan bernilai strategis tinggi</span>
                        </label>
                    </div>
                </div>

                <!-- 5. Catatan / Ringkasan KAK -->
                <div class="form-group">
                    <label for="input_keterangan" class="form-label" data-i18n="label_catatan">Catatan / Ringkasan Ruang Lingkup (Opsional)</label>
                    <textarea name="keterangan" 
                              id="input_keterangan" 
                              class="form-textarea" 
                              placeholder="Tulis ringkasan latar belakang atau ruang lingkup dokumen KAK..."
                              data-i18n-ph="ph_catatan">{{ old('keterangan') }}</textarea>
                </div>

                <!-- 6. Pernyataan Integritas & Kepatuhan -->
                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-choice-item">
                        <input type="checkbox" name="pernyataan_hash" value="1" checked required>
                        <span style="font-size: 13px; color: #444746;" data-i18n="text_kepatuhan">
                            Menyetujui perekaman nilai integritas Dual-Hash SHA-256 untuk sertifikasi keabsahan dokumen anti-tamper.
                        </span>
                    </label>
                </div>

                <!-- Bottom Action Bar -->
                <div class="form-actions-bar">
                    <div class="form-left-actions">
                        <a href="{{ route('formulir.blanko') }}" target="_blank" class="form-link" title="Cetak Blanko Kosong">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            <span data-i18n="link_blanko">Cetak Blanko KAK</span>
                        </a>
                    </div>

                    <button type="submit" class="form-btn-submit">
                        <span data-i18n="btn_submit">Kirim & Registrasi KAK</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- Unified Master JavaScript (Single JS) -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

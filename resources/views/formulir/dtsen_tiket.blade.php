<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Permintaan Bagi Pakai DTSEN — Sistem Tiket Layanan</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Product+Sans:wght@400;700&family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Unified Master Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="form-page">

    <div class="form-shell" style="max-width: 960px;">
        
        <!-- Top Utility Bar: Brand Badge, Quick Links & Language Switcher -->
        <div class="form-card" style="padding: 16px 24px; margin-bottom: 20px; border-radius: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 13px; font-weight: 800; background: #0b57d0; color: #fff; width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">DTSEN</span>
                    <div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--text-primary);">Layanan Permintaan Bagi Pakai DTSEN</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Sistem Registrasi & Pelacakan Tiket Data Terpadu Sosial Ekonomi Nasional</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
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

                    @auth
                        <a href="{{ route('opd.dashboard') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px;">
                            <i class="fi fi-rr-apps"></i> Dashboard OPD
                        </a>
                        <a href="{{ route('opd.layanan-bagi-pakai') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px;">
                            <i class="fi fi-rr-document"></i> Layanan DTSEN
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                            @csrf
                            <button type="submit" class="btn-header-logout" style="padding: 6px 10px; font-size: 11px;">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px;">
                            <i class="fi fi-rr-lock"></i> Masuk Portal OPD
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="form-alert-success" style="margin-bottom: 20px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="form-alert-error" style="margin-bottom: 20px;">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- 1. Hero Card: Sambutan & Tombol "Permintaan Bagi Pakai" -->
        <div class="ticket-hero">
            <div>
                <h1 class="ticket-hero-title">
                    <span>Permohonan Bagi Pakai Data DTSEN</span>
                </h1>
                <p class="ticket-hero-desc">
                    Silakan ajukan permintaan bagi pakai data DTSEN antar instansi, atau pantau alur tahapan verifikasi berkas Anda secara real-time di bawah.
                </p>
            </div>
            <div>
                <button type="button" id="btnToggleBuatTiket" class="btn-buat-tiket">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Permintaan Bagi Pakai</span>
                </button>
            </div>
        </div>

        <!-- 2. Template Form Pembuatan Tiket (Muncul di Atas saat tombol diklik / error) -->
        <div id="ticketFormBox" class="ticket-template-box" style="{{ ((isset($errors) && $errors->any()) || request()->has('buat_tiket')) ? 'display: block;' : 'display: none;' }}">
            
            <div class="ticket-template-header">
                <div>
                    <div class="form-badge-system" style="margin-bottom: 8px;">
                        <span><i class="fi fi-rr-document-signed"></i> FORMULIR PENGAJUAN TIKET BAGI PAKAI DTSEN</span>
                    </div>
                    <h2 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">
                        Template Tiket Permintaan Bagi Pakai Data DTSEN
                    </h2>
                    <p style="font-size: 13px; color: var(--text-secondary);">
                        Lengkapi identitas instansi pemohon, nomor surat edaran, dan rincian variabel data DTSEN yang diperlukan.
                    </p>
                </div>
                <button type="button" id="btnCloseTicketForm" style="background: transparent; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px 8px; border-radius: 8px;">
                    ✕
                </button>
            </div>

            <form action="{{ route('formulir.store') }}" method="POST" enctype="multipart/form-data" id="formTiketDtsen">
                @csrf

                <!-- Row 1: Nama Instansi & No Surat Edaran -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="inputNamaInstansi" class="form-label">
                            Nama Instansi / Lembaga Pemohon <span class="form-required">*</span>
                        </label>
                        <input type="text" 
                               name="nama_instansi" 
                               id="inputNamaInstansi" 
                               class="form-control" 
                               value="{{ old('nama_instansi') }}" 
                               placeholder="Contoh: Dinas Sosial Provinsi / Bappeda Kota" 
                               required>
                        <small class="form-help">Nama resmi Organisasi Perangkat Daerah (OPD) atau Lembaga Pengusul.</small>
                    </div>

                    <div class="form-group">
                        <label for="inputNoSuratEdaran" class="form-label">
                            No. Surat Edaran / Permohonan <span class="form-required">*</span>
                        </label>
                        <input type="text" 
                               name="no_surat_edaran" 
                               id="inputNoSuratEdaran" 
                               class="form-control" 
                               value="{{ old('no_surat_edaran') }}" 
                               placeholder="Contoh: SE/400.9/081/DINSOS/2026" 
                               required>
                        <small class="form-help">Nomor surat edaran atau surat dinas permohonan resmi.</small>
                    </div>
                </div>

                <!-- Row 2: PIC Pemohon & Kontak (Opsional) -->
                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="inputNamaPemohon" class="form-label">Nama PIC / Penanggung Jawab</label>
                        <input type="text" 
                               name="nama_pemohon" 
                               id="inputNamaPemohon" 
                               class="form-control" 
                               value="{{ old('nama_pemohon') }}" 
                               placeholder="Nama Pejabat / Pegawai Narahubung">
                    </div>

                    <div class="form-group">
                        <label for="inputKontakPemohon" class="form-label">Kontak / WhatsApp & Email</label>
                        <input type="text" 
                               name="kontak_pemohon" 
                               id="inputKontakPemohon" 
                               class="form-control" 
                               value="{{ old('kontak_pemohon') }}" 
                               placeholder="Contoh: 08123456789 / pemohon@instansi.go.id">
                    </div>
                </div>

                <!-- Row 3: Variabel DTSEN yang Diminta (Minimal 5 Baris + Tombol Tambah Kolom Isian) -->
                <div class="form-group" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                        <label class="form-label" style="margin-bottom: 0;">
                            Variabel DTSEN yang Diminta <span class="form-required">*</span>
                            <span style="font-size: 12px; font-weight: normal; color: var(--text-muted);">(Set Minimal 5 Form Variabel)</span>
                        </label>
                        
                        <button type="button" id="btnAddVariableRow" class="btn-add-var">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>+ Tambah Kolom Form Isian</span>
                        </button>
                    </div>

                    <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px;">
                        Tuliskan variabel atau atribut data DTSEN yang Anda butuhkan. Anda dapat mengedit isian di bawah atau menambahkan kolom baru.
                    </p>

                    <!-- Container Kolom Isian Variabel Dinamis (Default Minimal 5: Variabel 1, 2, 3, 4, 5) -->
                    <div class="variables-container" id="variablesList">
                        @php
                            $defaultVars = [
                                'Variabel 1',
                                'Variabel 2',
                                'Variabel 3',
                                'Variabel 4',
                                'Variabel 5'
                            ];
                            $oldVars = old('variabel_dtsen', $defaultVars);
                            if (count($oldVars) < 5) {
                                for ($i = count($oldVars); $i < 5; $i++) {
                                    $oldVars[] = $defaultVars[$i] ?? ('Variabel ' . ($i + 1));
                                }
                            }
                        @endphp

                        @foreach($oldVars as $idx => $varVal)
                            <div class="variable-row" data-row-idx="{{ $idx + 1 }}">
                                <div class="variable-num-badge">{{ $idx + 1 }}</div>
                                <input type="text" 
                                       name="variabel_dtsen[]" 
                                       class="variable-input" 
                                       value="{{ $varVal }}" 
                                       placeholder="Variabel {{ $idx + 1 }}" 
                                       required>
                                @if($idx >= 5)
                                    <button type="button" class="btn-remove-var" onclick="removeVariableRow(this)" title="Hapus kolom ini">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Row 4: Berkas Surat Edaran / Permohonan (Dropzone) -->
                <div class="form-group">
                    <label class="form-label">
                        Lampiran Surat Edaran / Surat Permohonan Resmi (Opsional)
                    </label>
                    
                    <div class="form-file-dropzone" id="gFileBox">
                        <input type="file" name="file_surat" id="gFileInput" accept=".pdf,.docx,.xlsx,.zip">
                        
                        <div class="form-file-prompt" id="gFilePrompt">
                            <div class="form-file-icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                                </svg>
                            </div>
                            <div class="form-file-title">Pilih berkas surat atau seret ke area ini</div>
                            <div class="form-file-subtitle">Format didukung: PDF, DOCX, XLSX, atau ZIP (Maksimal 50MB)</div>
                        </div>

                        <div class="form-file-selected" id="gFileSelected">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="form-badge-ext" id="gFileExtBadge">PDF</span>
                                <div style="text-align: left;">
                                    <div style="font-weight: 700; color: #1f1f1f; font-size: 14px;" id="gFileNameText">surat.pdf</div>
                                    <div style="font-size: 12px; color: #5e5e5e;" id="gFileSizeText">0 KB</div>
                                </div>
                            </div>
                            <span style="font-size: 12px; font-weight: 700; color: #146c2e;">✓ Berkas Surat Terlampir</span>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Tujuan Pemanfaatan Bagi Pakai DTSEN -->
                <div class="form-group">
                    <label for="inputTujuan" class="form-label">Tujuan Pemanfaatan Bagi Pakai DTSEN</label>
                    <textarea name="tujuan_pemanfaatan" 
                              id="inputTujuan" 
                              class="form-textarea" 
                              rows="3" 
                              placeholder="Jelaskan secara ringkas peruntukan atau program pemanfaatan data DTSEN ini...">{{ old('tujuan_pemanfaatan') }}</textarea>
                </div>

                <!-- Action Bar Form -->
                <div class="form-actions-bar" style="margin-top: 24px;">
                    <button type="button" id="btnCancelForm" class="btn-pill-outline" style="font-size: 13px;">
                        Batal
                    </button>

                    <button type="submit" class="form-btn-submit">
                        <span>Kirim & Buat Tiket DTSEN</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

            </form>
        </div>

        <!-- 3. Progress Bar & Step Tracker Status Tiket Aktif -->
        @if($selectedTiket)
            <div class="progress-card">
                
                <div class="progress-top-info">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                            <span class="ticket-tag-chip">{{ $selectedTiket->nomor_tiket }}</span>
                            @if($selectedTiket->status === 'PERLU_PERBAIKAN')
                                <span class="badge-status pending" style="font-size: 12px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;"><i class="fi fi-rr-exclamation"></i> PERLU PERBAIKAN OPD</span>
                            @elseif($selectedTiket->step5_done)
                                <span class="badge-status success" style="font-size: 12px;"><i class="fi fi-rr-check"></i> SELESAI (SUKSES DIBAGIPAKAIKAN)</span>
                            @else
                                <span class="badge-status pending" style="font-size: 12px;">⏳ SEDANG DIPROSES</span>
                            @endif
                        </div>
                        <h2 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;">
                            {{ $selectedTiket->nama_instansi }}
                        </h2>
                        <div style="font-size: 13px; color: var(--text-secondary);">
                            No. Surat: <strong>{{ $selectedTiket->no_surat_edaran }}</strong> | Diajukan pada: {{ $selectedTiket->created_at->format('d F Y, H:i') }} WIB
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Status Terkini:</span>
                        <div style="font-size: 14px; font-weight: 700; color: {{ ($selectedTiket->status === 'PERLU_PERBAIKAN') ? '#d97706' : ($selectedTiket->step5_done ? '#1e8e3e' : '#0b57d0') }};">
                            @if($selectedTiket->status === 'PERLU_PERBAIKAN')
                                Perlu Perbaikan Variabel vs SE
                            @else
                                {{ $selectedTiket->current_status_label }}
                            @endif
                        </div>
                    </div>
                </div>

                @if($selectedTiket->status === 'PERLU_PERBAIKAN')
                    <div style="background: #fffbeb; border: 1px solid #fde68a; border-left: 5px solid #d97706; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <strong style="color: #92400e; font-size: 14px;"><i class="fi fi-rr-exclamation"></i> Perhatian: Verifikasi Variabel Memerlukan Perbaikan</strong>
                                <div style="font-size: 13px; color: #78350f; margin-top: 4px;">
                                    Catatan Tim Walidata: {{ $selectedTiket->catatan_admin }}
                                </div>
                            </div>
                            <a href="{{ route('profile') }}" class="btn-pill-primary" style="background: #d97706; border-color: #d97706; font-size: 12px; padding: 6px 14px;">
                                <i class="fi fi-rr-edit"></i> Buka Profil untuk Memperbaiki Variabel
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Visual Overall Progress Bar Track -->
                <div class="progress-gauge-box">
                    <div class="progress-gauge-labels">
                        <span>Progress Alur Verifikasi Bagi Pakai DTSEN</span>
                        <span style="color: {{ $selectedTiket->step5_done ? '#1e8e3e' : '#0b57d0' }}; font-weight: 700;">
                            {{ $selectedTiket->progress_percent }}% Selesai
                        </span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $selectedTiket->progress_percent }}%;"></div>
                    </div>
                </div>

                <!-- 5-Step Process Flow: Centang Hijau vs Sedang Diproses vs Menunggu -->
                <div class="step-flow">
                    @foreach(\App\Models\TiketDtsen::$stepsDefinition as $stepNum => $stepDef)
                        @php
                            $isDone = $selectedTiket->isStepDone($stepNum);
                            $isActive = $selectedTiket->isStepActive($stepNum);
                            $nodeClass = $isDone ? 'is-done' : ($isActive ? 'is-active' : 'is-pending');
                            $stepColAt = "step{$stepNum}_at";
                            $timeDone = $selectedTiket->$stepColAt;
                        @endphp
                        
                        <div class="step-node {{ $nodeClass }}">
                            <div class="step-node-icon">
                                @if($isDone)
                                    <!-- Centang Hijau (✓) -->
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                @elseif($isActive)
                                    <!-- Lingkaran Biru Aktif -->
                                    <span>{{ $stepNum }}</span>
                                @else
                                    <!-- Angka Netral -->
                                    <span>{{ $stepNum }}</span>
                                @endif
                            </div>

                            <div class="step-node-content">
                                <div class="step-node-title" style="font-size: 14px;">
                                    <strong>Progress {{ $stepNum }}</strong>
                                </div>

                                @if($isDone)
                                    <span class="step-badge-status" style="background: #e6f4ea; color: #137333;">
                                        ✓ Selesai {{ $timeDone ? '(' . $timeDone->format('d/m H:i') . ')' : '' }}
                                    </span>
                                @elseif($stepNum === 1 && $selectedTiket->status === 'PERLU_PERBAIKAN')
                                    <span class="step-badge-status" style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                        <i class="fi fi-rr-exclamation"></i> Perlu Perbaikan
                                    </span>
                                @elseif($isActive)
                                    <span class="step-badge-status" style="background: #eaf1fb; color: #0b57d0;">
                                        ● Sedang Diproses
                                    </span>
                                @else
                                    <span class="step-badge-status" style="background: #f8f9fa; color: #70757a;">
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Detail Variabel & Catatan Admin -->
                <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-divider); display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 6px;">
                            Variabel DTSEN yang Diajukan ({{ count($selectedTiket->variabel_dtsen ?? []) }} Variabel):
                        </div>
                        <div class="dtsen-chips-wrap">
                            @foreach($selectedTiket->variabel_dtsen ?? [] as $v)
                                <span class="dtsen-chip">✓ {{ $v }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 6px;">
                            Catatan Verifikasi Tim Walidata:
                        </div>
                        <div style="background: #f8fafd; border: 1px solid var(--border-color); border-radius: 10px; padding: 10px 14px; font-size: 13px; color: var(--text-primary); line-height: 1.4;">
                            {{ $selectedTiket->catatan_admin ?: 'Tiket telah diterima dan sedang dalam antrean verifikasi administrasi.' }}
                        </div>

                        @if($selectedTiket->file_surat)
                            <div style="margin-top: 10px;">
                                <a href="{{ route('tiket.download-surat', $selectedTiket->id) }}" class="btn-row-action download">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <span>Unduh Surat Permohonan ({{ $selectedTiket->nama_file_asli ?: 'Surat.pdf' }})</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endif

        <!-- 4. Daftar Tiket Layanan Terdaftar & Cari Tiket Lain -->
        <div class="form-card" style="padding: 28px 32px; border-radius: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary);">
                        Daftar Tiket Permintaan Bagi Pakai DTSEN
                    </h3>
                    <p style="font-size: 12px; color: var(--text-secondary);">
                        Klik tiket untuk melihat visual progress bar dan rincian proses.
                    </p>
                </div>

                <form action="{{ route('formulir.index') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           style="padding: 6px 12px; font-size: 13px; width: 220px;" 
                           placeholder="Cari No. Tiket / Instansi..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px;">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('formulir.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 10px;" title="Reset Pencarian">✕</a>
                    @endif
                </form>
            </div>

            <div class="dash-table-container">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>No. Tiket</th>
                            <th>Nama Instansi</th>
                            <th>No. Surat Edaran</th>
                            <th>Jml Variabel</th>
                            <th>Status Tahapan</th>
                            <th>Progress</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tikets as $t)
                            <tr style="{{ ($selectedTiket && $selectedTiket->id === $t->id) ? 'background-color: #f0f7ff;' : '' }}">
                                <td>
                                    <strong style="color: var(--blue-primary); font-family: var(--font-mono); font-size: 13px;">
                                        {{ $t->nomor_tiket }}
                                    </strong>
                                    <div style="font-size: 11px; color: var(--text-muted);">{{ $t->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary); font-size: 13px;">{{ $t->nama_instansi }}</div>
                                    <div style="font-size: 11px; color: var(--text-secondary);">{{ $t->nama_pemohon }}</div>
                                </td>
                                <td>
                                    <span style="font-size: 12px; color: var(--text-secondary);">{{ $t->no_surat_edaran }}</span>
                                </td>
                                <td>
                                    <span class="dtsen-chip">{{ count($t->variabel_dtsen ?? []) }} Variabel</span>
                                </td>
                                <td>
                                    @if($t->step5_done)
                                        <span class="badge-status success">✓ SELESAI</span>
                                    @else
                                        <span class="badge-status pending">PROGRESS {{ $t->current_step }}</span>
                                    @endif
                                </td>
                                <td style="width: 120px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="progress-track" style="height: 8px;">
                                            <div class="progress-fill" style="width: {{ $t->progress_percent }}%;"></div>
                                        </div>
                                        <span style="font-size: 11px; font-weight: 700;">{{ $t->progress_percent }}%</span>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('formulir.index', ['track_id' => $t->id]) }}" class="btn-row-action print" style="font-size: 11px;">
                                        <span><i class="fi fi-rr-eye"></i> Pantau Progress</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                    Belum ada permohonan bagi pakai DTSEN. Silakan klik tombol <strong>"Permintaan Bagi Pakai"</strong> di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tikets->hasPages())
                <div style="margin-top: 16px;">
                    {{ $tikets->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Unified Master JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Toggle Template Tiket di Atas
        const btnToggleBuatTiket = document.getElementById('btnToggleBuatTiket');
        const btnCloseTicketForm = document.getElementById('btnCloseTicketForm');
        const btnCancelForm = document.getElementById('btnCancelForm');
        const ticketFormBox = document.getElementById('ticketFormBox');

        function openTicketForm() {
            if (ticketFormBox) {
                ticketFormBox.style.display = 'block';
                ticketFormBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function closeTicketForm() {
            if (ticketFormBox) {
                ticketFormBox.style.display = 'none';
            }
        }

        if (btnToggleBuatTiket) {
            btnToggleBuatTiket.addEventListener('click', function() {
                if (ticketFormBox.style.display === 'none' || ticketFormBox.style.display === '') {
                    openTicketForm();
                } else {
                    closeTicketForm();
                }
            });
        }

        if (btnCloseTicketForm) btnCloseTicketForm.addEventListener('click', closeTicketForm);
        if (btnCancelForm) btnCancelForm.addEventListener('click', closeTicketForm);

        // Dinamis Tambah Kolom Isian Variabel
        const btnAddVariableRow = document.getElementById('btnAddVariableRow');
        const variablesList = document.getElementById('variablesList');

        if (btnAddVariableRow && variablesList) {
            btnAddVariableRow.addEventListener('click', function() {
                const currentRows = variablesList.querySelectorAll('.variable-row').length;
                const newRowIndex = currentRows + 1;

                const rowDiv = document.createElement('div');
                rowDiv.className = 'variable-row';
                rowDiv.setAttribute('data-row-idx', newRowIndex);
                rowDiv.innerHTML = `
                    <div class="variable-num-badge">${newRowIndex}</div>
                    <input type="text" 
                           name="variabel_dtsen[]" 
                           class="variable-input" 
                           value="Variabel ${newRowIndex}"
                           placeholder="Variabel ${newRowIndex}" 
                           required>
                    <button type="button" class="btn-remove-var" onclick="removeVariableRow(this)" title="Hapus kolom ini">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                `;

                variablesList.appendChild(rowDiv);
                
                // Focus the newly added input
                const newInput = rowDiv.querySelector('.variable-input');
                if (newInput) newInput.focus();
            });
        }

        // Hapus Kolom Isian Variabel Tambahan
        window.removeVariableRow = function(btn) {
            const row = btn.closest('.variable-row');
            if (row) {
                row.remove();
                // Re-index badges
                const allRows = variablesList.querySelectorAll('.variable-row');
                allRows.forEach((r, idx) => {
                    r.setAttribute('data-row-idx', idx + 1);
                    const badge = r.querySelector('.variable-num-badge');
                    if (badge) badge.textContent = idx + 1;
                });
            }
        };
    </script>
</body>
</html>

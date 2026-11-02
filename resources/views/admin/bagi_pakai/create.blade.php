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
                        <i class="fi fi-rr-document"></i> Format SOP: FR.01-SOP.DTSEN-09/DKISP
                    </span>
                </div>
                <p style="font-size: 13px; color: #64748b; margin: 0;">
                    Lengkapi seluruh parameter formulir pemenuhan layanan bagi pakai DTSEN (Lampiran I SOP). Seluruh isian akan otomatis menjadi basis data tampilan dan ekspor berkas Word resmi (.docx).
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

        <!-- 1. HEADER DOKUMEN & PENOMORAN NASKAH SOP -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">1. Header Dokumen & Penomoran Naskah SOP</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">
                        Nomor Naskah Internal Bidang 
                    </label>
                    <input type="text" 
                           name="nomor_naskah" 
                           class="form-text-input" 
                           value="{{ old('nomor_naskah') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">
                        ID Pekerjaan (Nomor Tiket Unik)
                    </label>
                    <input type="text" 
                           name="nomor_tiket" 
                           class="form-text-input" 
                           value="{{ old('nomor_tiket') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">No. Agenda Disposisi</label>
                    <input type="text" 
                           name="no_agenda_disposisi" 
                           class="form-text-input" 
                           value="{{ old('no_agenda_disposisi') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">SOP Rujukan</label>
                    <input type="text" 
                           name="sop_rujukan" 
                           class="form-text-input" 
                           value="{{ old('sop_rujukan') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>
        </div>

        <!-- 2. PERMINTAAN LAYANAN (INSTANSI & NARAHUBUNG) -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">2. Permintaan Layanan (Instansi & Narahubung)</h2>
            </div>

            <div class="form-2col-grid">
                <!-- Nama Instansi (Bisa pilih dari akun OPD atau ketik manual) -->
                <div>
                    <label class="form-group-label">
                        Perangkat Daerah Pemohon 
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
                               placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada" 
                               required>
                    </div>
                </div>

                <!-- No Surat Permintaan -->
                <div>
                    <label class="form-group-label">
                        Nomor Surat Permintaan / Edaran Resmi 
                    </label>
                    <input type="text" 
                           name="no_surat_edaran" 
                           class="form-text-input" 
                           value="{{ old('no_surat_edaran') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada" 
                           required>
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <!-- Tanggal Permintaan -->
                <div>
                    <label class="form-group-label">Tanggal Permintaan</label>
                    <input type="date" 
                           name="tgl_permintaan" 
                           class="form-text-input" 
                           value="{{ old('tgl_permintaan') }}">
                </div>

                <!-- Tanggal Diterima -->
                <div>
                    <label class="form-group-label">Tanggal Diterima Walidata</label>
                    <input type="date" 
                           name="tgl_diterima" 
                           class="form-text-input" 
                           value="{{ old('tgl_diterima') }}">
                </div>
            </div>

            <div class="form-2col-grid form-group-mb-16" style="margin-top: 18px;">
                <!-- PIC Pemohon -->
                <div>
                    <label class="form-group-label">Nama Lengkap Narahubung / PIC</label>
                    <input type="text" 
                           name="nama_pemohon" 
                           id="nama_pemohon"
                           class="form-text-input" 
                           value="{{ old('nama_pemohon') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <!-- Kontak Telepon / Hp -->
                <div>
                    <label class="form-group-label">Kontak Telepon / WhatsApp Narahubung</label>
                    <input type="text" 
                           name="kontak_pemohon" 
                           id="kontak_pemohon"
                           class="form-text-input" 
                           value="{{ old('kontak_pemohon') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <!-- Email Pemohon -->
            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Alamat Email Resmi Narahubung / Dinas</label>
                <input type="email" 
                       name="email_pemohon" 
                       id="email_pemohon"
                       class="form-text-input" 
                       value="{{ old('email_pemohon') }}" 
                       placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
            </div>

            <!-- Tujuan Pemanfaatan Data -->
            <div style="margin-top: 18px;">
                <label class="form-group-label">Tujuan Pemanfaatan Data DTSEN</label>
                <textarea name="tujuan_pemanfaatan" 
                          class="form-text-input form-textarea-dtsen" 
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('tujuan_pemanfaatan') }}</textarea>
            </div>
        </div>

        <!-- 3. PENUGASAN & DESKRIPSI PEKERJAAN (WALIDATA) -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">3. Penugasan & Deskripsi Pekerjaan (Walidata)</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Nomor Surat Tugas</label>
                    <input type="text" 
                           name="no_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('no_surat_tugas') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Surat Tugas</label>
                    <input type="date" 
                           name="tgl_surat_tugas" 
                           class="form-text-input" 
                           value="{{ old('tgl_surat_tugas') }}">
                </div>
            </div>

            <div class="form-group-mb-16" style="margin-top: 18px;">
                <label class="form-group-label">Deskripsi Pekerjaan</label>
                <input type="text" 
                       name="deskripsi_pekerjaan" 
                       id="deskripsi_pekerjaan"
                       class="form-text-input" 
                       value="{{ old('deskripsi_pekerjaan') }}" 
                       placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Penanggung Jawab (Pejabat Administrator)</label>
                    <input type="text" 
                           name="penanggung_jawab" 
                           class="form-text-input" 
                           value="{{ old('penanggung_jawab') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Tempat Pelaksanaan</label>
                    <input type="text" 
                           name="tempat_pelaksanaan" 
                           class="form-text-input" 
                           value="{{ old('tempat_pelaksanaan') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Tanggal Mulai Pelaksanaan</label>
                    <input type="date" 
                           name="tgl_mulai" 
                           class="form-text-input" 
                           value="{{ old('tgl_mulai') }}">
                </div>

                <div>
                    <label class="form-group-label">Tanggal Selesai Pelaksanaan</label>
                    <input type="date" 
                           name="tgl_selesai" 
                           class="form-text-input" 
                           value="{{ old('tgl_selesai') }}">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Pelaksana 1</label>
                    <input type="text" 
                           name="pelaksana_1" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_1') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Pelaksana 2</label>
                    <input type="text" 
                           name="pelaksana_2" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_2') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Pelaksana 3</label>
                    <input type="text" 
                           name="pelaksana_3" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_3') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>

                <div>
                    <label class="form-group-label">Pelaksana 4</label>
                    <input type="text" 
                           name="pelaksana_4" 
                           class="form-text-input" 
                           value="{{ old('pelaksana_4') }}" 
                           placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                </div>
            </div>
        </div>

        <!-- 4. VARIABEL DTSEN YANG DIMINTA -->
        <div class="form-section-card">
            <div class="section-head-box-tight" style="margin-bottom: 4px;">
                <h2 class="section-head-title">4. Variabel DTSEN yang Diminta</h2>
            </div>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
                Pilih tema dataset di kotak sebelah kiri, lalu beri tanda centang (checklist) pada variabel data DTSEN yang Anda butuhkan di kotak sebelah kanan.
            </p>

            @include('partials.selector_variabel_dtsen', ['selectedVars' => old('variabel_dtsen', [])])
        </div>

        <!-- 5. PEMENUHAN DOKUMEN & MATRIKS PELAKSANAAN SOP -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">5. Pemenuhan Dokumen & Matriks Pelaksanaan SOP</h2>
            </div>

            <div class="form-2col-grid">
                <div>
                    <label class="form-group-label">Dokumen KAK (Kerangka Acuan Kerja)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_kak" class="form-text-input" style="width: 140px;">
                            <option value="Ada">Ada</option>
                            <option value="Tidak ada">Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_kak" class="form-text-input" value="{{ old('nomor_kak') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Penetapan Tim Pelaksana</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_tim" class="form-text-input" style="width: 140px;">
                            <option value="Ada">Ada</option>
                            <option value="Tidak ada">Tidak ada</option>
                        </select>
                        <input type="text" name="nomor_tim" class="form-text-input" value="{{ old('nomor_tim') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Dokumen ROPA DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_ropa" class="form-text-input" style="width: 140px;">
                            <option value="Sesuai">Sesuai</option>
                            <option value="Belum Sesuai">Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_ropa" class="form-text-input" value="{{ old('nomor_ropa') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Dokumen DPIA DTSEN (Penilaian Dampak PDP)</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_dpia" class="form-text-input" style="width: 140px;">
                            <option value="Sesuai">Sesuai</option>
                            <option value="Belum Sesuai">Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_dpia" class="form-text-input" value="{{ old('nomor_dpia') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Klasifikasi DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_klasifikasi" class="form-text-input" style="width: 160px;">
                            <option value="Sesuai (Data Terbatas)">Sesuai (Data Terbatas)</option>
                            <option value="Terbuka">Terbuka</option>
                            <option value="Rahasia">Rahasia</option>
                        </select>
                        <input type="text" name="nomor_klasifikasi" class="form-text-input" value="{{ old('nomor_klasifikasi') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>

                <div>
                    <label class="form-group-label">Hak Akses DTSEN</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="status_hak_akses" class="form-text-input" style="width: 140px;">
                            <option value="Diverifikasi">Diverifikasi</option>
                            <option value="Belum Sesuai">Belum Sesuai</option>
                        </select>
                        <input type="text" name="nomor_hak_akses" class="form-text-input" value="{{ old('nomor_hak_akses') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
                    </div>
                </div>
            </div>

            <div class="form-2col-grid" style="margin-top: 18px;">
                <div>
                    <label class="form-group-label">Jadwal Pelaksanaan Serah Terima</label>
                    <input type="date" name="jadwal_serah_terima" class="form-text-input" value="{{ old('jadwal_serah_terima') }}">
                </div>

                <div>
                    <label class="form-group-label">No. Berita Acara Serah Terima (BAST)</label>
                    <input type="text" name="no_bast" class="form-text-input" value="{{ old('no_bast') }}" placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">
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
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('catatan_admin') }}</textarea>
            </div>
        </div>

        <!-- 7. HASIL PELAKSANAAN PEKERJAAN -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">7. Hasil Pelaksanaan Pekerjaan</h2>
            </div>

            <div>
                <textarea name="hasil_pekerjaan" 
                          id="hasil_pekerjaan"
                          rows="3" 
                          class="form-text-input" 
                          placeholder="Isi jika ada, atau beri tanda strip (-) bila tidak ada">{{ old('hasil_pekerjaan') }}</textarea>
            </div>
        </div>

        <!-- 8. UNGGAH BERKAS LAMPIRAN SURAT EDARAN RESMI -->
        <div class="form-section-card">
            <div class="section-head-box" style="margin-bottom: 14px;">
            <h2 class="section-head-title">8. Lampiran Berkas Permohonan Resmi</h2>
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
                <span>Terbitkan Permintaan Bagi Pakai & Siapkan Formulir SOP</span>
            </button>
        </div>

    </form>

</div>

@section('scripts')
<script>
    function fillOpdData(select) {
        const selected = select.options[select.selectedIndex];
        if (selected && selected.value) {
            const instansi = selected.value;
            document.getElementById('nama_instansi').value = instansi;
            
            if (selected.getAttribute('data-pic')) {
                document.getElementById('nama_pemohon').value = selected.getAttribute('data-pic');
            }
            if (selected.getAttribute('data-kontak')) {
                document.getElementById('kontak_pemohon').value = selected.getAttribute('data-kontak');
            }
            if (selected.getAttribute('data-email')) {
                document.getElementById('email_pemohon').value = selected.getAttribute('data-email');
            }
            
            // Deskripsi & hasil tetap placeholder jika tidak diisi manual
        }
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

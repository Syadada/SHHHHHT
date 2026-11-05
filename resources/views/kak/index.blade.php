@extends('layouts.app')

@section('title', 'Formulir Pengajuan Dokumen KAK')
@section('header_title', 'Formulir Berkas KAK & Lembar Pengesahan (Offline)')

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px; max-width: 1100px; margin: 0 auto;">

    <!-- 1. Formulir Utama Entri Berkas KAK -->
    <div class="card" style="border: 1px solid rgba(59, 130, 246, 0.3); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);">
        <div class="card-header" style="border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 24px;">
            <div>
                <div class="card-title" style="font-size: 18px;">
                    <svg style="width: 22px; height: 22px; color: #60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Formulir Entri Berkas Kerangka Acuan Kerja (KAK)</span>
                </div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                    Silakan unggah dokumen KAK dalam format PDF, Word (.docx), atau arsip. Dokumen dapat langsung dicetak lembar pengesahannya secara offline.
                </div>
            </div>
            <div>
                <span class="badge badge-success">Sistem Siap Offline</span>
            </div>
        </div>

        <form action="{{ route('kak.store') }}" method="POST" enctype="multipart/form-data" id="kakForm">
            @csrf

            <!-- Area Unggah Berkas KAK (File Field Utama) -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="font-size: 14px; font-weight: 700; color: #fff; display: flex; justify-content: space-between;">
                    <span>Pilih Berkas KAK (File Field) <span style="color: #ef4444;">*</span></span>
                    <span style="font-size: 12px; color: var(--text-muted); font-weight: normal;">Format: PDF, DOC, DOCX, XLS, XLSX (Maks. 500MB)</span>
                </label>

                <div style="border: 2px dashed rgba(59, 130, 246, 0.4); background: rgba(15, 23, 42, 0.6); border-radius: 14px; padding: 32px 20px; text-align: center; position: relative; transition: all 0.2s ease;" id="dropzoneBox">
                    <input type="file" name="file_kak" id="file_kak_input" required 
                           style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                           onchange="handleFileSelect(this)">
                    
                    <div id="dropzonePrompt">
                        <div style="width: 54px; height: 54px; margin: 0 auto 14px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; color: #60a5fa;">
                            <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <div style="font-size: 15px; font-weight: 700; color: #fff;">Klik di sini atau Seret Berkas KAK ke Area Ini</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 6px;">Pilih dokumen Kerangka Acuan Kerja dari komputer Anda</div>
                    </div>

                    <div id="fileInfoBox" style="display: none; background: rgba(30, 41, 59, 0.85); border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 10px; padding: 14px 20px; text-align: left; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; color: #34d399; font-weight: 800; font-size: 12px;" id="fileExtBadge">
                                PDF
                            </div>
                            <div>
                                <div id="selectedFileName" style="font-size: 14px; font-weight: 700; color: #fff;">nama_berkas.pdf</div>
                                <div id="selectedFileSize" style="font-size: 12px; color: var(--text-muted); font-family: var(--font-mono); margin-top: 2px;">0 KB</div>
                            </div>
                        </div>
                        <span class="badge badge-success">Berkas Terpilih</span>
                    </div>
                </div>
            </div>

            <!-- Field Pendukung Tambahan (Judul & Penanggung Jawab) -->
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Judul KAK / Nama Kegiatan</label>
                    <input type="text" name="judul_kak" id="judul_kak" placeholder="Contoh: KAK Pengadaan Server & Mini Data Center 2026" class="form-control" value="{{ old('judul_kak') }}">
                    <small style="color: var(--text-muted); font-size: 11px;">Otomatis disesuaikan dari nama berkas jika dikosongkan</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Pengusul / Penanggung Jawab (PPTK)</label>
                    <input type="text" name="penanggung_jawab" placeholder="Contoh: Ir. Andi Wijaya, M.T." class="form-control" value="{{ old('penanggung_jawab') }}">
                </div>
            </div>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Unit / Bidang Kerja</label>
                    <input type="text" name="unit_kerja" placeholder="Contoh: Bidang Infrastruktur & TI" class="form-control" value="{{ old('unit_kerja') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pengajuan</label>
                    <input type="date" name="tanggal_pengajuan" value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan / Ringkasan KAK (Opsional)</label>
                <textarea name="keterangan" rows="2" class="form-control" placeholder="Rincian ringkas latar belakang pengajuan KAK...">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Tombol Aksi (Simpan & Tombol Print) -->
            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 14px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <button type="button" onclick="printBlankForm()" class="btn btn-secondary">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Blanko Formulir KOSONG</span>
                </button>

                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-size: 14px;">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan & Registrasi Berkas KAK</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Riwayat Dokumen KAK Terdaftar & Tombol Print -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg style="width: 20px; height: 20px; color: var(--accent-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Daftar Berkas KAK Terdaftar ({{ $kaks->count() }} Dokumen)</span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
                Setiap dokumen dapat dicetak lembar pengesahannya dengan menekan <strong>Tombol Print</strong>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 150px;">No Registrasi</th>
                        <th>Judul & Dokumen KAK</th>
                        <th>Pengusul / Unit</th>
                        <th style="width: 100px;">Ukuran</th>
                        <th style="width: 110px;">Tanggal</th>
                        <th style="text-align: center; width: 170px;">Aksi (Print & File)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kaks as $item)
                    <tr>
                        <td>
                            <div style="font-family: var(--font-mono); font-weight: 700; color: #93c5fa; font-size: 12px;">
                                {{ $item->nomor_registrasi }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #fff; font-size: 14px;">{{ $item->judul_kak }}</div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                <span class="badge badge-primary" style="font-family: var(--font-mono); font-size: 10px; text-transform: uppercase;">
                                    {{ $item->ekstensi_file ?? 'FILE' }}
                                </span>
                                <span style="font-size: 12px; color: var(--text-secondary);">{{ $item->nama_file_asli }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #e2e8f0; font-size: 13px;">{{ $item->penanggung_jawab }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $item->unit_kerja ?? '-' }}</div>
                        </td>
                        <td style="font-family: var(--font-mono); color: #cbd5e1; font-size: 12px;">
                            {{ $item->ukuran_file ?? '-' }}
                        </td>
                        <td style="font-family: var(--font-mono); color: var(--text-secondary); font-size: 12px;">
                            {{ date('d/m/Y', strtotime($item->tanggal_pengajuan)) }}
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <!-- Tombol Print Utama -->
                                <a href="{{ route('kak.cetak', $item->id) }}" target="_blank" class="btn btn-primary btn-sm" title="Cetak Lembar Pengesahan KAK">
                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Print</span>
                                </a>

                                @if($item->file_kak)
                                <a href="{{ route('kak.download', $item->id) }}" class="btn btn-secondary btn-sm" title="Unduh Berkas Asli">
                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                @endif

                                <form action="{{ route('kak.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen KAK ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color: #f87171;" title="Hapus">
                                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 36px;">
                            Belum ada dokumen KAK yang didaftarkan. Silakan gunakan formulir di atas untuk mengunggah dokumen KAK pertama Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/app.js') }}"></script>
@endsection

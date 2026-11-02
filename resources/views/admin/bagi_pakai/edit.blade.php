@extends('layouts.admin')

@section('title', 'Edit Permintaan Layanan #' . $tiket->nomor_tiket . ' — Bagi Pakai DTSEN')

@section('content')
<div class="admin-form-container" style="max-width: 900px; margin: 0 auto;">

    <!-- Breadcrumb -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}">Layanan Bagi Pakai DTSEN</a>
        <span class="separator">/</span>
        <span class="current">Edit Permintaan #{{ $tiket->nomor_tiket }}</span>
    </nav>

    <div class="dash-card">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h1 class="dash-card-title" style="font-size: 19px; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fi fi-rr-edit" style="color: var(--blue-primary);"></i>
                        <span>Edit Permintaan Layanan Bagi Pakai DTSEN</span>
                    </h1>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px;">
                        #{{ $tiket->nomor_tiket }}
                    </span>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        SOP: {{ $noSop }}
                    </span>
                </div>
                <p class="dash-card-subtitle" style="margin: 0;">
                    Perbarui informasi permohonan layanan, kelengkapan berkas legalitas, dan status tahapan proses.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline" style="font-size: 12px; padding: 6px 14px; text-decoration: none;">
                    <i class="fi fi-rr-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-box alert-error" style="margin-top: 16px;">
                <i class="fi fi-rr-cross-circle"></i>
                <div style="font-size: 13px;">
                    <strong>Terdapat kesalahan pada isian form:</strong>
                    <ul style="margin: 6px 0 0 16px; padding: 0;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.bagi-pakai-dtsen.update', $tiket->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            @csrf
            @method('PUT')

            <!-- 1. IDENTITAS OPD & SURAT EDARAN -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-building" style="color: var(--blue-primary);"></i>
                <span>1. Data Perangkat Daerah (OPD) & Surat Edaran</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Nama Instansi / OPD Pemohon <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $tiket->nama_instansi) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Status Permintaan Layanan <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="status" class="form-select">
                        <option value="DIPROSES" {{ old('status', $tiket->status) === 'DIPROSES' ? 'selected' : '' }}>Sedang Berjalan (DIPROSES)</option>
                        <option value="SELESAI" {{ old('status', $tiket->status) === 'SELESAI' ? 'selected' : '' }}>Selesai 100% (SELESAI)</option>
                        <option value="PERLU_PERBAIKAN" {{ old('status', $tiket->status) === 'PERLU_PERBAIKAN' ? 'selected' : '' }}>Perlu Perbaikan (REVISI)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 12px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Nomor Surat Edaran / Permohonan Resmi <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="no_surat_edaran" value="{{ old('no_surat_edaran', $tiket->no_surat_edaran) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Perbarui Berkas Surat Edaran <span style="color: #64748b; font-weight: 400;">(Opsional, PDF max 50MB)</span>
                    </label>
                    <input type="file" name="file_surat" class="form-input" accept=".pdf,.doc,.docx" style="padding: 6px;">
                    @if($tiket->file_surat)
                        <div style="margin-top: 4px; font-size: 12px; color: #64748b;">
                            Berkas tersimpan: <a href="{{ route('admin.riwayat.download', $tiket->id) }}" style="color: #0b57d0; text-decoration: none; font-weight: 600;">{{ $tiket->nama_file_asli ?: 'Lihat Berkas' }}</a> ({{ $tiket->ukuran_file ?: '-' }})
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. DATA PIC PEMOHON -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 24px 0 14px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-user" style="color: var(--blue-primary);"></i>
                <span>2. Kontak Narahubung / PIC Pemohon</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Nama PIC Pemohon</label>
                    <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon', $tiket->nama_pemohon) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="kontak_pemohon" value="{{ old('kontak_pemohon', $tiket->kontak_pemohon) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Alamat Email</label>
                    <input type="email" name="email_pemohon" value="{{ old('email_pemohon', $tiket->email_pemohon) }}" class="form-input">
                </div>
            </div>

            <!-- 3. TUJUAN & VARIABEL DTSEN -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 24px 0 14px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-database" style="color: var(--blue-primary);"></i>
                <span>3. Tujuan Pemanfaatan & Variabel Data DTSEN</span>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Tujuan Pemanfaatan Data</label>
                <textarea name="tujuan_pemanfaatan" rows="3" class="form-input">{{ old('tujuan_pemanfaatan', $tiket->tujuan_pemanfaatan) }}</textarea>
            </div>

            <div class="form-group" style="margin-top: 14px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px; margin: 0;">
                        Daftar Variabel Data DTSEN yang Dimohonkan <span style="color: #dc2626;">*</span>
                    </label>
                    <button type="button" class="btn-pill-outline" onclick="addVariabelRow()" style="font-size: 11px; padding: 3px 10px;">
                        <i class="fi fi-rr-plus"></i> Tambah Variabel
                    </button>
                </div>

                <div id="variabel-container" style="display: flex; flex-direction: column; gap: 8px;">
                    @php
                        $vars = old('variabel_dtsen', is_array($tiket->variabel_dtsen) ? $tiket->variabel_dtsen : []);
                        if (empty($vars)) $vars = ['NIK, Nama Kepala Keluarga, Alamat Lengkap'];
                    @endphp
                    @foreach($vars as $v)
                        <div class="variabel-row" style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" name="variabel_dtsen[]" value="{{ $v }}" class="form-input" placeholder="Nama variabel atau atribut data..." required>
                            <button type="button" class="btn-row-action delete" onclick="removeVariabelRow(this)" style="padding: 7px 10px;" title="Hapus Baris">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Catatan Admin / Verifikator</label>
                <textarea name="catatan_admin" rows="2" class="form-input" placeholder="Catatan atau instruksi perbaikan untuk OPD...">{{ old('catatan_admin', $tiket->catatan_admin) }}</textarea>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="margin-top: 30px; padding-top: 16px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline" style="padding: 9px 18px; text-decoration: none;">
                    Batal
                </a>
                <button type="submit" class="btn-pill-primary" style="padding: 9px 22px; font-weight: 700;">
                    <i class="fi fi-rr-check"></i> Simpan Pembaruan
                </button>
            </div>
        </form>
    </div>

</div>

@section('scripts')
<script>
function addVariabelRow() {
    const container = document.getElementById('variabel-container');
    const div = document.createElement('div');
    div.className = 'variabel-row';
    div.style = 'display: flex; gap: 8px; align-items: center;';
    div.innerHTML = `
        <input type="text" name="variabel_dtsen[]" class="form-input" placeholder="Nama variabel atau atribut data..." required>
        <button type="button" class="btn-row-action delete" onclick="removeVariabelRow(this)" style="padding: 7px 10px;" title="Hapus Baris">
            <i class="fi fi-rr-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeVariabelRow(btn) {
    const container = document.getElementById('variabel-container');
    if (container.querySelectorAll('.variabel-row').length > 1) {
        btn.closest('.variabel-row').remove();
    } else {
        alert('Minimal satu variabel data DTSEN wajib diisi.');
    }
}
</script>
@endsection
@endsection

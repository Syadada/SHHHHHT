@extends('layouts.admin')

@section('title', 'Buat Permintaan Layanan Bagi Pakai DTSEN — SOP: 555/07-SOP/DISKOMINFO SP/2026')

@section('content')
<div class="admin-form-container" style="max-width: 900px; margin: 0 auto;">

    <!-- Breadcrumb -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}">Layanan Bagi Pakai DTSEN</a>
        <span class="separator">/</span>
        <span class="current">Buat Permintaan Layanan</span>
    </nav>

    <div class="dash-card">
        <div class="dash-card-header" style="border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h1 class="dash-card-title" style="font-size: 19px; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fi fi-rr-document-signed" style="color: var(--blue-primary);"></i>
                        <span>Buat Permintaan Layanan Bagi Pakai DTSEN</span>
                    </h1>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="fi fi-rr-document"></i> SOP: {{ $noSop }}
                    </span>
                </div>
                <p class="dash-card-subtitle" style="margin: 0;">
                    Fasilitasi pembuatan permohonan layanan bagi pakai DTSEN atas nama OPD yang tidak dapat atau terkendala membuat tiket mandiri.
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

        <form action="{{ route('admin.bagi-pakai-dtsen.store') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
            @csrf

            <!-- 1. IDENTITAS OPD & SURAT EDARAN -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-building" style="color: var(--blue-primary);"></i>
                <span>1. Data Perangkat Daerah (OPD) & Surat Edaran</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Pilih Akun OPD Terdaftar <span style="color: #64748b; font-weight: 400;">(Opsional)</span>
                    </label>
                    <select id="opd_select" class="form-select" onchange="fillOpdData(this)">
                        <option value="">-- Pilih dari Akun Terdaftar atau Ketik di Bawah --</option>
                        @foreach($opdUsers as $opd)
                            <option value="{{ $opd->nama_instansi }}" 
                                    data-pic="{{ $opd->name }}" 
                                    data-kontak="{{ $opd->nomor_kontak }}" 
                                    data-email="{{ $opd->email }}">
                                {{ $opd->nama_instansi ?: $opd->username }} ({{ $opd->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Nama Instansi / OPD Pemohon <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi') }}" class="form-input" placeholder="Contoh: Dinas Sosial Kabupaten / Bappeda" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 12px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Nomor Surat Edaran / Permohonan Resmi <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="no_surat_edaran" value="{{ old('no_surat_edaran') }}" class="form-input" placeholder="Contoh: 050/123/DINSOS/2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">
                        Unggah Berkas Surat Edaran <span style="color: #64748b; font-weight: 400;">(PDF, Maks. 50MB)</span>
                    </label>
                    <input type="file" name="file_surat" class="form-input" accept=".pdf,.doc,.docx" style="padding: 6px;">
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
                    <input type="text" name="nama_pemohon" id="nama_pemohon" value="{{ old('nama_pemohon') }}" class="form-input" placeholder="Nama lengkap petugas OPD">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="kontak_pemohon" id="kontak_pemohon" value="{{ old('kontak_pemohon') }}" class="form-input" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Alamat Email</label>
                    <input type="email" name="email_pemohon" id="email_pemohon" value="{{ old('email_pemohon') }}" class="form-input" placeholder="pic@pemda.go.id">
                </div>
            </div>

            <!-- 3. TUJUAN & VARIABEL DTSEN -->
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 24px 0 14px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fi fi-rr-database" style="color: var(--blue-primary);"></i>
                <span>3. Tujuan Pemanfaatan & Variabel Data DTSEN</span>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Tujuan Pemanfaatan Data</label>
                <textarea name="tujuan_pemanfaatan" rows="3" class="form-input" placeholder="Jelaskan kebutuhan dan tujuan penggunaan data DTSEN oleh OPD...">{{ old('tujuan_pemanfaatan') }}</textarea>
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
                        $oldVars = old('variabel_dtsen', ['NIK, Nama Kepala Keluarga, Alamat Lengkap', 'Desil Kesejahteraan / Tingkat Kemiskinan']);
                    @endphp
                    @foreach($oldVars as $v)
                        <div class="variabel-row" style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" name="variabel_dtsen[]" value="{{ $v }}" class="form-input" placeholder="Nama variabel atau atribut data..." required>
                            <button type="button" class="btn-row-action delete" onclick="removeVariabelRow(this)" style="padding: 7px 10px;" title="Hapus Baris">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="margin-top: 30px; padding-top: 16px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline" style="padding: 9px 18px; text-decoration: none;">
                    Batal
                </a>
                <button type="submit" class="btn-pill-primary" style="padding: 9px 22px; font-weight: 700;">
                    <i class="fi fi-rr-check"></i> Simpan & Terbitkan Permintaan
                </button>
            </div>
        </form>
    </div>

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

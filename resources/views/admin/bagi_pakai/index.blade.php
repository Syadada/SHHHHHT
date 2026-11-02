@extends('layouts.admin')

@section('title', 'Layanan Bagi Pakai DTSEN — SOP: 555/07-SOP/DISKOMINFO SP/2026')

@section('content')
@php
    $currentSort = request('sort', 'terbaru');
    $sortOptions = [
        'terbaru' => ['icon' => 'fi-rr-clock', 'label' => 'Dibuat: Terbaru (Desc)'],
        'terlama' => ['icon' => 'fi-rr-time-past', 'label' => 'Dibuat: Terlama (Asc)'],
        'updated_desc' => ['icon' => 'fi-rr-refresh', 'label' => 'Terakhir Diperbarui (Desc)'],
        'updated_asc' => ['icon' => 'fi-rr-history', 'label' => 'Terlama Diperbarui (Asc)'],
        'alphabet_asc' => ['icon' => 'fi-rr-sort-alpha-down', 'label' => 'Instansi: A → Z (Asc)'],
        'alphabet_desc' => ['icon' => 'fi-rr-sort-alpha-up', 'label' => 'Instansi: Z → A (Desc)'],
        'tiket_asc' => ['icon' => 'fi-rr-sort-numeric-down', 'label' => 'No. Tiket: Asc (A → Z)'],
        'tiket_desc' => ['icon' => 'fi-rr-sort-numeric-up', 'label' => 'No. Tiket: Desc (Z → A)'],
        'progress_desc' => ['icon' => 'fi-rr-chart-histogram', 'label' => 'Progress: Tertinggi (100% → 0%)'],
        'progress_asc' => ['icon' => 'fi-rr-chart-line-down', 'label' => 'Progress: Terendah (0% → 100%)'],
    ];
    $activeSort = $sortOptions[$currentSort] ?? $sortOptions['terbaru'];
@endphp

<div class="ticket-stack-container">

    <!-- Breadcrumb -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <span class="current">Layanan Bagi Pakai DTSEN</span>
    </nav>

    <!-- Header Section Card -->
    <div class="dash-card form-group-mb-20">
        <div class="dash-card-header" style="flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
                    <h1 class="dash-card-title" style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                        <i class="fi fi-rr-share" style="color: var(--blue-primary);"></i>
                        <span>Layanan Bagi Pakai DTSEN</span>
                    </h1>
                    <span class="dtsen-chip" style="font-family: var(--font-mono); font-size: 11px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                        <i class="fi fi-rr-document"></i> SOP: {{ $noSop }}
                    </span>
                </div>
                <p class="dash-card-subtitle" style="margin: 0; max-width: 760px;">
                    Modul pengelolaan dan pembuatan permohonan layanan bagi pakai DTSEN bagi OPD pengguna layanan yang mengalami kendala teknis atau tidak dapat membuat tiket mandiri.
                </p>
            </div>

            <div>
                <a href="{{ route('admin.bagi-pakai-dtsen.create') }}" class="btn-pill-primary" style="padding: 10px 18px; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fi fi-rr-plus"></i>
                    <span>Buat Permintaan Layanan</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="dash-metric-grid" style="margin-top: 20px;">
            <div class="dash-metric-card">
                <div class="dash-metric-label">Total Permintaan</div>
                <div class="dash-metric-value">{{ number_format($totalPermintaan ?? 0) }}</div>
                <div class="dash-metric-sub">Seluruh permohonan OPD</div>
            </div>

            <div class="dash-metric-card">
                <div class="dash-metric-label">Sedang Berjalan</div>
                <div class="dash-metric-value" style="color: var(--blue-primary);">{{ number_format($totalProses ?? 0) }}</div>
                <div class="dash-metric-sub">Tahapan aktif verifikasi</div>
            </div>

            <div class="dash-metric-card">
                <div class="dash-metric-label">Selesai (100%)</div>
                <div class="dash-metric-value" style="color: var(--green-primary);">{{ number_format($totalSelesai ?? 0) }}</div>
                <div class="dash-metric-sub">BAST resmi terbit</div>
            </div>

            <div class="dash-metric-card">
                <div class="dash-metric-label">Perlu Perbaikan</div>
                <div class="dash-metric-value" style="color: #b45309;">{{ number_format($totalRevisi ?? 0) }}</div>
                <div class="dash-metric-sub">Menunggu revisi berkas SE</div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="ticket-portal-card">
        
        <!-- Filter & Search Bar -->
        <div class="ticket-portal-header">
            <div>
                <h2 class="ticket-portal-title">
                    <i class="fi fi-rr-list-check color-status-progress"></i>
                    <span>Daftar Permintaan Layanan OPD</span>
                </h2>
                <div class="portal-subtitle">
                    Klik pada <strong>Nomor Tiket</strong> atau <strong>Nama Instansi</strong> untuk melihat progress detail serta proses verifikasi.
                </div>
            </div>

            <div class="ticket-filter-bar">
                <form action="{{ route('admin.bagi-pakai-dtsen.index') }}" method="GET" class="ticket-filter-form">
                    <div class="ticket-search-box">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Tiket / Instansi / SE..." class="form-input ticket-search-input">
                        <i class="fi fi-rr-search ticket-search-icon"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="form-select ticket-filter-select">
                        <option value="">Semua Status</option>
                        <option value="PROSES" {{ request('status') === 'PROSES' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="SELESAI" {{ request('status') === 'SELESAI' ? 'selected' : '' }}>Selesai (100%)</option>
                        <option value="REVISI" {{ request('status') === 'REVISI' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>

                    <!-- Custom Flaticon Sort Dropdown -->
                    <div class="custom-flaticon-dropdown" id="sort-flaticon-dropdown">
                        <button type="button" class="custom-flaticon-btn" id="sort-dropdown-toggle" title="Urutkan Data">
                            <i class="fi {{ $activeSort['icon'] }} sort-active-icon"></i>
                            <span>{{ $activeSort['label'] }}</span>
                            <i class="fi fi-rr-angle-small-down sort-caret"></i>
                        </button>

                        <div class="custom-flaticon-menu">
                            <div class="custom-flaticon-header">
                                <i class="fi fi-rr-sort-alt"></i> Urutkan Berdasarkan
                            </div>

                            @foreach($sortOptions as $key => $opt)
                                @if(in_array($key, ['updated_desc', 'alphabet_asc', 'tiket_asc', 'progress_desc']))
                                    <div class="custom-flaticon-divider"></div>
                                @endif
                                <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->query(), ['sort' => $key])) }}" 
                                   class="custom-flaticon-item {{ $currentSort === $key ? 'active' : '' }}">
                                    <i class="fi {{ $opt['icon'] }}"></i>
                                    <span>{{ $opt['label'] }}</span>
                                    @if($currentSort === $key)
                                        <i class="fi fi-rr-check sort-check"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if(request('search') || request('status') || (request('sort') && request('sort') !== 'terbaru'))
                        <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" class="btn-pill-outline" style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 10px; font-size: 12px; color: #64748b; text-decoration: none;" title="Reset Filter">
                            <i class="fi fi-rr-refresh"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Permintaan Layanan -->
        <div class="table-responsive">
            <table class="data-table" style="width: 100%; table-layout: auto;">
                <thead>
                    <tr>
                        <th style="width: 135px;">
                            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->query(), ['sort' => request('sort') === 'tiket_asc' ? 'tiket_desc' : 'tiket_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Urutkan No. Tiket">
                                <span>No. Tiket</span>
                                @if(request('sort') === 'tiket_asc')
                                    <i class="fi fi-rr-sort-numeric-down" style="color: var(--blue-primary); font-size: 12px;"></i>
                                @elseif(request('sort') === 'tiket_desc')
                                    <i class="fi fi-rr-sort-numeric-up" style="color: var(--blue-primary); font-size: 12px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 11px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->query(), ['sort' => request('sort') === 'alphabet_asc' ? 'alphabet_desc' : 'alphabet_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Urutkan Instansi">
                                <span>Instansi Pemohon</span>
                                @if(request('sort') === 'alphabet_asc')
                                    <i class="fi fi-rr-sort-alpha-down" style="color: var(--blue-primary); font-size: 12px;"></i>
                                @elseif(request('sort') === 'alphabet_desc')
                                    <i class="fi fi-rr-sort-alpha-up" style="color: var(--blue-primary); font-size: 12px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 11px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width: 160px;">Surat Edaran</th>
                        <th style="width: 140px;">Variabel DTSEN</th>
                        <th style="width: 130px;">Status & Progress</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tikets as $item)
                        @php
                            $activeStepNum = 1;
                            if (!$item->step1_done) $activeStepNum = 1;
                            elseif (!$item->step2_done) $activeStepNum = 2;
                            elseif (!$item->step3_done) $activeStepNum = 3;
                            elseif (!$item->step4_done) $activeStepNum = 4;
                            elseif (!$item->step5_done) $activeStepNum = 5;
                            else $activeStepNum = 6;
                        @endphp
                        <tr>
                            <!-- 1. Nomor Tiket -->
                            <td>
                                <div>
                                    <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: var(--blue-primary); font-size: 13px; text-decoration: none;" title="Buka Detail Tiket">
                                        {{ $item->nomor_tiket }}
                                    </a>
                                </div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">
                                    {{ $item->created_at->format('d/m/Y H:i') }} WIB
                                </div>
                            </td>

                            <!-- 2. Nama Instansi & PIC -->
                            <td>
                                <div>
                                    <a href="{{ route('admin.tiket.show', $item->id) }}" class="instansi-title-link" title="Klik untuk membuka informasi selengkapnya">
                                        <span>{{ $item->nama_instansi }}</span>
                                    </a>
                                </div>
                                @if($item->nama_pemohon)
                                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">
                                        PIC: <strong>{{ $item->nama_pemohon }}</strong> ({{ $item->kontak_pemohon ?: '-' }})
                                    </div>
                                @endif
                            </td>

                            <!-- 3. Surat Edaran -->
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 13px;">
                                    {{ $item->no_surat_edaran }}
                                </div>
                                @if($item->file_surat)
                                    <div style="margin-top: 4px;">
                                        <a href="{{ route('admin.riwayat.download', $item->id) }}" class="btn-pill-outline" style="font-size: 11px; padding: 2px 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Berkas Surat Edaran">
                                            <i class="fi fi-rr-download"></i> Berkas SE
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <!-- 4. Variabel DTSEN & Status Verifikasi -->
                            <td>
                                <div style="margin-bottom: 5px;">
                                    <span class="dtsen-chip" style="font-size: 11px; font-weight: 700; color: var(--blue-primary); background: var(--blue-light); padding: 3px 8px; border-radius: 6px;">
                                        {{ count($item->variabel_dtsen ?? []) }} Variabel
                                    </span>
                                </div>
                                @if($item->status === 'PERLU_PERBAIKAN')
                                    <span style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-exclamation"></i> Perlu Perbaikan
                                    </span>
                                @elseif($item->step1_done)
                                    <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-check"></i> Sesuai SE
                                    </span>
                                @else
                                    <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-time-quarter-to"></i> Menunggu Cek
                                    </span>
                                @endif
                            </td>

                            <!-- 5. Status & Progress -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="progress-track" style="height: 8px; width: 75px;">
                                        <div class="progress-fill" style="width: {{ $item->progress_percent }}%;"></div>
                                    </div>
                                    <span style="font-size: 12px; font-weight: 700; color: {{ $item->step5_done ? '#1e8e3e' : '#0b57d0' }};">
                                        {{ $item->progress_percent }}%
                                    </span>
                                </div>
                                <div style="font-size: 11px; margin-top: 4px; font-weight: 600; color: {{ $item->step5_done ? '#1e8e3e' : '#444746' }};">
                                    @if($item->step5_done)
                                        <span style="color: #1e8e3e; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fi fi-rr-check"></i> Selesai 100%
                                        </span>
                                    @else
                                        Progress {{ $activeStepNum }}/5
                                    @endif
                                </div>
                            </td>

                            <!-- 6. Aksi (Edit, Hapus) -->
                            <td style="text-align: center;">
                                <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: center;">
                                    <!-- Tombol Edit Permintaan -->
                                    <a href="{{ route('admin.bagi-pakai-dtsen.edit', $item->id) }}" class="btn-pill-outline" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: #0b57d0; border-color: #bfdbfe; background: #eff6ff; display: inline-flex; align-items: center; gap: 3px;" title="Edit Permintaan Layanan">
                                        <i class="fi fi-rr-edit" style="font-size: 11px;"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Tombol Hapus Permintaan -->
                                    <form action="{{ route('admin.bagi-pakai-dtsen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permintaan layanan tiket [{{ $item->nomor_tiket }}] milik {{ $item->nama_instansi }}? Tindakan ini tidak dapat dibatalkan.');" style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-row-action delete" style="padding: 4px 7px;" title="Hapus Permintaan Layanan">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                                <div style="font-size: 32px; margin-bottom: 8px; color: #94a3b8;"><i class="fi fi-rr-share"></i></div>
                                <div style="font-weight: 700; font-size: 14px; color: var(--text-primary);">Belum Ada Permintaan Layanan Bagi Pakai DTSEN</div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                                    Klik tombol <strong>"Buat Permintaan Layanan"</strong> di atas untuk membuat permohonan atas nama OPD.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tikets->hasPages())
            <div class="ticket-portal-pagination" style="padding: 16px 20px; border-top: 1px solid var(--border-light);">
                {{ $tikets->links() }}
            </div>
        @endif

    </div>

</div>
@endsection


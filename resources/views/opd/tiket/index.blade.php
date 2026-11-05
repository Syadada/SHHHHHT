@extends('layouts.opd')

@section('title', 'Layanan Bagi Pakai DTSEN — Portal Perangkat Daerah')
@section('page_title', 'Layanan')
@section('page_subtitle', 'Daftar Permintaan Layanan Bagi Pakai DTSEN Instansi Anda')

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

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb">
        <a href="{{ route('opd.dashboard') }}"><i class="fi fi-rr-home"></i> Dashboard</a>
        <span class="separator">/</span>
        <span class="current">Layanan</span>
    </nav>

    <!-- Support Tickets Card (DomaiNesia Style) -->
    <div class="ticket-portal-card">
        
        <!-- Header & Filter Bar -->
        <div class="ticket-portal-header">
            <div>
                <h2 class="ticket-portal-title">
                    <i class="fi fi-rr-share color-status-progress"></i>
                    <span>Daftar Permintaan Layanan Bagi Pakai</span>
                </h2>
                <div class="portal-subtitle">
                    Klik pada <strong>Nomor Permintaan / Tiket</strong> untuk memantau rincian alur progress tahapan secara langsung.
                </div>
            </div>

            <div class="ticket-filter-bar">
                <form action="{{ route('opd.tiket.index') }}" method="GET" class="ticket-filter-form">
                    <div class="ticket-search-box">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Permintaan / SE..." class="form-input ticket-search-input">
                        <i class="fi fi-rr-search ticket-search-icon"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="form-select ticket-filter-select">
                        <option value="">Semua Status</option>
                        <option value="PROSES" {{ request('status') === 'PROSES' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="SELESAI" {{ request('status') === 'SELESAI' ? 'selected' : '' }}>Selesai (100%)</option>
                        <option value="REVISI" {{ request('status') === 'REVISI' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>

                    <!-- Custom Flaticon Sort Dropdown (Zero OS Emojis, 100% Flat Icon) -->
                    <div class="custom-flaticon-dropdown" id="sort-flaticon-dropdown">
                        <button type="button" class="custom-flaticon-btn" id="sort-dropdown-toggle" title="Urutkan Data Tiket">
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
                                <a href="{{ route('opd.tiket.index', array_merge(request()->query(), ['sort' => $key])) }}" 
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
                        <a href="{{ route('opd.tiket.index') }}" class="btn-pill-outline" style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 10px; font-size: 12px; color: #64748b; text-decoration: none;" title="Reset Semua Filter">
                            <i class="fi fi-rr-cross-small"></i> Reset
                        </a>
                    @endif
                </form>

                <a href="{{ route('opd.tiket.create') }}" class="btn-pill-primary ticket-btn-back">
                    <i class="fi fi-rr-plus"></i> Permintaan Bagi Pakai
                </a>
            </div>
        </div>

        <!-- Tabel Tiket DomaiNesia Support Style -->
        <div class="table-responsive">
            <table class="ticket-portal-table">
                <thead>
                    <tr>
                        <th class="th-w-28">Nama Instansi</th>
                        <th class="th-w-32">Subject (Nomor Tiket)</th>
                        <th class="th-w-20">Status</th>
                        <th class="th-w-20">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tikets as $t)
                        <tr>
                            <!-- 1. Nama Instansi -->
                            <td>
                                <div class="ticket-table-instansi">
                                    {{ $t->nama_instansi }}
                                </div>
                                <div class="ticket-table-pic">
                                    PIC: {{ $t->nama_pemohon }}
                                </div>
                            </td>

                            <!-- 2. Subject (Nomor Tiket - CLICKABLE) -->
                            <td>
                                <a href="{{ route('opd.tiket.show', $t->id) }}" class="ticket-subject-link" title="Klik untuk melihat progress tiket ini">
                                    <span class="ticket-subject-num">#{{ $t->nomor_tiket }}</span>
                                </a>
                                <span class="ticket-subject-subtitle">
                                    SE: <strong>{{ $t->no_surat_edaran }}</strong> | {{ count($t->variabel_dtsen ?? []) }} Variabel
                                </span>
                            </td>

                            <!-- 3. Status Tiket -->
                            <td>
                                @if($t->status === 'PERLU_PERBAIKAN')
                                    <div>
                                        <span class="ticket-badge ticket-badge-warning">
                                            <i class="fi fi-rr-exclamation"></i> Perlu Perbaikan Berkas
                                        </span>
                                    </div>
                                    <div style="margin-top: 4px;">
                                        <a href="{{ route('opd.tiket.edit', $t->id) }}" class="btn-pill-outline" style="font-size: 11px; padding: 2px 8px; color: #dc2626; border-color: #fca5a5; background: #fff1f2; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fi fi-rr-edit"></i> Perbaiki Form
                                        </a>
                                    </div>
                                @elseif($t->status === 'PENDING')
                                    <span class="ticket-badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-time-forward"></i> Pending Verifikasi Berkas
                                    </span>
                                @elseif($t->isOpdStepDone(4) || $t->isCompleted())
                                    <span class="ticket-badge ticket-badge-success">
                                        <i class="fi fi-rr-check"></i> Selesai (100%)
                                    </span>
                                    <div style="margin-top: 4px;">
                                        <a href="{{ route('opd.tiket.formulir', $t->id) }}" target="_blank" class="btn-pill-outline" style="font-size: 11px; padding: 2px 8px; color: #15803d; border-color: #86efac; background: #f0fdf4; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Unduh Formulir Bukti Layanan Selesai">
                                            <i class="fi fi-rr-document-signed"></i> Unduh Formulir Bukti
                                        </a>
                                    </div>
                                @else
                                    <span class="ticket-badge ticket-badge-progress">
                                        <i class="fi fi-rr-time-forward"></i> {{ $t->current_opd_status_label }}
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Last Updated -->
                            <td>
                                <div class="ticket-table-updated">
                                    {{ $t->updated_at ? $t->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                                </div>
                                <div class="ticket-table-ago">
                                    {{ $t->updated_at ? $t->updated_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="ticket-empty-cell">
                                <div class="ticket-empty-icon">
                                    <i class="fi fi-rr-ticket"></i>
                                </div>
                                <div class="ticket-empty-title">Belum Ada Tiket Permohonan</div>
                                <div class="ticket-empty-desc form-group-mb-16">
                                    Anda belum memiliki tiket permohonan aktif atau sesuai filter yang dipilih.
                                </div>
                                <a href="{{ route('formulir.index') }}" class="btn-clean-cta ticket-btn-back">
                                    <i class="fi fi-rr-plus"></i> Permintaan Bagi Pakai Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tikets->hasPages())
            <div class="ticket-pagination-wrap">
                {{ $tikets->links() }}
            </div>
        @endif

    </div>

</div>
@endsection

@extends('layouts.admin')

@section('title', 'Daftar Tiket Support — Walidata')

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

    <!-- Breadcrumb Navigasi Admin -->
    <nav class="app-breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i class="fi fi-rr-apps"></i> Dashboard Admin</a>
        <span class="separator">/</span>
        <span class="current">Daftar Tiket Support</span>
    </nav>

    <!-- Support Tickets Card (DomaiNesia Style for Admin) -->
    <div class="ticket-portal-card">
        
        <!-- Header & Filter Bar -->
        <div class="ticket-portal-header">
            <div>
                <h2 class="ticket-portal-title">
                    <i class="fi fi-rr-ticket color-status-progress"></i>
                    <span>Semua Tiket Permohonan Perangkat Daerah</span>
                </h2>
                
            </div>

            <div class="ticket-filter-bar">
                <form action="{{ route('admin.tiket.index') }}" method="GET" class="ticket-filter-form">
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
                                <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => $key])) }}" 
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
                        <a href="{{ route('admin.tiket.index') }}" class="btn-pill-outline" style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 10px; font-size: 12px; color: #64748b; text-decoration: none;" title="Reset Semua Filter">
                            <i class="fi fi-rr-refresh"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Tiket DomaiNesia Support Style -->
        <div class="table-responsive">
            <table class="ticket-portal-table">
                <thead>
                    <tr>
                        <th class="th-w-28">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'alphabet_asc' ? 'alphabet_desc' : 'alphabet_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Klik untuk mengurutkan Abjad Instansi">
                                <span>Nama Instansi</span>
                                @if(request('sort') === 'alphabet_asc')
                                    <i class="fi fi-rr-sort-alpha-down" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @elseif(request('sort') === 'alphabet_desc')
                                    <i class="fi fi-rr-sort-alpha-up" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 12px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-32">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'tiket_asc' ? 'tiket_desc' : 'tiket_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Klik untuk mengurutkan Nomor Tiket">
                                <span>Subject (Nomor Tiket)</span>
                                @if(request('sort') === 'tiket_asc')
                                    <i class="fi fi-rr-sort-numeric-down" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @elseif(request('sort') === 'tiket_desc')
                                    <i class="fi fi-rr-sort-numeric-up" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 12px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-20">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'progress_desc' ? 'progress_asc' : 'progress_desc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Klik untuk mengurutkan Progress">
                                <span>Status</span>
                                @if(request('sort') === 'progress_desc')
                                    <i class="fi fi-rr-chart-histogram" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @elseif(request('sort') === 'progress_asc')
                                    <i class="fi fi-rr-chart-line-down" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 12px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-20">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'updated_desc' ? 'updated_asc' : 'updated_desc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Klik untuk mengurutkan Waktu Update">
                                <span>Last Updated</span>
                                @if(request('sort') === 'updated_desc')
                                    <i class="fi fi-rr-time-forward" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @elseif(request('sort') === 'updated_asc')
                                    <i class="fi fi-rr-time-past" style="color: var(--blue-primary); font-size: 13px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="color: #94a3b8; font-size: 12px; opacity: 0.5;"></i>
                                @endif
                            </a>
                        </th>
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
                                    PIC: {{ $t->nama_pemohon }} ({{ $t->email_pemohon }})
                                </div>
                            </td>

                            <!-- 2. Subject (Nomor Tiket - CLICKABLE) -->
                            <td>
                                <a href="{{ route('admin.tiket.show', $t->id) }}" class="ticket-subject-link" title="Buka Detail Progress Tiket">
                                    <span class="ticket-subject-num">#{{ $t->nomor_tiket }}</span>
                                </a>
                                <span class="ticket-subject-subtitle">
                                    SE: <strong>{{ $t->no_surat_edaran }}</strong> | {{ count($t->variabel_dtsen ?? []) }} Variabel Dimohonkan
                                </span>
                            </td>

                            <!-- 3. Status Tiket -->
                            <td>
                                @if($t->status === 'PERLU_PERBAIKAN')
                                    <span class="ticket-badge ticket-badge-warning">
                                        <i class="fi fi-rr-exclamation"></i> Perlu Perbaikan OPD
                                    </span>
                                @elseif($t->step5_done)
                                    <span class="ticket-badge ticket-badge-success">
                                        <i class="fi fi-rr-check"></i> Selesai (100%)
                                    </span>
                                @else
                                    <span class="ticket-badge ticket-badge-progress">
                                        <i class="fi fi-rr-time-forward"></i> {{ $t->current_status_label }}
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
                                <div class="ticket-empty-title">Tidak Ditemukan Tiket</div>
                                <div class="ticket-empty-desc">
                                    Belum ada tiket yang terdaftar atau sesuai kriteria pencarian yang Anda masukkan.
                                </div>
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

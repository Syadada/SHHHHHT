@extends('layouts.admin')

@section('title', 'Daftar Tiket Support — Admin Walidata')

@section('content')
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
                <div class="portal-subtitle">
                    Klik pada <strong>Nomor Tiket</strong> untuk melihat progress secara detail serta melakukan verifikasi & approval.
                </div>
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

                    <select name="sort" onchange="this.form.submit()" class="form-select ticket-filter-select" style="min-width: 195px;" title="Urutkan Data Tiket">
                        <option value="terbaru" {{ (request('sort') === 'terbaru' || (!request('sort') && !request('status'))) ? 'selected' : '' }}>🕒 Dibuat: Terbaru (Desc)</option>
                        <option value="terlama" {{ request('sort') === 'terlama' || request('sort') === 'asc' ? 'selected' : '' }}>⏳ Dibuat: Terlama (Asc)</option>
                        <option value="updated_desc" {{ request('sort') === 'updated_desc' ? 'selected' : '' }}>🔄 Terakhir Diperbarui (Desc)</option>
                        <option value="updated_asc" {{ request('sort') === 'updated_asc' ? 'selected' : '' }}>⌛ Terlama Diperbarui (Asc)</option>
                        <option value="alphabet_asc" {{ request('sort') === 'alphabet_asc' ? 'selected' : '' }}>🔤 Instansi: A → Z (Asc)</option>
                        <option value="alphabet_desc" {{ request('sort') === 'alphabet_desc' ? 'selected' : '' }}>🔠 Instansi: Z → A (Desc)</option>
                        <option value="tiket_asc" {{ request('sort') === 'tiket_asc' ? 'selected' : '' }}>🔢 No. Tiket: Asc (A → Z)</option>
                        <option value="tiket_desc" {{ request('sort') === 'tiket_desc' ? 'selected' : '' }}>🔢 No. Tiket: Desc (Z → A)</option>
                        <option value="progress_desc" {{ request('sort') === 'progress_desc' ? 'selected' : '' }}>📊 Progress: Tertinggi (100% → 0%)</option>
                        <option value="progress_asc" {{ request('sort') === 'progress_asc' ? 'selected' : '' }}>📉 Progress: Terendah (0% → 100%)</option>
                    </select>

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
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'alphabet_asc' ? 'alphabet_desc' : 'alphabet_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Klik untuk mengurutkan Abjad Instansi A-Z / Z-A">
                                <span>Nama Instansi</span>
                                @if(request('sort') === 'alphabet_asc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▲ (A-Z)</span>
                                @elseif(request('sort') === 'alphabet_desc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▼ (Z-A)</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px; opacity: 0.6;">⇅</span>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-32">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'tiket_asc' ? 'tiket_desc' : 'tiket_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Klik untuk mengurutkan Nomor Tiket Asc / Desc">
                                <span>Subject (Nomor Tiket)</span>
                                @if(request('sort') === 'tiket_asc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▲ (Asc)</span>
                                @elseif(request('sort') === 'tiket_desc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▼ (Desc)</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px; opacity: 0.6;">⇅</span>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-20">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'progress_desc' ? 'progress_asc' : 'progress_desc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Klik untuk mengurutkan Progress">
                                <span>Status</span>
                                @if(request('sort') === 'progress_desc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▼ (100%→0%)</span>
                                @elseif(request('sort') === 'progress_asc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▲ (0%→100%)</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px; opacity: 0.6;">⇅</span>
                                @endif
                            </a>
                        </th>
                        <th class="th-w-20">
                            <a href="{{ route('admin.tiket.index', array_merge(request()->query(), ['sort' => request('sort') === 'updated_desc' ? 'updated_asc' : 'updated_desc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Klik untuk mengurutkan Waktu Update">
                                <span>Last Updated</span>
                                @if(request('sort') === 'updated_desc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▼ Terbaru</span>
                                @elseif(request('sort') === 'updated_asc')
                                    <span style="color: var(--blue-primary); font-size: 11px; font-weight: 700;">▲ Terlama</span>
                                @else
                                    <span style="color: #94a3b8; font-size: 11px; opacity: 0.6;">⇅</span>
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

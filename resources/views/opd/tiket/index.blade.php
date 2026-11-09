@extends('layouts.opd')

@section('title', 'Daftar Permintaan Layanan — Portal Perangkat Daerah')

@section('content')
@php
    $currentSort = request('sort', $sortBy ?? 'terbaru');
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
    $currentStatus = request('status', '');
@endphp

<div class="ticket-stack-container" style="max-width: 100%; margin: 0 auto;">

    <!-- Breadcrumb Navigasi -->
    <nav class="app-breadcrumb" style="margin-bottom: 16px;">
        <a href="{{ route('opd.dashboard') }}"><i class="fi fi-rr-home"></i> Dashboard</a>
        <span class="separator">/</span>
        <span class="current">Layanan</span>
    </nav>

    <!-- Card Utama Layanan Bagi Pakai -->
    <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        
        <!-- Header: Judul & Tombol Buat Permintaan -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fi fi-rr-share" style="color: #2563eb; font-size: 24px; display: flex; align-items: center;"></i>
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">
                    Daftar Permintaan Layanan Bagi Pakai
                </h1>
            </div>

            <div>
                <a href="{{ route('opd.tiket.create') }}" 
                   style="background: #0f172a; color: #ffffff; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.15s ease;"
                   onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    <i class="fi fi-rr-plus" style="font-size: 12px;"></i>
                    <span>Buat Permintaan</span>
                </a>
            </div>
        </div>

        <!-- Statistik Permintaan (WordPress Subsubsub Style) -->
        <div class="wp-subsubsub" style="margin-bottom: 16px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('opd.tiket.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" 
               style="text-decoration: none; color: {{ empty($currentStatus) ? '#0f172a' : '#2563eb' }}; font-weight: {{ empty($currentStatus) ? '700' : '500' }};">
                Semua <span style="color: #64748b; font-weight: normal;">({{ number_format($totalAll ?? $tikets->total()) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('opd.tiket.index', array_merge(request()->except('status', 'page'), ['status' => 'PROSES'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'PROSES' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'PROSES' ? '700' : '500' }};">
                Proses <span style="color: #64748b; font-weight: normal;">({{ number_format($totalProses ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('opd.tiket.index', array_merge(request()->except('status', 'page'), ['status' => 'SELESAI'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'SELESAI' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'SELESAI' ? '700' : '500' }};">
                Selesai <span style="color: #64748b; font-weight: normal;">({{ number_format($totalSelesai ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('opd.tiket.index', array_merge(request()->except('status', 'page'), ['status' => 'REVISI'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'REVISI' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'REVISI' ? '700' : '500' }};">
                Perlu Perbaikan <span style="color: #64748b; font-weight: normal;">({{ number_format($totalRevisi ?? 0) }})</span>
            </a>
        </div>

        <!-- Filter & Search Bar Row -->
        <div style="margin-bottom: 20px;">
            <form action="{{ route('opd.tiket.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <!-- Pencarian Input -->
                <div style="position: relative; width: 300px; max-width: 100%;">
                    <i class="fi fi-rr-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Permintaan / SE / PIC..." 
                           style="width: 100%; height: 38px; padding-left: 36px; padding-right: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; outline: none; background: #ffffff; box-sizing: border-box;">
                </div>

                <!-- Dropdown Status -->
                <select name="status" onchange="this.form.submit()" 
                        style="height: 38px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; background: #f8fafc; cursor: pointer; outline: none;">
                    <option value="">Semua Status</option>
                    <option value="PROSES" {{ request('status') === 'PROSES' ? 'selected' : '' }}>Sedang Berjalan</option>
                    <option value="SELESAI" {{ request('status') === 'SELESAI' ? 'selected' : '' }}>Selesai (100%)</option>
                    <option value="REVISI" {{ request('status') === 'REVISI' ? 'selected' : '' }}>Perlu Perbaikan</option>
                </select>

                <!-- Dropdown Sort Flaticon -->
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
                    <a href="{{ route('opd.tiket.index') }}" 
                       style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 12px; font-size: 12px; color: #64748b; text-decoration: none; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff;" 
                       title="Reset Filter">
                        <i class="fi fi-rr-refresh"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Daftar Permintaan Layanan -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            ID PEKERJAAN & PEMOHON
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 220px;">
                            SURAT EDARAN & VARIABEL
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 200px;">
                            STATUS
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 160px;">
                            TERAKHIR DIPERBARUI
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tikets as $t)
                        <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                            <!-- 1. ID PEKERJAAN & PEMOHON -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <a href="{{ route('opd.tiket.show', $t->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #2563eb; font-size: 14px; text-decoration: none;" title="Buka Detail Progress">
                                        #{{ $t->nomor_tiket }}
                                    </a>
                                </div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 13px; margin-top: 3px;">
                                    {{ $t->nama_instansi }}
                                </div>
                                @if($t->nama_pemohon)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        PIC: {{ $t->nama_pemohon }} ({{ $t->kontak_pemohon ?: '-' }})
                                    </div>
                                @endif

                                <!-- Baris Aksi Hover -->
                                <div class="wp-row-actions">
                                    <a href="{{ route('opd.tiket.show', $t->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                        Pantau Progress
                                    </a>
                                    @if($t->status === 'PERLU_PERBAIKAN')
                                        <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                        <a href="{{ route('opd.tiket.edit', $t->id) }}" style="color: #dc2626; text-decoration: none; font-weight: 600;">
                                            Perbaiki Form
                                        </a>
                                    @endif
                                    @if($t->isOpdStepDone(4) || $t->isCompleted())
                                        <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                        <a href="{{ route('opd.tiket.formulir', $t->id) }}" target="_blank" style="color: #166534; text-decoration: none; font-weight: 600;">
                                            Unduh Formulir Bukti
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. SURAT EDARAN & VARIABEL -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-weight: 600; color: #1e293b; font-size: 13px;">
                                    {{ $t->no_surat_edaran }}
                                </div>
                                <div style="margin-top: 6px;">
                                    <span style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 9999px; display: inline-block;">
                                        {{ count($t->variabel_dtsen ?? []) }} Variabel Diminta
                                    </span>
                                </div>
                            </td>

                            <!-- 3. STATUS -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                @if($t->status === 'PERLU_PERBAIKAN')
                                    <div>
                                        <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fi fi-rr-exclamation"></i> Perlu Perbaikan Berkas
                                        </span>
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <a href="{{ route('opd.tiket.edit', $t->id) }}" style="font-size: 11px; padding: 3px 10px; color: #dc2626; border: 1px solid #fca5a5; background: #fff1f2; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; font-weight: 600;">
                                            <i class="fi fi-rr-edit" style="font-size: 10px;"></i> Perbaiki Form Sekarang
                                        </a>
                                    </div>
                                @elseif($t->isOpdStepDone(4) || $t->isCompleted())
                                    <div>
                                        <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fi fi-rr-check"></i> Selesai (100%)
                                        </span>
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <a href="{{ route('opd.tiket.formulir', $t->id) }}" target="_blank" style="font-size: 11px; padding: 3px 10px; color: #15803d; border: 1px solid #86efac; background: #f0fdf4; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; border-radius: 6px; font-weight: 600;" title="Unduh Formulir Bukti Layanan Selesai">
                                            <i class="fi fi-rr-document-signed" style="font-size: 10px;"></i> Unduh Formulir Bukti
                                        </a>
                                    </div>
                                @else
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="background: #e2e8f0; border-radius: 9999px; height: 6px; width: 60px; overflow: hidden;">
                                            <div style="background: #0284c7; width: {{ $t->progress_percent }}%; height: 100%; border-radius: 9999px;"></div>
                                        </div>
                                        <span style="font-size: 12px; font-weight: 600; color: #0284c7;">
                                            {{ $t->progress_percent }}%
                                        </span>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 4px; font-weight: 500;">
                                        {{ $t->current_opd_status_label }}
                                    </div>
                                @endif
                            </td>

                            <!-- 4. TERAKHIR DIPERBARUI -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-size: 12px; color: #334155; font-weight: 500;">
                                    {{ $t->updated_at ? $t->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ $t->updated_at ? $t->updated_at->diffForHumans() : '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-ticket"></i></div>
                                <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum Ada Tiket Permohonan</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 4px; margin-bottom: 14px;">
                                    Anda belum memiliki tiket permohonan aktif atau sesuai filter yang dipilih.
                                </div>
                                <a href="{{ route('opd.tiket.create') }}" 
                                   style="background: #0f172a; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fi fi-rr-plus" style="font-size: 11px;"></i> Buat Permintaan Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tikets->hasPages())
            <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                {{ $tikets->links() }}
            </div>
        @endif

    </div>

</div>

<style>
    /* Hover effect for row */
    .wp-table-row:hover {
        background-color: #f8fafc !important;
    }

    /* Hidden hover action links (WordPress Style) */
    .wp-row-actions {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.12s ease-in-out, visibility 0.12s ease-in-out;
        font-size: 12px;
        margin-top: 4px;
        line-height: 1.2;
    }

    .wp-table-row:hover .wp-row-actions {
        visibility: visible;
        opacity: 1;
    }

    .wp-row-actions a:hover,
    .wp-row-actions button:hover {
        text-decoration: underline !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sort-dropdown-toggle');
        const dropdown = document.getElementById('sort-flaticon-dropdown');
        if (toggleBtn && dropdown) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });
        }
    });
</script>
@endsection
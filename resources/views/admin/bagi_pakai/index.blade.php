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
    $currentStatus = request('status', '');
@endphp

<div class="ticket-stack-container" style="max-width: 100%; margin: 0 auto;">

    <!-- Card Utama Layanan Bagi Pakai DTSEN -->
    <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        
        <!-- Header: Judul & Tombol Buat Permintaan -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fi fi-rr-share" style="color: #2563eb; font-size: 24px; display: flex; align-items: center;"></i>
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">
                    Layanan Bagi Pakai DTSEN
                </h1>
            </div>

            <div>
                <a href="{{ route('admin.bagi-pakai-dtsen.create') }}" 
                   style="background: #0f172a; color: #ffffff; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.15s ease;"
                   onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    <span>Buat Permintaan</span>
                </a>
            </div>
        </div>

        <!-- Statistik Permintaan Sederhana (WordPress Subsubsub Style) -->
        <div class="wp-subsubsub" style="margin-bottom: 16px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" 
               style="text-decoration: none; color: {{ empty($currentStatus) ? '#0f172a' : '#2563eb' }}; font-weight: {{ empty($currentStatus) ? '700' : '500' }};">
                Semua <span style="color: #64748b; font-weight: normal;">({{ number_format($totalPermintaan ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->except('status', 'page'), ['status' => 'PROSES'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'PROSES' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'PROSES' ? '700' : '500' }};">
                Proses <span style="color: #64748b; font-weight: normal;">({{ number_format($totalProses ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->except('status', 'page'), ['status' => 'SELESAI'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'SELESAI' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'SELESAI' ? '700' : '500' }};">
                Selesai <span style="color: #64748b; font-weight: normal;">({{ number_format($totalSelesai ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->except('status', 'page'), ['status' => 'REVISI'])) }}" 
               style="text-decoration: none; color: {{ $currentStatus === 'REVISI' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentStatus === 'REVISI' ? '700' : '500' }};">
                Revisi <span style="color: #64748b; font-weight: normal;">({{ number_format($totalRevisi ?? 0) }})</span>
            </a>
        </div>

        <!-- Filter & Search Bar Row -->
        <div style="margin-bottom: 20px;">
            <form action="{{ route('admin.bagi-pakai-dtsen.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <!-- Pencarian Input -->
                <div style="position: relative; width: 300px; max-width: 100%;">
                    <i class="fi fi-rr-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Tiket / Instansi / SE..." 
                           style="width: 100%; height: 38px; padding-left: 36px; padding-right: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; outline: none; background: #ffffff; box-sizing: border-box;">
                </div>

                <!-- Dropdown Status -->
                <select name="status" onchange="this.form.submit()" 
                        style="height: 38px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; background: #f8fafc; cursor: pointer; outline: none;">
                    <option value="">Semua Status</option>
                    <option value="PROSES" {{ request('status') === 'PROSES' ? 'selected' : '' }}>Proses</option>
                    <option value="SELESAI" {{ request('status') === 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                    <option value="REVISI" {{ request('status') === 'REVISI' ? 'selected' : '' }}>Revisi</option>
                </select>

                <!-- Dropdown Sort Flaticon -->
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
                    <a href="{{ route('admin.bagi-pakai-dtsen.index') }}" 
                       style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 12px; font-size: 12px; color: #64748b; text-decoration: none; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff;" 
                       title="Reset Filter">
                        <i class="fi fi-rr-refresh"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Daftar Permintaan Layanan (Format WordPress Style) -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->query(), ['sort' => request('sort') === 'tiket_asc' ? 'tiket_desc' : 'tiket_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Urutkan ID Pekerjaan">
                                <span>ID PEKERJAAN</span>
                                @if(request('sort') === 'tiket_asc')
                                    <i class="fi fi-rr-sort-numeric-down" style="color: #2563eb; font-size: 11px;"></i>
                                @elseif(request('sort') === 'tiket_desc')
                                    <i class="fi fi-rr-sort-numeric-up" style="color: #2563eb; font-size: 11px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="font-size: 11px; opacity: 0.4;"></i>
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            <a href="{{ route('admin.bagi-pakai-dtsen.index', array_merge(request()->query(), ['sort' => request('sort') === 'alphabet_asc' ? 'alphabet_desc' : 'alphabet_asc'])) }}" style="color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" title="Urutkan OPD">
                                <span>OPD</span>
                                @if(request('sort') === 'alphabet_asc')
                                    <i class="fi fi-rr-sort-alpha-down" style="color: #2563eb; font-size: 11px;"></i>
                                @elseif(request('sort') === 'alphabet_desc')
                                    <i class="fi fi-rr-sort-alpha-up" style="color: #2563eb; font-size: 11px;"></i>
                                @else
                                    <i class="fi fi-rr-sort-alt" style="font-size: 11px; opacity: 0.4;"></i>
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 170px;">
                            VARIABEL DTSEN
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 170px;">
                            STATUS
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 150px;">
                            TANGGAL
                        </th>
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
                            else $activeStepNum = 5;
                        @endphp
                        <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                            <!-- 1. ID PEKERJAAN & Aksi Hidden Hover Text -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-family: var(--font-mono); font-weight: 700; color: #2563eb; font-size: 13px; text-decoration: none;">
                                        {{ $item->nomor_tiket }}
                                    </a>
                                </div>
                                
                                <!-- Baris Aksi: Muncul saat mouse hover baris ini -->
                                <div class="wp-row-actions">
                                    @if(!$item->isCompleted())
                                        <a href="{{ route('admin.bagi-pakai-dtsen.edit', $item->id) }}" class="wp-action-link" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                            Edit
                                        </a>
                                        <span class="wp-action-sep" style="color: #cbd5e1; margin: 0 4px;">|</span>
                                        <form action="{{ route('admin.bagi-pakai-dtsen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus permintaan layanan tiket [{{ $item->nomor_tiket }}] milik {{ $item->nama_instansi }}? Tindakan ini tidak dapat dibatalkan.');" style="display: inline; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="wp-action-delete" style="color: #dc2626; background: none; border: none; padding: 0; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none;">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.bagi-pakai-dtsen.formulir', $item->id) }}" target="_blank" style="color: #166534; text-decoration: none; font-weight: 600;">
                                            Formulir Bukti
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. OPD -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <a href="{{ route('admin.tiket.show', $item->id) }}" style="font-weight: 700; color: #2563eb; text-decoration: none; font-size: 13px;">
                                        {{ $item->nama_instansi }}
                                    </a>
                                </div>
                                @if($item->nama_pemohon)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                        PIC: {{ $item->nama_pemohon }} ({{ $item->kontak_pemohon ?: '-' }})
                                    </div>
                                @endif
                            </td>

                            <!-- 3. VARIABEL DTSEN -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <span style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 9999px; display: inline-block;">
                                    {{ count($item->variabel_dtsen ?? []) }} Variabel
                                </span>
                            </td>

                            <!-- 4. STATUS -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="background: #e2e8f0; border-radius: 9999px; height: 6px; width: 60px; overflow: hidden;">
                                        <div style="background: {{ $item->step5_done ? '#16a34a' : '#0284c7' }}; width: {{ $item->progress_percent }}%; height: 100%; border-radius: 9999px;"></div>
                                    </div>
                                    <span style="font-size: 12px; font-weight: 600; color: {{ $item->step5_done ? '#16a34a' : '#0284c7' }};">
                                        {{ $item->progress_percent }}%
                                    </span>
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 4px; font-weight: 500;">
                                    @if($item->status === 'PERLU_PERBAIKAN')
                                        <span style="color: #d97706; font-weight: 600;">Perlu Perbaikan</span>
                                    @elseif($item->step5_done)
                                        <span style="color: #16a34a; font-weight: 600;">Selesai 100%</span>
                                    @else
                                        Progress {{ $activeStepNum }}/5
                                    @endif
                                </div>
                            </td>

                            <!-- 5. TANGGAL -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-size: 12px; color: #334155; font-weight: 500;">
                                    {{ $item->created_at->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ $item->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-share"></i></div>
                                <div style="font-weight: 700; font-size: 14px; color: #334155;">Belum Ada Permintaan Layanan Bagi Pakai DTSEN</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                                    Klik tombol <strong>"Buat Permintaan"</strong> di atas untuk membuat permohonan baru.
                                </div>
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

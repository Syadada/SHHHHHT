@extends('layouts.admin')

@section('content')
@php
    $currentRole = request('role', '');
    $search = request('search', '');
@endphp

<div class="ticket-stack-container" style="max-width: 100%; margin: 0 auto;">

    <!-- Banner Notifikasi Permintaan Reset Password (Walidata) -->
    @if(isset($pendingResetRequests) && $pendingResetRequests->count() > 0 && auth()->user()->isSuperAdmin())
        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fi fi-rr-bell"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 700; color: #92400e;">
                            {{ $pendingResetRequests->count() }} Permintaan Reset Password Masuk
                        </div>
                        <div style="font-size: 12px; color: #b45309; margin-top: 2px;">
                            Perangkat daerah memerlukan verifikasi kata sandi baru dari Walidata.
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @foreach($pendingResetRequests as $req)
                        <form action="{{ route('admin.reset-requests.approve', $req->id) }}" method="POST" onsubmit="return confirm('Reset kata sandi akun {{ $req->username }} menjadi: opd123 ?');" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: #b45309; color: #ffffff; border: none; border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fi fi-rr-refresh"></i> Reset <strong>{{ $req->username }}</strong> ({{ $req->nama_instansi }})
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Card Utama User Management -->
    <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
        
        <!-- Header: Judul & Tombol Buat Akun -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fi fi-rr-users-alt" style="color: #2563eb; font-size: 24px; display: flex; align-items: center;"></i>
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">
                    User Management
                </h1>
            </div>

            <div>
                <a href="{{ route('admin.users.create') }}" 
                   style="background: #0f172a; color: #ffffff; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.15s ease;"
                   onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
                    <i class="fi fi-rr-user-add" style="font-size: 13px;"></i>
                    <span>Buat Akun</span>
                </a>
            </div>
        </div>

        <!-- Statistik Sederhana (WordPress Subsubsub Style) -->
        <div class="wp-subsubsub" style="margin-bottom: 16px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'users_page'), ['role' => ''])) }}" 
               style="text-decoration: none; color: {{ empty($currentRole) ? '#0f172a' : '#2563eb' }}; font-weight: {{ empty($currentRole) ? '700' : '500' }};">
                Semua <span style="color: #64748b; font-weight: normal;">({{ number_format($totalUsers ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'users_page'), ['role' => 'perangkat_daerah'])) }}" 
               style="text-decoration: none; color: {{ $currentRole === 'perangkat_daerah' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentRole === 'perangkat_daerah' ? '700' : '500' }};">
                Perangkat Daerah <span style="color: #64748b; font-weight: normal;">({{ number_format($totalOpd ?? 0) }})</span>
            </a>
            <span style="color: #cbd5e1;">|</span>
            <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'users_page'), ['role' => 'superadmin'])) }}" 
               style="text-decoration: none; color: {{ $currentRole === 'superadmin' ? '#0f172a' : '#2563eb' }}; font-weight: {{ $currentRole === 'superadmin' ? '700' : '500' }};">
                Walidata <span style="color: #64748b; font-weight: normal;">({{ number_format($totalAdmin ?? 0) }})</span>
            </a>
        </div>

        <!-- Filter & Search Bar Row -->
        <div style="margin-bottom: 20px;">
            <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <!-- Pencarian Input -->
                <div style="position: relative; width: 300px; max-width: 100%;">
                    <i class="fi fi-rr-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; pointer-events: none;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Username / OPD..." 
                           style="width: 100%; height: 38px; padding-left: 36px; padding-right: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; outline: none; background: #ffffff; box-sizing: border-box;">
                </div>

                <!-- Dropdown Role -->
                <select name="role" onchange="this.form.submit()" 
                        style="height: 38px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #1e293b; background: #f8fafc; cursor: pointer; outline: none;">
                    <option value="">Semua Role</option>
                    <option value="perangkat_daerah" {{ request('role') === 'perangkat_daerah' ? 'selected' : '' }}>Perangkat Daerah</option>
                    <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Walidata</option>
                </select>

                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" 
                       style="height: 38px; display: inline-flex; align-items: center; gap: 4px; padding: 0 12px; font-size: 12px; color: #64748b; text-decoration: none; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff;" 
                       title="Reset Filter">
                        <i class="fi fi-rr-refresh"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Tabel Daftar Akun Pengguna (WordPress Style) -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            INSTANSI / PERANGKAT DAERAH
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            USERNAME & EMAIL
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 170px;">
                            ROLE / HAK AKSES
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 180px;">
                            KATA SANDI
                        </th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 170px;">
                            TERAKHIR LOGIN
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                            <!-- 1. Nama Instansi & Aksi Hover -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <a href="{{ route('admin.users.show', $u->id) }}" style="font-weight: 700; color: #2563eb; font-size: 13px; text-decoration: none;">
                                        {{ $u->nama_instansi ?: 'Pemerintah Daerah' }}
                                    </a>
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ $u->name }}
                                </div>

                                <!-- Aksi Hidden Hover Text (WordPress Style) -->
                                <div class="wp-row-actions">
                                    <a href="{{ route('admin.users.show', $u->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                        Detail
                                    </a>
                                    <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                    <a href="{{ route('admin.users.edit-access', $u->id) }}" style="color: #b45309; text-decoration: none; font-weight: 500;">
                                        Edit Akses
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <span style="color: #cbd5e1; margin: 0 4px;">|</span>
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->username }} ({{ $u->nama_instansi }})? Tindakan ini tidak dapat dibatalkan.');" style="display: inline; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: #dc2626; background: none; border: none; padding: 0; font-size: 12px; font-weight: 500; cursor: pointer; text-decoration: none;">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. Username & Email -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <span style="font-family: var(--font-mono); font-weight: 700; color: #0f172a; font-size: 13px;">
                                        {{ $u->username }}
                                    </span>
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ $u->email }}
                                </div>
                            </td>

                            <!-- 3. Role / Hak Akses -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                @if($u->role === 'superadmin')
                                    <span style="background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-shield-check" style="font-size: 10px;"></i>
                                        {{ $u->roleRelation ? $u->roleRelation->name : 'Walidata' }}
                                    </span>
                                @else
                                    <span style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fi fi-rr-building" style="font-size: 10px;"></i>
                                        {{ $u->roleRelation ? $u->roleRelation->name : 'Perangkat Daerah' }}
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Kata Sandi -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                @if(auth()->user()->isSuperAdmin())
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span id="pwd-val-{{ $u->id }}" style="font-family: var(--font-mono); font-size: 12px; font-weight: 700; color: #0f172a; min-width: 65px;">••••••••</span>
                                        <button type="button" 
                                                style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 3px 7px; font-size: 11px; color: #475569; cursor: pointer;"
                                                onclick="togglePasswordVisibility('pwd-val-{{ $u->id }}', '{{ addslashes($u->plain_password) }}', this)" 
                                                title="Lihat / Sembunyikan Kata Sandi">
                                            <i class="fi fi-rr-eye"></i>
                                        </button>
                                        <button type="button" 
                                                style="background: #fff5f5; border: 1px solid #fca5a5; border-radius: 6px; padding: 3px 7px; font-size: 11px; color: #b91c1c; cursor: pointer;"
                                                onclick="promptResetPassword('{{ $u->id }}', '{{ $u->username }}')" 
                                                title="Reset Kata Sandi Akun Ini">
                                            <i class="fi fi-rr-key"></i>
                                        </button>
                                    </div>
                                @else
                                    <span style="font-size: 11px; color: #94a3b8;"><i class="fi fi-rr-lock"></i> Terkunci</span>
                                @endif
                            </td>

                            <!-- 5. Terakhir Login -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                @if($u->last_login_at)
                                    <div style="font-size: 12px; color: #334155; font-weight: 500;">
                                        {{ \Carbon\Carbon::parse($u->last_login_at)->format('d/m/Y') }}
                                    </div>
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        {{ \Carbon\Carbon::parse($u->last_login_at)->format('H:i') }} WIB
                                    </div>
                                @else
                                    <span style="font-size: 11px; color: #94a3b8;">Belum Pernah Login</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                <div style="font-size: 32px; margin-bottom: 8px;"><i class="fi fi-rr-users-slash"></i></div>
                                <div style="font-weight: 700; font-size: 14px; color: #334155;">Tidak Ada Akun Pengguna Ditemukan</div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 3px;">
                                    Coba ubah kata kunci pencarian atau filter role di atas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    @if(isset($riwayatBagiDatas) && $riwayatBagiDatas->count() > 0)
    <!-- DAFTAR RIWAYAT BAGI DATA / BAST RESMI TERBIT -->
    <div class="card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-top: 24px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <i class="fi fi-rr-document-signed" style="color: #2563eb; font-size: 20px;"></i>
            <h2 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">
                Riwayat Bagi Data DTSEN Resmi Terbit (BAST)
            </h2>
        </div>

        <div class="table-responsive" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #ffffff;">
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 60px;">NO</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">PERANGKAT DAERAH</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">SURAT EDARAN & BAST</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 160px;">TANGGAL TERBIT</th>
                        <th style="padding: 12px 16px; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 140px; text-align: center;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatBagiDatas as $idx => $r)
                        <tr class="wp-table-row" style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                            <td style="padding: 14px 16px; font-size: 12px; color: #64748b; font-weight: 600;">
                                {{ $riwayatBagiDatas->firstItem() + $idx }}
                            </td>
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div>
                                    <a href="{{ route('admin.riwayat-bagi-data.show', $r->id) }}" style="font-weight: 700; color: #2563eb; text-decoration: none; font-size: 13px;">
                                        {{ $r->nama_instansi }}
                                    </a>
                                </div>
                                @if($r->tiket_dtsen_id)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                        Tiket Terkait: <a href="{{ route('admin.tiket.show', $r->tiket_dtsen_id) }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">#{{ $r->tiket_dtsen_id }}</a>
                                    </div>
                                @endif
                                <div class="wp-row-actions">
                                    <a href="{{ route('admin.riwayat-bagi-data.show', $r->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 500;">
                                        Lihat Berita Acara
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-size: 12px; font-weight: 600; color: #1e293b;">
                                    SE: {{ $r->no_surat_edaran ?: '-' }}
                                </div>
                                @if($r->no_bast)
                                    <div style="font-size: 11px; color: #64748b; margin-top: 2px; font-family: var(--font-mono);">
                                        BAST: <strong>{{ $r->no_bast }}</strong>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-size: 12px; color: #334155; font-weight: 500;">{{ \Carbon\Carbon::parse($r->tanggal_terbit_bast)->format('d/m/Y') }}</div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ \Carbon\Carbon::parse($r->tanggal_terbit_bast)->format('H:i') }} WIB</div>
                            </td>
                            <td style="padding: 14px 16px; vertical-align: top; text-align: center;">
                                <span style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fi fi-rr-check" style="font-size: 10px;"></i>
                                    <span>BAST Terbit</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                Belum ada data penerbitan BAST resmi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatBagiDatas->hasPages())
            <div style="padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f1f5f9;">
                {{ $riwayatBagiDatas->links() }}
            </div>
        @endif
    </div>
    @endif

</div>

<!-- Form Tersembunyi Reset Password Superadmin -->
<form id="formSuperadminReset" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="password" id="inputResetNewPassword">
</form>

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
function togglePasswordVisibility(elId, plainPwd, btn) {
    const el = document.getElementById(elId);
    if (!el) return;
    if (el.innerText === '••••••••') {
        el.innerText = plainPwd;
        btn.innerHTML = '<i class="fi fi-rr-eye-crossed"></i>';
    } else {
        el.innerText = '••••••••';
        btn.innerHTML = '<i class="fi fi-rr-eye"></i>';
    }
}

function promptResetPassword(userId, username) {
    const newPwd = prompt(`Masukkan kata sandi baru untuk akun ${username}:`, 'opd123');
    if (newPwd && newPwd.trim() !== '') {
        const form = document.getElementById('formSuperadminReset');
        form.action = `{{ url('admin/users') }}/${userId}/reset-password`;
        document.getElementById('inputResetNewPassword').value = newPwd.trim();
        form.submit();
    }
}
</script>
@endsection

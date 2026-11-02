<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\Dataset;
use App\Models\User;
use App\Models\TiketDtsen;
use App\Models\PengolahanDtsen;
use App\Models\HakAksesDtsen;
use App\Models\PengamananDtsen;
use App\Models\RopaDtsen;
use App\Models\DpiaDtsen;

class GovernanceDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Inisialisasi Master Roles
        $roleSuperAdmin = Role::updateOrCreate(
            ['slug' => 'superadmin'],
            [
                'name' => 'Super Administrator (Walidata)',
                'deskripsi' => 'Pengelola dan pengawas utama permohonan bagi pakai data DTSEN tingkat daerah.',
            ]
        );

        $roleOpd = Role::updateOrCreate(
            ['slug' => 'perangkat_daerah'],
            [
                'name' => 'Perangkat Daerah (OPD)',
                'deskripsi' => 'Instansi pemohon bagi pakai data DTSEN untuk perencanaan dan evaluasi program.',
            ]
        );

        $roleAdminUserManager = Role::updateOrCreate(
            ['slug' => 'admin_user_manager'],
            [
                'name' => 'Admin 1 (Kelola Akun)',
                'deskripsi' => 'Administrator berwenang membuat dan mengelola akun pengguna, tetapi tidak dapat memperbarui proses tiket.',
            ]
        );

        $roleAdminOperator = Role::updateOrCreate(
            ['slug' => 'admin_operator'],
            [
                'name' => 'Admin 2 (Operator Proses Tiket)',
                'deskripsi' => 'Administrator berwenang memverifikasi variabel SE dan memperbarui tahapan tiket (1-5), tetapi tidak dapat mengelola akun pengguna.',
            ]
        );

        $roleEselon2 = Role::updateOrCreate(
            ['slug' => 'eselon2'],
            [
                'name' => 'Pejabat Eselon 2 (Kepala Dinas / Badan)',
                'deskripsi' => 'Pimpinan instansi untuk monitoring, persetujuan, dan pembaruan alur proses tiket bagi pakai data DTSEN.',
            ]
        );

        $roleVerifikator = Role::updateOrCreate(
            ['slug' => 'verifikator'],
            [
                'name' => 'Verifikator Tim Teknis',
                'deskripsi' => 'Tim teknis pemeriksa kesesuaian variabel data DTSEN dan verifikasi dokumen legalitas SE.',
            ]
        );

        // 2. Inisialisasi Master Privileges / Hak Akses Fitur
        $privileges = [
            ['slug' => 'create-tiket', 'name' => 'Membuat Tiket Baru', 'module' => 'TIKET', 'deskripsi' => 'Mengajukan permohonan layanan bagi pakai data DTSEN baru'],
            ['slug' => 'verify-se', 'name' => 'Verifikasi Dokumen SE', 'module' => 'TIKET', 'deskripsi' => 'Memeriksa keabsahan surat edaran permohonan'],
            ['slug' => 'manipulate-steps', 'name' => 'Manipulasi Tahapan Proses', 'module' => 'TIKET', 'deskripsi' => 'Menyetujui dan menyelesaikan progress tahapan tiket (1 - 5)'],
            ['slug' => 'manage-governance', 'name' => 'Kelola Tata Kelola DTSEN', 'module' => 'GOVERNANCE', 'deskripsi' => 'Akses modul ROPA, DPIA, dan Pengolahan Data'],
            ['slug' => 'security-verification', 'name' => 'Verifikasi Integritas & Enkripsi', 'module' => 'SECURITY', 'deskripsi' => 'Pemeriksaan checksum SHA-256 dan standar enkripsi perangkat'],
            ['slug' => 'export-data', 'name' => 'Ekspor CSV Laporan & Audit', 'module' => 'SISTEM', 'deskripsi' => 'Mengunduh laporan rekapitulasi tiket dan jejak log audit'],
            ['slug' => 'manage-users', 'name' => 'Manajemen Akun Pengguna', 'module' => 'USERS', 'deskripsi' => 'Mengelola akun perangkat daerah dan log perangkat login'],
            ['slug' => 'manage-access', 'name' => 'Kelola Hak Akses Administrator', 'module' => 'SISTEM', 'deskripsi' => 'Mengelola hak akses dan penugasan peran admin via modul Filament Access Control'],
            ['slug' => 'bagi_pakai_dtsen', 'name' => 'Layanan Bagi Pakai DTSEN', 'module' => 'TIKET', 'deskripsi' => 'Daftar, buat, edit, dan hapus permintaan layanan dari OPD pengguna layanan (SOP: 555/07-SOP/DISKOMINFO SP/2026)'],
        ];

        $privilegeModels = [];
        foreach ($privileges as $p) {
            $privilegeModels[$p['slug']] = Privilege::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }

        // Tautkan Hak Akses ke Roles
        $roleSuperAdmin->privileges()->sync(collect($privilegeModels)->pluck('id'));
        $roleOpd->privileges()->sync([
            $privilegeModels['create-tiket']->id,
            $privilegeModels['bagi_pakai_dtsen']->id,
        ]);
        $roleAdminUserManager->privileges()->sync([
            $privilegeModels['manage-users']->id,
            $privilegeModels['export-data']->id,
        ]);
        $roleAdminOperator->privileges()->sync([
            $privilegeModels['manipulate-steps']->id,
            $privilegeModels['verify-se']->id,
            $privilegeModels['security-verification']->id,
            $privilegeModels['bagi_pakai_dtsen']->id,
        ]);
        $roleEselon2->privileges()->sync([
            $privilegeModels['manipulate-steps']->id,
            $privilegeModels['verify-se']->id,
            $privilegeModels['export-data']->id,
            $privilegeModels['bagi_pakai_dtsen']->id,
        ]);
        $roleOpd->privileges()->sync([
            $privilegeModels['create-tiket']->id,
        ]);
        $roleVerifikator->privileges()->sync([
            $privilegeModels['verify-se']->id,
            $privilegeModels['manage-governance']->id,
            $privilegeModels['security-verification']->id,
        ]);

        // 3. Tautkan Akun Pengguna yang Ada ke role_id
        User::where('username', 'admin')->update([
            'role' => 'superadmin',
            'role_id' => $roleSuperAdmin->id,
        ]);
        User::where('role', 'perangkat_daerah')->update(['role_id' => $roleOpd->id]);

        // 3b. Buat Akun Contoh: Admin 1 (Kelola Akun) & Admin 2 (Operator Proses)
        User::updateOrCreate(
            ['username' => 'admin1'],
            [
                'name' => 'Admin 1 (Kelola Akun)',
                'email' => 'admin1@pemda.go.id',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'role_id' => $roleAdminUserManager->id,
                'nama_instansi' => 'Diskominfo Bidang Tata Kelola Akun',
                'nomor_kontak' => '081234567891',
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
                'last_login_device' => 'WIN-ADM-USER01',
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin2'],
            [
                'name' => 'Admin 2 (Operator Proses Tiket)',
                'email' => 'admin2@pemda.go.id',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'role_id' => $roleAdminOperator->id,
                'nama_instansi' => 'Diskominfo Tim Verifikator DTSEN',
                'nomor_kontak' => '081234567892',
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
                'last_login_device' => 'WIN-ADM-OPERATOR02',
            ]
        );

        // 4. Inisialisasi Master Dataset & SOP Manual Book
        Dataset::updateOrCreate(
            ['kode_dataset' => 'DTSEN-2026-RESMI'],
            [
                'nama_dataset' => 'Data Terpadu Sosial Ekonomi Nasional (DTSEN) 2026',
                'kategori_desil' => 'Desil 1 s/d Desil 10',
                'deskripsi' => 'Katalog resmi variabel mikro sosial ekonomi nasional untuk integrasi program perlindungan sosial daerah terpadu.',
                'daftar_variabel_tersedia' => [
                    'Nomor Induk Kependudukan (NIK) & Identitas Kependudukan',
                    'Nama Lengkap Kepala Keluarga & Anggota Rumah Tangga',
                    'Alamat Domisili & Kode Wilayah Administrasi (Prov/Kab/Kec/Kel)',
                    'Desil Kesejahteraan Sosial Ekonomi DTSEN (Desil 1 - 10)',
                    'Status Kepesertaan Bantuan Sosial (PKH, BPNT/Sembako, PBI JK)',
                    'Kondisi Fisik Bangunan Tempat Tinggal & Kelayakan Sanitasi',
                    'Sumber Penerangan & Daya Listrik Terpasang',
                    'Sumber Air Minum Utama & Fasilitas Jamban Keluarga',
                    'Status Kepemilikan Aset Bergerak & Kepemilikan Lahan',
                    'Tingkat Pendidikan Tertinggi & Partisipasi Sekolah Anggota Rumah Tangga',
                ],
                'file_sop_manual' => 'sop/SOP_Layanan_Bagi_Pakai_DTSEN_2026.pdf',
                'nama_file_sop' => 'SOP Layanan Bagi Pakai DTSEN & Manual Book Pengguna (Rev. 2026).pdf',
                'status' => 'AKTIF',
            ]
        );

        // 5. Inisialisasi Rekam Data Tata Kelola untuk Tiket yang Sudah Ada
        $tiket = TiketDtsen::first();
        if ($tiket) {
            $adminUser = User::where('role', 'superadmin')->first();
            $opdUser = User::where('role', 'perangkat_daerah')->first() ?: $adminUser;

            // A. Pengolahan DTSEN
            PengolahanDtsen::updateOrCreate(
                ['tiket_dtsen_id' => $tiket->id],
                [
                    'petugas_pengolah_id' => $adminUser ? $adminUser->id : null,
                    'tanggal_mulai' => now()->subDays(2),
                    'tanggal_selesai' => now()->subDay(),
                    'jumlah_baris_data' => 45820,
                    'status_pengolahan' => 'SELESAI',
                    'catatan_teknis' => 'Cleansing dan ekstraksi 5 variabel data mikro berhasil disaring berdasarkan filter wilayah kabupaten/kota.',
                ]
            );

            // B. Hak Akses DTSEN
            HakAksesDtsen::updateOrCreate(
                ['tiket_dtsen_id' => $tiket->id, 'user_id' => $opdUser->id],
                [
                    'lingkup_data' => 'Data DTSEN Sektor Perlindungan Sosial (Desil 1 - Desil 4)',
                    'status_akses' => 'AKTIF',
                    'masa_berlaku_mulai' => now()->subDays(3),
                    'masa_berlaku_selesai' => now()->addYear(),
                    'keterangan' => 'Hak akses diberikan resmi melalui Berita Acara Serah Terima (BAST).',
                ]
            );

            // C. Pengamanan DTSEN
            PengamananDtsen::updateOrCreate(
                ['tiket_dtsen_id' => $tiket->id],
                [
                    'metode_enkripsi' => 'AES-256-GCM / BitLocker Hardware Volume',
                    'checksum_sha256' => $tiket->hash_sha256 ?: hash('sha256', 'dtsen_secure_dataset_' . $tiket->nomor_tiket),
                    'status_integritas' => 'TERVERIFIKASI',
                    'tanggal_verifikasi' => now()->subDay(),
                    'petugas_keamanan' => 'Tim Keamanan Siber & Walidata DKISP',
                    'catatan' => 'Integritas file data mikro valid 100%, hash SHA-256 cocok dengan catatan serah terima.',
                ]
            );

            // D. ROPA DTSEN (Record of Processing Activities - UU PDP)
            RopaDtsen::updateOrCreate(
                ['tiket_dtsen_id' => $tiket->id],
                [
                    'tujuan_pemrosesan' => 'Validasi dan sinkronisasi sasaran program perlindungan sosial daerah terpadu untuk pengentasan kemiskinan ekstrem.',
                    'kategori_data_pribadi' => 'Data Pribadi Spesifik & Umum (Nama, NIK, Status Kesejahteraan Sosial, Alamat Rumah Tangga)',
                    'pihak_penerima' => $tiket->nama_instansi,
                    'dasar_hukum_se' => $tiket->no_surat_edaran . ' jo. UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi',
                    'jangka_waktu_retensi' => '1 (Satu) Tahun kalender sejak tanggal penandatanganan BAST',
                    'metode_penyimpanan' => 'Media penyimpanan offline terenkripsi (Harddisk Eksternal/Laptop Dinas Terverifikasi)',
                ]
            );

            // E. DPIA DTSEN (Data Protection Impact Assessment)
            DpiaDtsen::updateOrCreate(
                ['tiket_dtsen_id' => $tiket->id],
                [
                    'skor_risiko' => 'SEDANG',
                    'identifikasi_dampak' => 'Potensi kebocoran identitas kependudukan jika media penyimpanan perangkat hilang atau dipindahkan tanpa izin resmi.',
                    'langkah_mitigasi' => 'Penerapan pembatasan transfer data hanya ke nomor laptop terdaftar (hardware ID), enkripsi BitLocker, serta penandatanganan pakta integritas kerahasiaan data.',
                    'status_rekomendasi' => 'DISETUJUI',
                    'pejabat_penilai_id' => $adminUser ? $adminUser->id : null,
                    'tanggal_penilaian' => now()->subDays(2),
                ]
            );
        }
    }
}

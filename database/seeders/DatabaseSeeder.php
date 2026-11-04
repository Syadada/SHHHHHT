<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kak;
use App\Models\AuditLog;
use App\Models\TiketDtsen;
use App\Models\RiwayatBagiDataDtsen;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Walidata (Hak Kendali Penuh)
        $superAdmin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Walidata',
                'nama_instansi' => 'Dinas Komunikasi & Informatika (Walidata)',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'), // Password default: admin
                'role' => 'superadmin',
                'nomor_kontak' => '081122334455',
                'last_login_at' => now(),
                'last_login_ip' => '127.0.0.1',
                'last_login_device' => 'WIN-SUPERADMIN01',
            ]
        );

        // 2. Akun Perangkat Daerah (OPD) 1: Dinas Sosial
        $opdDinsos = User::updateOrCreate(
            ['username' => 'dinsos'],
            [
                'name' => 'Dinas Sosial Provinsi',
                'nama_instansi' => 'Dinas Sosial Provinsi',
                'email' => 'dinsos@pemda.go.id',
                'password' => Hash::make('dinsos123'), // Password: dinsos123
                'role' => 'perangkat_daerah',
                'nomor_kontak' => null,
                'last_login_at' => now()->subHours(3),
                'last_login_ip' => '192.168.10.45',
                'last_login_device' => 'WIN-LP-DINSOS01',
            ]
        );

        // Akun Perangkat Daerah (OPD) 2: Bappeda
        $opdBappeda = User::updateOrCreate(
            ['username' => 'bappeda'],
            [
                'name' => 'Badan Perencanaan Pembangunan Daerah',
                'nama_instansi' => 'Badan Perencanaan Pembangunan Daerah',
                'email' => 'bappeda@pemda.go.id',
                'password' => Hash::make('bappeda123'), // Password: bappeda123
                'role' => 'perangkat_daerah',
                'nomor_kontak' => null,
                'last_login_at' => now()->subDay(),
                'last_login_ip' => '192.168.20.12',
                'last_login_device' => 'WIN-LP-BAPPEDA02',
            ]
        );

        // 3. Data Awal Layanan Tiket Permintaan Bagi Pakai DTSEN
        $tiket = TiketDtsen::first();
        if (!$tiket) {
            $tiket = TiketDtsen::create([
                'nomor_tiket' => 'TIK-DTSEN-' . date('Ymd') . '-0001',
                'nama_instansi' => 'Dinas Sosial Provinsi',
                'no_surat_edaran' => 'SE/400.9/081/DINSOS/2026',
                'nama_pemohon' => 'Budi Santoso, S.Sos',
                'kontak_pemohon' => '081234567890',
                'email_pemohon' => 'dinsos@pemda.go.id',
                'tujuan_pemanfaatan' => 'Verifikasi dan validasi calon penerima program perlindungan sosial daerah terpadu.',
                'variabel_dtsen' => [
                    'Variabel 1',
                    'Variabel 2',
                    'Variabel 3',
                    'Variabel 4',
                    'Variabel 5',
                ],
                'file_surat' => null,
                'nama_file_asli' => 'Surat_Permohonan_Bagi_Pakai_DTSEN.pdf',
                'ukuran_file' => '1.8 MB',
                'ekstensi_file' => 'pdf',
                'hash_sha256' => hash('sha256', 'contoh_surat_edaran_dtsen'),
                'current_step' => 2,
                'step1_done' => true,
                'step1_at' => now()->subHours(2),
                'step2_done' => false,
                'step3_done' => false,
                'step4_done' => false,
                'step5_done' => false,
                'status' => 'DIPROSES',
                'catatan_admin' => 'Progress 1 telah disetujui & diverifikasi oleh Admin.',
            ]);
        }

        // 4. Data Tabel Riwayat Bagi Pakai Data DTSEN (Diinput Admin untuk Tampil di Profile OPD)
        if (RiwayatBagiDataDtsen::count() === 0) {
            RiwayatBagiDataDtsen::create([
                'tiket_dtsen_id' => $tiket ? $tiket->id : null,
                'user_id' => $opdDinsos->id,
                'nama_instansi' => 'Dinas Sosial Provinsi',
                'no_surat_edaran' => 'SE/400.9/081/DINSOS/2026',
                'tanggal_tiket_dibuat' => now()->subDays(3),
                'tanggal_bast_selesai' => now()->subDay(),
                'no_bast' => 'BAST/DTSEN/2026/X/014',
                'status_bast' => 'SELESAI DITANDATANGANI (BAST SAH)',
                'orang_hadir' => "1. Budi Santoso, S.Sos (Kabid Perlindungan Sosial)\n2. Siti Rahmah, S.Kom (Pranata Komputer Ahli Muda)\n3. Hendra Gunawan (Operator SiKS-NG)",
                'ip_atau_nomor_laptop' => 'IP: 192.168.10.45 (Laptop ID: WIN-LP-DINSOS01)',
                'catatan' => 'Bagi pakai data mikro DTSEN telah diserahkan terenkripsi sesuai SOP Keamanan Informasi.',
                'created_by' => 'Super Administrator',
            ]);
        }

        // 5. Log Riwayat Aktivitas & Login
        if (AuditLog::count() === 0) {
            AuditLog::create([
                'user_id' => $superAdmin->id,
                'user_name' => $superAdmin->name,
                'user_role' => $superAdmin->role,
                'action' => 'AUTH_LOGIN',
                'description' => 'Super Administrator berhasil masuk ke sistem melalui laptop WIN-SUPERADMIN01.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/124.0.0.0',
                'status' => 'SUCCESS',
                'metadata' => ['device_id' => 'WIN-SUPERADMIN01'],
                'created_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => $opdDinsos->id,
                'user_name' => $opdDinsos->name,
                'user_role' => $opdDinsos->role,
                'action' => 'AUTH_LOGIN',
                'description' => 'Akun Perangkat Daerah Dinas Sosial masuk ke sistem melalui laptop WIN-LP-DINSOS01.',
                'ip_address' => '192.168.10.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/124.0.0.0',
                'status' => 'SUCCESS',
                'metadata' => ['device_id' => 'WIN-LP-DINSOS01'],
                'created_at' => now()->subHours(3),
            ]);
        }

        // 6. Jalankan Seeder Tata Kelola (Roles, Privileges, Dataset, ROPA, DPIA)
        $this->call(GovernanceDatabaseSeeder::class);
    }
}

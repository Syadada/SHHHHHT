<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'password_display',
        'role',
        'role_id',
        'nama_instansi',
        'nomor_kontak',
        'last_login_at',
        'last_login_ip',
        'last_login_device',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Mengambil plain password yang tersimpan untuk dilihat Superadmin
     */
    public function getPlainPasswordAttribute(): ?string
    {
        if (empty($this->password_display)) {
            // Default fallback mapping untuk user demo
            $defaults = [
                'admin' => 'admin',
                'dinsos' => 'dinsos123',
                'bappeda' => 'bappeda123',
                'admin1' => 'admin123',
                'admin2' => 'admin123',
            ];
            return $defaults[$this->username] ?? '••••••••';
        }

        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->password_display);
        } catch (\Exception $e) {
            return $this->password_display;
        }
    }

    /**
     * Set password sekaligus menyimpan versi terenkripsi untuk superadmin
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($plainPassword);
        $this->password_display = \Illuminate\Support\Facades\Crypt::encryptString($plainPassword);
    }

    public function isSuperAdminOnly(): bool
    {
        return $this->role === 'superadmin' || $this->username === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    public function isPerangkatDaerah(): bool
    {
        return $this->role === 'perangkat_daerah';
    }

    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Memeriksa apakah pengguna memiliki privilege / hak akses tertentu
     */
    public function hasPrivilege(string $slug): bool
    {
        // Akun utama superadmin selalu memiliki seluruh hak akses
        if ($this->role === 'superadmin' || $this->username === 'admin') {
            return true;
        }

        if (!$this->relationLoaded('roleRelation')) {
            $this->load('roleRelation.privileges');
        }

        if ($this->roleRelation && $this->roleRelation->privileges) {
            return $this->roleRelation->privileges->contains('slug', $slug);
        }

        return false;
    }

    /**
     * Hak akses membuat & mengelola akun pengguna (Admin 1)
     */
    public function canManageUsers(): bool
    {
        return $this->hasPrivilege('manage-users');
    }

    /**
     * Hak akses memanipulasi & memperbarui alur tahapan proses tiket (Admin 2)
     */
    public function canManipulateSteps(): bool
    {
        return $this->hasPrivilege('manipulate-steps');
    }

    /**
     * Hak akses verifikasi dokumen surat edaran & variabel
     */
    public function canVerifySe(): bool
    {
        return $this->hasPrivilege('verify-se');
    }

    /**
     * Hak akses modul konfigurasi hak akses admin (Filament Access Control)
     */
    public function canManageAccess(): bool
    {
        return $this->role === 'superadmin' || $this->username === 'admin' || $this->hasPrivilege('manage-access');
    }

    /**
     * Hak akses modul Layanan Bagi Pakai DTSEN (SOP: 555/07-SOP/DISKOMINFO SP/2026)
     */
    public function canManageBagiPakaiDtsen(): bool
    {
        return $this->role === 'superadmin' || $this->username === 'admin' || $this->hasPrivilege('bagi_pakai_dtsen');
    }

    public function riwayatBagiDatas()
    {
        return $this->hasMany(RiwayatBagiDataDtsen::class, 'user_id');
    }

    public function loginLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id')->where('action', 'AUTH_LOGIN');
    }

    public function hakAkses()
    {
        return $this->hasMany(HakAksesDtsen::class, 'user_id');
    }
}

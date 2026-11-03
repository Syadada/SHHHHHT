<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'nama_instansi',
        'ip_address',
        'status',
        'password_baru',
        'reset_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resetter()
    {
        return $this->belongsTo(User::class, 'reset_by');
    }
}

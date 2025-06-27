<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'tb_akun';
    protected $primaryKey = 'ID_Akun';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function log()
    {
        return $this->hasMany(Log::class, 'ID_Akun');
    }
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'ID_Akun');
    }

    public function isKesiswaan()
    {
        return $this->role === 'kesiswaan';
    }

    public function isBK()
    {
        return $this->role === 'bk';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isPKS()
    {
        return $this->role === 'pks';
    }
}

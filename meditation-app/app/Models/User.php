<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const AVATARS = ['lotus', 'moon', 'sun', 'mountain', 'wave', 'leaf', 'flame', 'star'];

    protected $fillable = ['name', 'email', 'password', 'role', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function avatarUrl(): string
    {
        $key = in_array($this->avatar, self::AVATARS, true) ? $this->avatar : 'lotus';
        return asset("images/avatars/{$key}.svg");
    }

    public function audios(): HasMany
    {
        return $this->hasMany(Audio::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

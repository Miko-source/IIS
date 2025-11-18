<?php

namespace App\Models;

use App\Enums\UserRole;      // ← přidej
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // pokud používáš mass assignment:
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class, // 
        ];
    }

    // malé helpery se hodí
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function hasRole(UserRole|string $role): bool
    {
        if ($role instanceof UserRole) {
            return $this->role === $role;
        }

        return $this->role?->value === $role;
    }
}

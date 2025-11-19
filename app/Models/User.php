<?php

namespace App\Models;


use App\Enums\UserRole;      
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

   
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
            'role' => UserRole::class, 
        ];
    }

    
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

    public function hasRoleOrHigher(UserRole|string $role): bool
    {
        $roleHierarchy = [
            UserRole::WORKER->value => 1,
            UserRole::CAMPAIGN_MANAGER->value => 2,
            UserRole::COORDINATOR->value => 3,
            UserRole::ADMIN->value => 4,
        ];
        $currentRole = $this->role->value; 
        $neededRole = $role instanceof UserRole ? $role->value: $role;

        if (!isset($roleHierarchy[$currentRole]) || !isset($roleHierarchy[$neededRole])) {
                return false;
        }
        $current = $roleHierarchy[$currentRole];
        $needed = $roleHierarchy[$neededRole];
        
        return $current>=$needed;
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_user')
                    ->withPivot('is_confirmed')
                    ->withTimestamps();
    }

}

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
// kontrola zda je aktualni role vyssi nebo rovna nez pozadovana
    public function hasRoleOrHigher(UserRole|string $role): bool
    {
        $roleHierarchy = [
            UserRole::DEACTIVATED->value => 0,
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

    public function campaignsVisible()
{
    return $this->belongsToMany(Campaign::class, 'campaign_user')
        ->withTimestamps();
}
public function promoteToCampaignManager()
{
    
    if ($this->hasRoleOrHigher(\App\Enums\UserRole::CAMPAIGN_MANAGER)) {
        return;
    }

    // nastav roli
    $this->role = \App\Enums\UserRole::CAMPAIGN_MANAGER;
    $this->save();
}
public function refreshRole()
{
    // Pokud deaktivovany, zustava 
    if ($this->role === UserRole::DEACTIVATED) {
        return;
    }
    // Admin se nesmí měnit
    if ($this->role === UserRole::ADMIN) {
        return;
    }

    // spravce kampaně
    $isManager = \App\Models\Campaign::where('user_id', $this->id)->exists();
    if ($isManager) {
        $this->role = UserRole::CAMPAIGN_MANAGER;
        $this->save();
        return;
    }

    // koordinator kroku
    $isCoordinator = \App\Models\CampaignStep::where('user_id', $this->id)->exists();
    if ($isCoordinator) {
        $this->role = UserRole::COORDINATOR;
        $this->save();
        return;
    }

    //  Jinak worker
    $this->role = UserRole::WORKER;
    $this->save();
}

public function getStrongestRole(): string
{
    // Admin
    if (\App\Models\Campaign::where('user_id', $this->id)->exists()) {
        return UserRole::CAMPAIGN_MANAGER->value;
    }

    //  Coordinator
    if (\App\Models\CampaignStep::where('user_id', $this->id)->exists()) {
        return UserRole::COORDINATOR->value;
    }

    // campaign worker
    $isCampaignWorker = \App\Models\Campaign::whereHas('workers', function ($q) {
        $q->where('users.id', $this->id);
    })->exists();

    if ($isCampaignWorker) {
        return UserRole::WORKER->value;
    }

    // activity user
    if (\App\Models\ActivityUser::where('user_id', $this->id)->exists()) {
        return UserRole::WORKER->value;
    }

    // default
    return UserRole::WORKER->value;
}





}

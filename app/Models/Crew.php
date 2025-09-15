<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'invite_code',
        'owner_id'
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Generate unique invite code when creating crew
        static::creating(function ($crew) {
            $crew->invite_code = strtoupper(Str::random(8));
        });
    }

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'crew_users')
                    ->withPivot('role', 'custom_permissions', 'crew_role_id')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customRoles()
    {
        return $this->hasMany(CrewRole::class);
    }

    // Helper methods
    public function isOwner($user)
    {
        return $this->owner_id === $user->id;
    }

    public function hasMember($user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function getMemberRole($user)
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->role : null;
    }

    // Permission methods with custom roles support
    public function canUserUpload($user)
    {
        if ($this->isOwner($user)) return true;
        
        $member = $this->members()->where('user_id', $user->id)->first();
        if (!$member) return false;
        
        // Check custom role first
        if ($member->pivot->crew_role_id) {
            $customRole = CrewRole::find($member->pivot->crew_role_id);
            return $customRole ? $customRole->canUpload() : false;
        }
        
        // Fall back to default roles
        $role = $member->pivot->role;
        return in_array($role, ['editor', 'uploader']);
    }

    public function canUserEdit($user)
    {
        if ($this->isOwner($user)) return true;
        
        $member = $this->members()->where('user_id', $user->id)->first();
        if (!$member) return false;
        
        // Check custom role first
        if ($member->pivot->crew_role_id) {
            $customRole = CrewRole::find($member->pivot->crew_role_id);
            return $customRole ? $customRole->canEdit() : false;
        }
        
        // Fall back to default roles
        return $member->pivot->role === 'editor';
    }

    public function canUserDelete($user)
    {
        if ($this->isOwner($user)) return true;
        
        $member = $this->members()->where('user_id', $user->id)->first();
        if (!$member) return false;
        
        // Check custom role first
        if ($member->pivot->crew_role_id) {
            $customRole = CrewRole::find($member->pivot->crew_role_id);
            return $customRole ? $customRole->canDelete() : false;
        }
        
        // Owners only by default
        return false;
    }

    public function canUserManageMembers($user)
    {
        if ($this->isOwner($user)) return true;
        
        $member = $this->members()->where('user_id', $user->id)->first();
        if (!$member) return false;
        
        // Check custom role first
        if ($member->pivot->crew_role_id) {
            $customRole = CrewRole::find($member->pivot->crew_role_id);
            return $customRole ? $customRole->canManageMembers() : false;
        }
        
        return false;
    }

    // Get member's role info (including custom roles)
    public function getMemberRoleInfo($user)
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        if (!$member) return null;
        
        if ($member->pivot->crew_role_id) {
            $customRole = CrewRole::find($member->pivot->crew_role_id);
            if ($customRole) {
                return [
                    'type' => 'custom',
                    'name' => $customRole->name,
                    'color' => $customRole->color,
                    'role' => $customRole
                ];
            }
        }
        
        return [
            'type' => 'default',
            'name' => ucfirst($member->pivot->role),
            'color' => 'blue',
            'role' => $member->pivot->role
        ];
    }
}
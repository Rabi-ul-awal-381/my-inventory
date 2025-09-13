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
        return $this->belongsToMany(User::class, 'crew_user')
                    ->withPivot('role', 'custom_permissions')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(Item::class);
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

    public function canUserUpload($user)
    {
        if ($this->isOwner($user)) return true;
        
        $role = $this->getMemberRole($user);
        return in_array($role, ['editor', 'uploader']);
    }

    public function canUserEdit($user)
    {
        if ($this->isOwner($user)) return true;
        
        $role = $this->getMemberRole($user);
        return $role === 'editor';
    }

    public function canUserDelete($user)
    {
        return $this->isOwner($user);
    }
}
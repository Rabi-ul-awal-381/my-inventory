<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrewRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_id',
        'name',
        'color',
        'permissions',
        'description'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    // Relationships
    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function members()
    {
        return $this->hasMany('App\Models\User', 'crew_role_id', 'id')
                    ->wherePivot('crew_id', $this->crew_id);
    }

    // Permission helpers
    public function canUpload()
    {
        return $this->permissions['can_upload'] ?? false;
    }

    public function canEdit()
    {
        return $this->permissions['can_edit'] ?? false;
    }

    public function canDelete()
    {
        return $this->permissions['can_delete'] ?? false;
    }

    public function canManageMembers()
    {
        return $this->permissions['can_manage_members'] ?? false;
    }

    public function canViewItems()
    {
        return $this->permissions['can_view_items'] ?? true;
    }

    // Get available colors for roles
    public static function getAvailableColors()
    {
        return [
            'blue' => 'Blue',
            'green' => 'Green',
            'purple' => 'Purple',
            'red' => 'Red',
            'yellow' => 'Yellow',
            'indigo' => 'Indigo',
            'pink' => 'Pink',
            'gray' => 'Gray'
        ];
    }

    // Get default permission structure
    public static function getDefaultPermissions()
    {
        return [
            'can_view_items' => true,
            'can_upload' => false,
            'can_edit' => false,
            'can_delete' => false,
            'can_manage_members' => false,
        ];
    }
}
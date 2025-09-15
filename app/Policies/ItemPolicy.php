<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        // Owner of any crew gets full access for items in their crew
        // (we still check crew membership elsewhere; this shortcut helps)
        return null;
    }

    /**
     * Determine whether the user can view the item.
     */
    public function view(User $user, Item $item): bool
    {
        // membership check done in middleware; but double-check here
        $pivot = $user->crews()->where('crew_id', $item->crew_id)->first()?->pivot;
        if (!$pivot) return false;

        $perms = json_decode($pivot->permissions ?? '{}', true);
        return !empty($perms['can_view']) || $item->crew->owner_id === $user->id;
    }

    /**
     * Determine whether the user can create items for the crew.
     * Note: for store we pass the crew context instead of Item, so we check via pivot in controller/middleware.
     */
    public function create(User $user): bool
    {
        // Not used by gate directly since store checks pivot, but implement defensively:
        return $user->crews()->wherePivot('permissions->can_upload', true)->exists()
            || $user->ownedCrews()->exists();
    }

    /**
     * Determine whether the user can update the item.
     */

    public function update(User $user, Item $item): bool
{
    if ($item->crew->owner_id === $user->id) return true;

    $pivot = $user->crews()->where('crew_id', $item->crew_id)->first()?->pivot;
    if (!$pivot) return false;

    // Check JSON perms
    $perms = json_decode($pivot->permissions ?? '{}', true);
    if (!empty($perms['can_edit'])) {
        return true;
    }

    // Fallback to role defaults
    $rolePermissions = [
        'viewer' => [],
        'editor' => ['can_edit'],
        'manager' => ['can_edit', 'can_delete'],
    ];

    return in_array('can_edit', $rolePermissions[$pivot->role] ?? []);
}

public function delete(User $user, Item $item): bool
{
    if ($item->crew->owner_id === $user->id) return true;

    $pivot = $user->crews()->where('crew_id', $item->crew_id)->first()?->pivot;
    if (!$pivot) return false;

    $perms = json_decode($pivot->permissions ?? '{}', true);
    if (!empty($perms['can_delete'])) {
        return true;
    }

    $rolePermissions = [
        'viewer' => [],
        'editor' => ['can_edit'],
        'manager' => ['can_edit', 'can_delete'],
    ];

    return in_array('can_delete', $rolePermissions[$pivot->role] ?? []);
}

}

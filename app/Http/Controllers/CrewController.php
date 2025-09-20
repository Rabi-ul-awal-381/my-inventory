<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CrewRole;






class CrewController extends Controller
{
    // Show all crews user belongs to
    public function index()
    {
        $user = Auth::user();
        $ownedCrews = $user->ownedCrews()->withCount('members', 'items')->get();
        $memberCrews = $user->crews()->withCount('members', 'items')->get();
        
        return view('crews.index', compact('ownedCrews', 'memberCrews'));
    }

    // Show create crew form
    public function create()
    {
        return view('crews.create');
    }

    // Store new crew
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $crew = Crew::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id()
        ]);

        // Owner automatically becomes a member with owner role
        $crew->members()->attach(Auth::id(), ['role' => 'owner']);

        return redirect()->route('crews.show', $crew)
                        ->with('success', 'Crew created successfully!');
    }

    // Show single crew
    public function show(Crew $crew)
    {
        // Check if user is a member
        if (!$crew->hasMember(Auth::user())) {
            abort(403, 'You are not a member of this crew.');
        }

        $crew->load('members', 'items.user', 'owner');
        $userRole = $crew->getMemberRole(Auth::user());

        $customRoles = $crew->roles()->get(); // fetch roles defined for this crew
        
        return view('crews.show', compact('crew', 'userRole', 'customRoles'));
    }

    // Show edit form (owner only)
    public function edit(Crew $crew)
    {
        if (!$crew->isOwner(Auth::user())) {
            abort(403, 'Only crew owners can edit crew settings.');
        }

        return view('crews.edit', compact('crew'));
    }

    // Update crew (owner only)
    public function update(Request $request, Crew $crew)
    {
        if (!$crew->isOwner(Auth::user())) {
            abort(403, 'Only crew owners can edit crew settings.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $crew->update($request->only('name', 'description'));

        return redirect()->route('crews.show', $crew)
                        ->with('success', 'Crew updated successfully!');
    }

    // Delete crew (owner only)
    public function destroy(Crew $crew)
    {
        if (!$crew->isOwner(Auth::user())) {
            abort(403, 'Only crew owners can delete crews.');
        }

        $crew->delete();

        return redirect()->route('crews.index')
                        ->with('success', 'Crew deleted successfully!');
    }

    // Show join crew form
    public function joinForm()
    {
        return view('crews.join');
    }

    // Join crew via invite code
    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|size:8'
        ]);

        $crew = Crew::where('invite_code', strtoupper($request->invite_code))->first();

        if (!$crew) {
            return back()->withErrors(['invite_code' => 'Invalid invite code.']);
        }

        if ($crew->hasMember(Auth::user())) {
            return redirect()->route('crews.show', $crew)
                            ->with('info', 'You are already a member of this crew.');
        }

        $crew->members()->attach(Auth::id(), ['role' => 'viewer']);

        return redirect()->route('crews.show', $crew)
                        ->with('success', 'Successfully joined the crew!');
    }

    // Leave crew
    public function leave(Crew $crew)
    {
        if ($crew->isOwner(Auth::user())) {
            return back()->withErrors(['error' => 'Crew owners cannot leave their own crew. Transfer ownership or delete the crew instead.']);
        }

        $crew->members()->detach(Auth::id());

        return redirect()->route('crews.index')
                        ->with('success', 'Successfully left the crew.');
    }

    // Update member role (owner only)
    public function updateMemberRole(Request $request, Crew $crew, User $user)
    {
        if (!$crew->isOwner(Auth::user())) {
            abort(403, 'Only crew owners can manage member roles.');
        }

        $request->validate([
            'role_type' => 'required|in:default,custom',
            'role' => 'required_if:role_type,default',   // <-- changed here
            'role_id' => 'required_if:role_type,custom',
        ]);
        
        

        $crew->members()->updateExistingPivot($user->id, ['role' => $request->role]);

        return back()->with('success', 'Member role updated successfully!');
    }

    // Remove member (owner only)
    public function removeMember(Crew $crew, User $user)
    {
        if (!$crew->isOwner(Auth::user())) {
            abort(403, 'Only crew owners can remove members.');
        }

        if ($crew->isOwner($user)) {
            return back()->withErrors(['error' => 'Cannot remove the crew owner.']);
        }

        $crew->members()->detach($user->id);

        return back()->with('success', 'Member removed from crew successfully!');
    }

    public function roles(Crew $crew)
{
    if (!$crew->isOwner(Auth::user())) {
        abort(403, 'Only crew owners can manage roles.');
    }

    $crew->load('customRoles');
    return view('crews.roles', compact('crew'));
}

// Create custom role form
public function createRole(Crew $crew)
{
    if (!$crew->isOwner(Auth::user())) {
        abort(403, 'Only crew owners can create roles.');
    }

    $availableColors = CrewRole::getAvailableColors();
    $defaultPermissions = CrewRole::getDefaultPermissions();
    
    return view('crews.create-role', compact('crew', 'availableColors', 'defaultPermissions'));
}

// Store custom role
public function storeRole(Request $request, Crew $crew)
{
    if (!$crew->isOwner(Auth::user())) {
        abort(403, 'Only crew owners can create roles.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'color' => 'required|string|in:' . implode(',', array_keys(CrewRole::getAvailableColors())),
        'description' => 'nullable|string|max:500',
        'permissions' => 'required|array',
        'permissions.can_view_items' => 'boolean',
        'permissions.can_upload' => 'boolean', 
        'permissions.can_edit' => 'boolean',
        'permissions.can_delete' => 'boolean',
        'permissions.can_manage_members' => 'boolean',
    ]);

    // Check for duplicate role names in this crew
    if ($crew->customRoles()->where('name', $request->name)->exists()) {
        return back()->withErrors(['name' => 'A role with this name already exists in this crew.']);
    }

    $crew->customRoles()->create([
        'name' => $request->name,
        'color' => $request->color,
        'description' => $request->description,
        'permissions' => $request->permissions,
    ]);

    return redirect()->route('crews.roles', $crew)->with('success', 'Custom role created successfully!');
}

// Edit custom role
public function editRole(Crew $crew, CrewRole $role)
{
    if (!$crew->isOwner(Auth::user()) || $role->crew_id !== $crew->id) {
        abort(403, 'Unauthorized.');
    }

    $availableColors = CrewRole::getAvailableColors();
    $defaultPermissions = CrewRole::getDefaultPermissions();
    
    return view('crews.edit-role', compact('crew', 'role', 'availableColors', 'defaultPermissions'));
}

// Update custom role
public function updateRole(Request $request, Crew $crew, CrewRole $role)
{
    if (!$crew->isOwner(Auth::user()) || $role->crew_id !== $crew->id) {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'color' => 'required|string|in:' . implode(',', array_keys(CrewRole::getAvailableColors())),
        'description' => 'nullable|string|max:500',
        'permissions' => 'required|array',
        'permissions.can_view_items' => 'boolean',
        'permissions.can_upload' => 'boolean',
        'permissions.can_edit' => 'boolean', 
        'permissions.can_delete' => 'boolean',
        'permissions.can_manage_members' => 'boolean',
    ]);

    // Check for duplicate role names (excluding current role)
    if ($crew->customRoles()->where('name', $request->name)->where('id', '!=', $role->id)->exists()) {
        return back()->withErrors(['name' => 'A role with this name already exists in this crew.']);
    }

    $role->update([
        'name' => $request->name,
        'color' => $request->color,
        'description' => $request->description,
        'permissions' => $request->permissions,
    ]);

    return redirect()->route('crews.roles', $crew)->with('success', 'Custom role updated successfully!');
}

// Delete custom role
public function destroyRole(Crew $crew, CrewRole $role)
{
    if (!$crew->isOwner(Auth::user()) || $role->crew_id !== $crew->id) {
        abort(403, 'Unauthorized.');
    }

    // Remove this role from all members (they'll fall back to default roles)
    $crew->members()->where('crew_role_id', $role->id)->update(['crew_role_id' => null]);

    $role->delete();

    return redirect()->route('crews.roles', $crew)->with('success', 'Custom role deleted successfully!');
}

// Assign custom role to member
public function assignRole(Request $request, Crew $crew, User $user)
{
    if (!$crew->isOwner(Auth::user()) && !$crew->canUserManageMembers(Auth::user())) {
        abort(403, 'You do not have permission to manage member roles.');
    }

    if ($request->role_type === 'custom') {
        $customRole = CrewRole::findOrFail($request->role_id);
        if ($customRole->crew_id !== $crew->id) {
            abort(403, 'Invalid role for this crew.');
        }

        $crew->members()->updateExistingPivot($user->id, [
            'crew_role_id' => $customRole->id,
            'role' => 'viewer' // Keep default as fallback
        ]);
    } else {
        $crew->members()->updateExistingPivot($user->id, [
            'crew_role_id' => null,
            'role' => $request->role
        ]);
    }

    return back()->with('success', 'Member role updated successfully!');
}



}
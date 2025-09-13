<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        return view('crews.show', compact('crew', 'userRole'));
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
            'role' => 'required|in:viewer,uploader,editor'
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
}
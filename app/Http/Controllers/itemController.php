<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Show all items for the logged-in user
    public function index()
    {
        $items = Auth::user()->items()->with('crew')->latest()->get();
        return view('items.index', compact('items'));
    }

    // Show the create form
    public function create(Request $request)
    {
        $categories = [
            'Shirt',
            'Pants',
            'Dress',
            'Shoes',
            'Hat/Cap',
            'Jacket',
            'Accessories',
            'Other'
        ];
        
        $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];
        
        // Get crews where user can upload
        $availableCrews = Auth::user()->crews()->get()->filter(function ($crew) {
            return $crew->canUserUpload(Auth::user());
        });
        
        // Add owned crews
        $ownedCrews = Auth::user()->ownedCrews;
        $availableCrews = $availableCrews->merge($ownedCrews)->unique('id');
        
        $selectedCrewId = $request->get('crew');
        
        return view('items.create', compact('categories', 'conditions', 'availableCrews', 'selectedCrewId'));
    }

    // Store a new item
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'brand' => 'nullable|string',
            'condition' => 'required|string',
            'crew_id' => 'nullable|exists:crews,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if user can upload to this crew
        if ($request->crew_id) {
            $crew = Crew::findOrFail($request->crew_id);
            if (!$crew->canUserUpload(Auth::user())) {
                abort(403, 'You do not have permission to upload items to this crew.');
            }
        }

        $data = $request->except('image');
        $data['user_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $data['image_path'] = $imagePath;
        }

        Item::create($data);

        if ($request->crew_id) {
            return redirect()->route('crews.show', $request->crew_id)->with('success', 'Item added to crew successfully!');
        }

        return redirect()->route('items.index')->with('success', 'Item uploaded successfully!');
    }

    // Show a single item
    public function show(Item $item)
    {
        // Check if user can see this item
        if ($item->crew_id) {
            // Item belongs to a crew - check crew membership
            if (!$item->crew->hasMember(Auth::user())) {
                abort(403, 'You do not have access to this item.');
            }
        } else {
            // Personal item - only owner can see
            if ($item->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        }

        return view('items.show', compact('item'));
    }

    // Show edit form
    public function edit(Item $item)
    {
        // Check permissions
        if ($item->crew_id) {
            if (!$item->crew->canUserEdit(Auth::user()) && $item->user_id !== Auth::id()) {
                abort(403, 'You do not have permission to edit this item.');
            }
        } else {
            if ($item->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        }

        $categories = [
            'Shirt',
            'Pants', 
            'Dress',
            'Shoes',
            'Hat/Cap',
            'Jacket',
            'Accessories',
            'Other'
        ];
        
        $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];

        return view('items.edit', compact('item', 'categories', 'conditions'));
    }

    // Update an item
    public function update(Request $request, Item $item)
    {
        // Check permissions
        if ($item->crew_id) {
            if (!$item->crew->canUserEdit(Auth::user()) && $item->user_id !== Auth::id()) {
                abort(403, 'You do not have permission to edit this item.');
            }
        } else {
            if ($item->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'brand' => 'nullable|string',
            'condition' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            
            $imagePath = $request->file('image')->store('items', 'public');
            $data['image_path'] = $imagePath;
        }

        $item->update($data);

        return redirect()->route('items.show', $item)->with('success', 'Item updated successfully!');
    }

    // Delete an item
    public function destroy(Item $item)
    {
        // Check permissions
        if ($item->crew_id) {
            if (!$item->crew->canUserDelete(Auth::user()) && $item->user_id !== Auth::id()) {
                abort(403, 'You do not have permission to delete this item.');
            }
        } else {
            if ($item->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
        }

        // Delete image if exists
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $crew = $item->crew; // Store before deletion
        $item->delete();

        if ($crew) {
            return redirect()->route('crews.show', $crew)->with('success', 'Item deleted successfully!');
        }

        return redirect()->route('items.index')->with('success', 'Item deleted successfully!');
    }
}
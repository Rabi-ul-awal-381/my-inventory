<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $crew->name }} - Crew Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">
                        <a href="{{ route('home') }}" class="hover:text-blue-200">Crew Inventory</a>
                    </h1>
                    <p class="text-blue-200">{{ $crew->name }}</p>
                </div>
                
                <div class="space-x-4 flex items-center">
                    <a href="{{ route('crews.index') }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        My Crews
                    </a>
                    <span class="bg-{{ $userRole === 'owner' ? 'green' : 'blue' }}-100 text-{{ $userRole === 'owner' ? 'green' : 'blue' }}-800 px-2 py-1 rounded text-xs uppercase">
                        {{ $userRole }}
                    </span>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <!-- Success Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Crew Info -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">{{ $crew->name }}</h2>
                                @if($crew->description)
                                    <p class="text-gray-600 mt-2">{{ $crew->description }}</p>
                                @endif
                            </div>
                            @if($crew->isOwner(Auth::user()))
                                <div class="flex space-x-2">
                                    <a href="{{ route('crews.edit', $crew) }}" class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600">
                                        Edit
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ $crew->members->count() }}</p>
                                <p class="text-sm text-gray-600">Members</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $crew->items->count() }}</p>
                                <p class="text-sm text-gray-600">Items</p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-sm font-mono font-bold text-purple-600">{{ $crew->invite_code }}</p>
                                <p class="text-sm text-gray-600">Invite Code</p>
                            </div>
                        </div>

                        @if($crew->canUserUpload(Auth::user()))
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('items.create') }}?crew={{ $crew->id }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    Add Item to Crew
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Crew Items -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Crew Items ({{ $crew->items->count() }})</h3>
                        
                        @if($crew->items->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($crew->items->take(6) as $item)
                                    <div class="border rounded-lg p-3 hover:shadow-md transition-shadow">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded overflow-hidden">
                                                @if($item->image_path)
                                                    <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-300"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium">{{ $item->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $item->category }}</p>
                                                <p class="text-xs text-gray-500">by {{ $item->user->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($crew->items->count() > 6)
                                <div class="mt-4 text-center">
                                    <p class="text-gray-500 text-sm">... and {{ $crew->items->count() - 6 }} more items</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                                </svg>
                                <p>No items in this crew yet</p>
                                @if($crew->canUserUpload(Auth::user()))
                                    <a href="{{ route('items.create') }}?crew={{ $crew->id }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Add the first item
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Members List -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Members ({{ $crew->members->count() }})</h3>
                        
                        <div class="space-y-3">
                            @foreach($crew->members as $member)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500 capitalize">{{ $member->pivot->role }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($crew->isOwner(Auth::user()) && !$crew->isOwner($member))
                                        <div class="flex space-x-1">
                                            <!-- Role Change Dropdown -->
                                            <form action="{{ route('crews.update-member-role', [$crew, $member]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" onchange="this.form.submit()" class="text-xs border rounded px-1 py-1">
                                                    <option value="viewer" {{ $member->pivot->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                                    <option value="uploader" {{ $member->pivot->role === 'uploader' ? 'selected' : '' }}>Uploader</option>
                                                    <option value="editor" {{ $member->pivot->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                                </select>
                                            </form>
                                            
                                            <!-- Remove Member -->
                                            <form action="{{ route('crews.remove-member', [$crew, $member]) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Remove {{ $member->name }} from crew?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs px-1">×</button>
                                            </form>
                                        </div>
                                    @endif
                                    
                                    @if($crew->isOwner($member))
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">OWNER</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Invite Section -->
                    @if($crew->isOwner(Auth::user()))
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Invite Members</h3>
                            
                            <div class="bg-gray-50 p-3 rounded-lg mb-3">
                                <p class="text-sm text-gray-600 mb-1">Share this invite code:</p>
                                <p class="font-mono text-lg font-bold text-center py-2 bg-white rounded border">{{ $crew->invite_code }}</p>
                            </div>
                            
                            <p class="text-xs text-gray-500">New members will join as viewers. You can change their role after they join.</p>
                        </div>
                    @endif

                    <!-- Role Permissions -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Role Permissions</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-medium">Owner</span>
                                <span class="text-green-600">All permissions</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Editor</span>
                                <span class="text-blue-600">Upload & edit items</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Uploader</span>
                                <span class="text-yellow-600">Upload items only</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Viewer</span>
                                <span class="text-gray-600">View items only</span>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Crew (if not owner) -->
                    @if(!$crew->isOwner(Auth::user()))
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-semibold mb-4 text-red-800">Leave Crew</h3>
                            <p class="text-sm text-gray-600 mb-4">You can leave this crew at any time.</p>
                            
                            <form action="{{ route('crews.leave', $crew) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to leave this crew?')">
                                @csrf
                                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">
                                    Leave Crew
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>
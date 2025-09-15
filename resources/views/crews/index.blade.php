<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Crews - Crew Inventory</title>
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
                    <p class="text-blue-200">My Crews</p>
                </div>
                
                <div class="space-x-4 flex items-center">
                    <a href="{{ route('items.index') }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        My Items
                    </a>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-400 px-3 py-1 rounded text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <!-- Success/Info Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mb-6">
                <a href="{{ route('crews.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
                    <span class="mr-2">+</span> Create New Crew
                </a>
                <a href="{{ route('crews.join-form') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                    <span class="mr-2">🔗</span> Join Crew
                </a>
            </div>

            <!-- Owned Crews Section -->
            @if($ownedCrews->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2">👑</span> Crews I Own ({{ $ownedCrews->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ownedCrews as $crew)
                            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $crew->name }}</h3>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">OWNER</span>
                                </div>
                                
                                @if($crew->description)
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($crew->description, 80) }}</p>
                                @endif
                                <div class="flex justify-between text-sm text-gray-500 mb-4">
    <span>👥 {{ $crew->members_count }} {{ Str::plural('member', $crew->members_count) }}</span>
    <span>📦 {{ $crew->items_count }} {{ Str::plural('item', $crew->items_count) }}</span>
</div>
<div class="text-xs text-purple-600 mb-2">
    🎭 {{ $crew->customRoles->count() }} custom {{ Str::plural('role', $crew->customRoles->count()) }}
</div>

                                <div class="bg-gray-50 p-2 rounded mb-3">
                                    <p class="text-xs text-gray-600">Invite Code:</p>
                                    <p class="font-mono text-sm font-semibold">{{ $crew->invite_code }}</p>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('crews.show', $crew) }}" class="flex-1 bg-blue-500 text-white text-center py-2 rounded text-sm hover:bg-blue-600">
                                        Manage
                                    </a>
                                    <a href="{{ route('crews.edit', $crew) }}" class="flex-1 bg-gray-500 text-white text-center py-2 rounded text-sm hover:bg-gray-600">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Member Crews Section -->
            @if($memberCrews->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="mr-2">👥</span> Crews I'm In ({{ $memberCrews->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($memberCrews as $crew)
                            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $crew->name }}</h3>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium uppercase">
                                        {{ $crew->pivot->role }}
                                    </span>
                                </div>
                                
                                @if($crew->description)
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($crew->description, 80) }}</p>
                                @endif

                                <div class="flex justify-between text-sm text-gray-500 mb-4">
                                    <span>👥 {{ $crew->members_count }} {{ Str::plural('member', $crew->members_count) }}</span>
                                    <span>📦 {{ $crew->items_count }} {{ Str::plural('item', $crew->items_count) }}</span>
                                </div>

                                <p class="text-xs text-gray-500 mb-3">
                                    Owner: {{ $crew->owner->name }}
                                </p>

                                <div class="flex space-x-2">
                                    <a href="{{ route('crews.show', $crew) }}" class="flex-1 bg-blue-500 text-white text-center py-2 rounded text-sm hover:bg-blue-600">
                                        View
                                    </a>
                                    <form action="{{ route('crews.leave', $crew) }}" method="POST" class="flex-1"
                                        onsubmit="return confirm('Are you sure you want to leave this crew?')">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded text-sm hover:bg-red-600">
                                            Leave
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Empty State -->
            @if($ownedCrews->count() === 0 && $memberCrews->count() === 0)
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No crews yet</h3>
                    <p class="text-gray-500 mb-4">Create your first crew or join an existing one to get started.</p>
                    <div class="space-x-4">
                        <a href="{{ route('crews.create') }}" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            Create First Crew
                        </a>
                        <a href="{{ route('crews.join-form') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Join a Crew
                        </a>
                    </div>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
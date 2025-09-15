<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles - {{ $crew->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-purple-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">
                        <a href="{{ route('home') }}" class="hover:text-purple-200">Crew Inventory</a>
                    </h1>
                    <p class="text-purple-200">{{ $crew->name }} - Manage Roles</p>
                </div>
                
                <div class="space-x-4">
                    <a href="{{ route('crews.show', $crew) }}" class="bg-purple-500 hover:bg-purple-400 px-3 py-1 rounded text-sm">
                        Back to Crew
                    </a>
                    <span class="text-purple-200">{{ Auth::user()->name }}</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Custom Roles Section -->
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold">Custom Roles ({{ $crew->customRoles->count() }})</h2>
                            <a href="{{ route('crews.create-role', $crew) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                Create New Role
                            </a>
                        </div>

                        @if($crew->customRoles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($crew->customRoles as $role)
                                    <div class="border border-{{ $role->color }}-200 rounded-lg p-4 bg-{{ $role->color }}-50">
                                        <div class="flex justify-between items-start mb-3">
                                            <h3 class="font-semibold text-{{ $role->color }}-800">{{ $role->name }}</h3>
                                            <span class="bg-{{ $role->color }}-200 text-{{ $role->color }}-800 px-2 py-1 rounded text-xs">
                                                {{ ucfirst($role->color) }}
                                            </span>
                                        </div>

                                        @if($role->description)
                                            <p class="text-sm text-{{ $role->color }}-700 mb-3">{{ $role->description }}</p>
                                        @endif

                                        <!-- Permissions Preview -->
                                        <div class="mb-3">
                                            <p class="text-xs font-medium text-{{ $role->color }}-800 mb-1">Permissions:</p>
                                            <div class="flex flex-wrap gap-1">
                                                @if($role->canUpload())
                                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">📤 Upload</span>
                                                @endif
                                                @if($role->canEdit())
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">✏️ Edit</span>
                                                @endif
                                                @if($role->canDelete())
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">🗑️ Delete</span>
                                                @endif
                                                @if($role->canManageMembers())
                                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">👥 Manage</span>
                                                @endif
                                                @if($role->canViewItems())
                                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">👁️ View</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Member Count -->
                                        @php
                                            $memberCount = $crew->members()->where('crew_role_id', $role->id)->count();
                                        @endphp
                                        <p class="text-xs text-{{ $role->color }}-600 mb-3">
                                            👤 {{ $memberCount }} {{ Str::plural('member', $memberCount) }} assigned
                                        </p>

                                        <!-- Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('crews.edit-role', [$crew, $role]) }}" class="flex-1 bg-{{ $role->color }}-600 text-white py-1 px-2 rounded text-sm hover:bg-{{ $role->color }}-700 text-center block">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('crews.destroy-role', [$crew, $role]) }}" class="flex-1"
                                                onsubmit="return confirm('Delete this role? Members will lose this role.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-600 text-white py-1 px-2 rounded text-sm hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-4">
                                    <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No custom roles yet</h3>
                                <p class="text-gray-500 mb-4">Create custom roles like "Photographer", "Stylist", or "Manager" with specific permissions.</p>
                                <a href="{{ route('crews.create-role', $crew) }}" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700">
                                    Create Your First Role
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Default Roles Reference -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Default Roles</h3>
                        <div class="space-y-3">
                            <div class="p-3 bg-green-50 rounded border-l-4 border-green-500">
                                <h4 class="font-medium text-green-800">Owner</h4>
                                <p class="text-sm text-green-600">Full control of everything</p>
                            </div>
                            <div class="p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                                <h4 class="font-medium text-blue-800">Editor</h4>
                                <p class="text-sm text-blue-600">Upload & edit items</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                                <h4 class="font-medium text-yellow-800">Uploader</h4>
                                <p class="text-sm text-yellow-600">Upload items only</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded border-l-4 border-gray-500">
                                <h4 class="font-medium text-gray-800">Viewer</h4>
                                <p class="text-sm text-gray-600">View items only</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Creation Tips -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">💡 Role Ideas</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-medium">📸 Photographer</span>
                                <span class="text-gray-500">Upload + Edit</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">✨ Stylist</span>
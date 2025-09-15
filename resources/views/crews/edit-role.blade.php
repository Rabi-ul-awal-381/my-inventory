<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role - {{ $role->name }}</title>
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
                    <p class="text-purple-200">Edit Role: {{ $role->name }}</p>
                </div>
                
                <div class="space-x-4">
                    <a href="{{ route('crews.roles', $crew) }}" class="bg-purple-500 hover:bg-purple-400 px-3 py-1 rounded text-sm">
                        Back to Roles
                    </a>
                    <span class="text-purple-200">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6">Edit Role: {{ $role->name }}</h2>

                    <form action="{{ route('crews.update-role', [$crew, $role]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Role Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Role Color *</label>
                            <select id="color" name="color" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @foreach($availableColors as $value => $label)
                                    <option value="{{ $value }}" {{ old('color', $role->color) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Permissions *</label>
                            <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                                
                                <label class="flex items-center">
                                    <input type="hidden" name="permissions[can_view_items]" value="0">
                                    <input type="checkbox" name="permissions[can_view_items]" value="1" 
                                        {{ old('permissions.can_view_items', $role->canViewItems()) ? 'checked' : '' }}
                                        class="mr-3">
                                    <div>
                                        <span class="font-medium">👁️ View Items</span>
                                        <p class="text-sm text-gray-600">Can see all items in the crew</p>
                                    </div>
                                </label>

                                <label class="flex items-center">
                                    <input type="hidden" name="permissions[can_upload]" value="0">
                                    <input type="checkbox" name="permissions[can_upload]" value="1" 
                                        {{ old('permissions.can_upload', $role->canUpload()) ? 'checked' : '' }}
                                        class="mr-3">
                                    <div>
                                        <span class="font-medium">📤 Upload Items</span>
                                        <p class="text-sm text-gray-600">Can add new items to the crew</p>
                                    </div>
                                </label>

                                <label class="flex items-center">
                                    <input type="hidden" name="permissions[can_edit]" value="0">
                                    <input type="checkbox" name="permissions[can_edit]" value="1" 
                                        {{ old('permissions.can_edit', $role->canEdit()) ? 'checked' : '' }}
                                        class="mr-3">
                                    <div>
                                        <span class="font-medium">✏️ Edit Items</span>
                                        <p class="text-sm text-gray-600">Can modify item details, descriptions, etc.</p>
                                    </div>
                                </label>

                                <label class="flex items-center">
                                    <input type="hidden" name="permissions[can_delete]" value="0">
                                    <input type="checkbox" name="permissions[can_delete]" value="1" 
                                        {{ old('permissions.can_delete', $role->canDelete()) ? 'checked' : '' }}
                                        class="mr-3">
                                    <div>
                                        <span class="font-medium">🗑️ Delete Items</span>
                                        <p class="text-sm text-gray-600">Can permanently remove items</p>
                                    </div>
                                </label>

                                <label class="flex items-center">
                                    <input type="hidden" name="permissions[can_manage_members]" value="0">
                                    <input type="checkbox" name="permissions[can_manage_members]" value="1" 
                                        {{ old('permissions.can_manage_members', $role->canManageMembers()) ? 'checked' : '' }}
                                        class="mr-3">
                                    <div>
                                        <span class="font-medium">👥 Manage Members</span>
                                        <p class="text-sm text-gray-600">Can change other members' roles</p>
                                    </div>
                                </label>
                            </div>
                            @error('permissions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex space-x-4 pt-4">
                            <button type="submit" class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 font-medium">
                                Update Role
                            </button>
                            <a href="{{ route('crews.roles', $crew) }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 font-medium text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
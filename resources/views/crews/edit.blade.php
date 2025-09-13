<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $crew->name }} - Crew Inventory</title>
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
                    <p class="text-blue-200">Edit Crew</p>
                </div>
                
                <div class="space-x-4">
                    <a href="{{ route('crews.show', $crew) }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        Back to Crew
                    </a>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6">Edit: {{ $crew->name }}</h2>

                    <form action="{{ route('crews.update', $crew) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Crew Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Crew Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $crew->name) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $crew->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Invite Code Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-800 mb-2">Invite Code</h3>
                            <p class="text-blue-700 text-sm mb-2">Current invite code: <span class="font-mono font-bold">{{ $crew->invite_code }}</span></p>
                            <p class="text-blue-600 text-sm">The invite code cannot be changed. If you need a new one, you'll need to create a new crew.</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex space-x-4 pt-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium">
                                Update Crew
                            </button>
                            <a href="{{ route('crews.show', $crew) }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 font-medium text-center">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <!-- Danger Zone -->
                    <div class="mt-8 pt-6 border-t border-red-200">
                        <h3 class="text-lg font-semibold text-red-800 mb-4">Danger Zone</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="font-semibold text-red-800 mb-2">Delete Crew</h4>
                            <p class="text-red-700 text-sm mb-4">
                                Permanently delete this crew and all its items. This action cannot be undone.
                            </p>
                            <form action="{{ route('crews.destroy', $crew) }}" method="POST"
                                onsubmit="return confirm('Are you sure? This will delete the crew and ALL items in it. This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                                    Delete Crew Forever
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
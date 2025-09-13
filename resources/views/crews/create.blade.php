<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Crew - Crew Inventory</title>
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
                    <p class="text-blue-200">Create New Crew</p>
                </div>
                
                <div class="space-x-4">
                    <a href="{{ route('crews.index') }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        My Crews
                    </a>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6">Create Your Crew</h2>

                    <form action="{{ route('crews.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Crew Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Crew Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g. Fashion Squad, Style Crew, The Closet">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="What's this crew about? Who can join?">{{ old('description') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Optional: Describe your crew's purpose or theme</p>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-800 mb-2">What happens when you create a crew?</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• You become the crew owner with full permissions</li>
                                <li>• A unique invite code is generated automatically</li>
                                <li>• You can invite others using the invite code</li>
                                <li>• You control member roles and permissions</li>
                                <li>• Items can be shared within the crew</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex space-x-4 pt-4">
                            <button type="submit" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 font-medium">
                                Create Crew
                            </button>
                            <a href="{{ route('crews.index') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 font-medium text-center">
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Items - Crew Inventory</title>
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
                    <p class="text-blue-200">My Items</p>
                </div>
                
                <div class="space-x-4">
                    <span class="text-blue-200">Welcome, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header with Upload Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">My Items ({{ $items->count() }})</h2>
                <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                    <span class="mr-2">+</span> Upload New Item
                </a>
            </div>

            @if($items->count() > 0)
               <!-- Items Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($items as $item)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <!-- Item Image -->
            <div class="h-48 bg-gray-200 overflow-hidden">
                @if($item->image_path)
                    <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Item Details -->
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-1">{{ $item->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $item->category }}</p>
                
                <!-- Crew Badge -->
                @if($item->crew)
                    <div class="mb-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                            👥 {{ $item->crew->name }}
                        </span>
                    </div>
                @else
                    <div class="mb-2">
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                            🔒 Personal
                        </span>
                    </div>
                @endif
                
                <div class="flex justify-between items-center text-xs text-gray-500 mb-3">
                    @if($item->size)
                        <span>Size: {{ $item->size }}</span>
                    @endif
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $item->condition }}</span>
                </div>

                @if($item->description)
                    <p class="text-sm text-gray-700 mb-3 line-clamp-2">{{ Str::limit($item->description, 80) }}</p>
                @endif

                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('items.show', $item) }}" class="flex-1 bg-blue-500 text-white text-center py-2 rounded text-sm hover:bg-blue-600">
                        View
                    </a>
                    <a href="{{ route('items.edit', $item) }}" class="flex-1 bg-gray-500 text-white text-center py-2 rounded text-sm hover:bg-gray-600">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No items yet</h3>
                    <p class="text-gray-500 mb-4">Start building your inventory by uploading your first item.</p>
                    <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Upload Your First Item
                    </a>
                </div>
            @endif
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }} - Crew Inventory</title>
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
                    <p class="text-blue-200">Item Details</p>
                </div>
                @auth
                <div class="flex justify-evenly space-x-4">
                    @if($item->crew)
                        <a href="{{ route('crews.show', $item->crew) }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                            Back To Crew
                        </a>
                    @endif
                    @endauth
                <div class="space-x-4 ">
                    <a href="{{ route('items.index') }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        My Items
                    </a>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
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

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="md:flex">
                        <!-- Item Image -->
                        <div class="md:w-1/2">
                            @if($item->image_path)
                                <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}" class="w-full h-96 object-cover">
                            @else
                                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Item Details -->
                        <div class="md:w-1/2 p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $item->name }}</h2>
                            <p class="text-lg text-blue-600 mb-4">{{ $item->category }}</p>

                            @if($item->description)
                                <div class="mb-4">
                                    <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
                                    <p class="text-gray-600">{{ $item->description }}</p>
                                </div>
                            @endif

                            <!-- Item Properties -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                @if($item->size)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Size</span>
                                        <p class="text-gray-800">{{ $item->size }}</p>
                                    </div>
                                @endif

                                @if($item->color)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Color</span>
                                        <p class="text-gray-800">{{ $item->color }}</p>
                                    </div>
                                @endif

                                @if($item->brand)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Brand</span>
                                        <p class="text-gray-800">{{ $item->brand }}</p>
                                    </div>
                                @endif

                                <div>
                                    <span class="text-sm font-medium text-gray-500">Condition</span>
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
                                        {{ $item->condition }}
                                    </span>
                                </div>
                            </div>

                            <!-- Meta Information -->
                            <div class="border-t pt-4 mb-6">
                                <p class="text-sm text-gray-500">
                                    Added {{ $item->created_at->diffForHumans() }} by {{ $item->user->name }}
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                            @can('update', $item)
                                <a href="{{ route('items.edit', $item) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Edit Item
                                </a>
                                @endcan

                                @can('delete', $item)
                                
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Delete Item
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
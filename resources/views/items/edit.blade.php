<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $item->name }} - Crew Inventory</title>
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
                    <p class="text-blue-200">Edit Item</p>
                </div>
                
                <div class="space-x-4">
                    <a href="{{ route('items.show', $item) }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                        Back to Item
                    </a>
                    <span class="text-blue-200">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto p-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-6">Edit: {{ $item->name }}</h2>

                    <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Current Image Preview -->
                        @if($item->image_path)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                <img src="{{ $item->getImageUrl() }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        @endif

                        <!-- Same form fields as create, but with values -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                            <select id="category" name="category" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category', $item->category) == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                                <input type="text" id="size" name="size" value="{{ old('size', $item->size) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('size')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                <input type="text" id="color" name="color" value="{{ old('color', $item->color) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('color')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                <input type="text" id="brand" name="brand" value="{{ old('brand', $item->brand) }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('brand')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">Condition *</label>
                            <select id="condition" name="condition" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach($conditions as $condition)
                                    <option value="{{ $condition }}" {{ old('condition', $item->condition) == $condition ? 'selected' : '' }}>
                                        {{ $condition }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Update Photo</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium">
                                Update Item
                            </button>
                            <a href="{{ route('items.show', $item) }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 font-medium text-center">
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
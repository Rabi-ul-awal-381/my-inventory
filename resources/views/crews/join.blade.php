<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join a Crew - Crew Inventory</title>
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
                    <p class="text-blue-200">Join a Crew</p>
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
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-center mb-6">
                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold">Join a Crew</h2>
                        <p class="text-gray-600 text-sm">Enter the invite code provided by the crew owner</p>
                    </div>

                    <form action="{{ route('crews.join') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Invite Code -->
                        <div>
                            <label for="invite_code" class="block text-sm font-medium text-gray-700 mb-1">Invite Code *</label>
                            <input type="text" id="invite_code" name="invite_code" value="{{ old('invite_code') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-center text-lg uppercase"
                                placeholder="ABCD1234"
                                maxlength="8"
                                style="letter-spacing: 2px;">
                            @error('invite_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Need an invite code?</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Ask a crew owner to share their 8-character invite code with you. You'll join as a viewer by default.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex space-x-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium">
                                Join Crew
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

    <script>
        // Auto-uppercase and format invite code
        document.getElementById('invite_code').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase().substring(0, 8);
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">{{ $appName }}</h1>
                    <p class="text-blue-200">Manage your crew's inventory</p>
                </div>
                
                <div class="space-x-4">
                    @if($user)
                        <span class="text-blue-200">Welcome, {{ $user->name }}!</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-400 px-3 py-1 rounded text-sm">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-400 px-3 py-1 rounded text-sm">
                            Register
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto p-4">
            <div class="max-w-2xl mx-auto">
                @if($user)
                    <!-- Logged-in user dashboard -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('items.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 bg-green-50 border-green-200">
                                <h3 class="font-semibold">📱 My Items</h3>
                                <p class="text-sm text-gray-600">View all your uploaded items</p>
                                <span class="text-xs text-green-600">✅ Ready to use!</span>
                            </a>
                            <a href="{{ route('crews.index') }}" class="block p-4 border rounded-lg hover:bg-gray-50 bg-blue-50 border-blue-200">
                                <h3 class="font-semibold">👥 My Crews</h3>
                                <p class="text-sm text-gray-600">Manage your crew memberships</p>
                                <span class="text-xs text-blue-600">✅ Ready to use!</span>
                            </a>
                            <a href="{{ route('items.create') }}" class="block p-4 border rounded-lg hover:bg-gray-50 bg-yellow-50 border-yellow-200">
                                <h3 class="font-semibold">➕ Upload Item</h3>
                                <p class="text-sm text-gray-600">Add new clothing or accessories</p>
                                <span class="text-xs text-yellow-600">✅ Ready to use!</span>
                            </a>
                            <a href="{{ route('crews.join-form') }}" class="block p-4 border rounded-lg hover:bg-gray-50 bg-purple-50 border-purple-200">
                                <h3 class="font-semibold">🔗 Join Crew</h3>
                                <p class="text-sm text-gray-600">Join an existing crew</p>
                                <span class="text-xs text-purple-600">✅ Ready to use!</span>
                            </a>
                        </div>
                @else
                    <!-- Guest user landing page -->
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Welcome to Your Crew Inventory</h2>
                        <p class="text-gray-600 mb-4">
                            A private app to manage clothing and accessories with your crew members.
                        </p>
                        
                        <h3 class="font-semibold mb-2">Features:</h3>
                        <ul class="space-y-2 mb-6">
                            @foreach($features as $feature)
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="space-x-4">
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                                Login
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white p-4 text-center">
            <p>&copy; 2025 {{ $appName }}. Built with Laravel.</p>
        </footer>
    </div>
</body>
</html>
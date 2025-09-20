<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative min-h-screen text-gray-900 overflow-hidden">

    <!-- Animated gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 via-gray-500 to-indigo-800 animate-gradient-x"></div>

    <!-- Floating blurred blobs -->
    <div class="absolute -top-32 -left-32 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-float"></div>
    <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-float-slow"></div>

    <div class="relative flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white/10 backdrop-blur-lg shadow-lg text-white p-4 sticky top-0 z-50 rounded-b-2xl">
            <div class="container mx-auto flex justify-between items-center">
                <div class="animate-fade-in">
                    <h1 class="text-3xl font-extrabold tracking-wide">{{ $appName }}</h1>
                    <p class="text-blue-200">Manage your crew's inventory</p>
                </div>
                
                <div class="space-x-4 animate-slide-in-right">
                    @if($user)
                        <span class="text-blue-200">👋 Welcome, {{ $user->name }}!</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-red-500 to-pink-500 hover:scale-105 transition-transform duration-200 text-white shadow-md">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 hover:scale-105 transition-transform duration-200 text-white shadow-md">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 hover:scale-105 transition-transform duration-200 text-white shadow-md">
                            Register
                        </a>
                    @endif
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto p-6 mt-8">
            <div class="max-w-4xl mx-auto">
                @if($user)
                    <!-- Logged-in user dashboard -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- My Items -->
    <a href="{{ route('items.index') }}" 
       class="relative group block p-6 rounded-2xl bg-black/40 border border-green-400/50 shadow-[0_0_20px_rgba(34,197,94,0.6)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-400 via-transparent to-green-400 opacity-0 group-hover:opacity-20 animate-scan"></div>
        <h3 class="font-extrabold text-xl text-green-300 group-hover:scale-110 transition-transform">📱 My Items</h3>
        <p class="text-green-100/80 mt-2">View all your uploaded items</p>
    </a>

    <!-- My Crews -->
    <a href="{{ route('crews.index') }}" 
       class="relative group block p-6 rounded-2xl bg-black/40 border border-cyan-400/50 shadow-[0_0_20px_rgba(34,211,238,0.6)] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-transparent to-cyan-400 opacity-0 group-hover:opacity-20 animate-glitch"></div>
        <h3 class="font-extrabold text-xl text-cyan-300 group-hover:skew-x-6 transition-transform">👥 My Crews</h3>
        <p class="text-cyan-100/80 mt-2">Manage your crew memberships</p>
    </a>

    <!-- Upload Item -->
    <a href="{{ route('items.create') }}" 
       class="relative group block p-6 rounded-2xl bg-black/40 border border-yellow-400/50 shadow-[0_0_20px_rgba(250,204,21,0.6)] overflow-hidden">
        <span class="absolute -inset-0.5 bg-gradient-to-r from-yellow-400 to-orange-500 opacity-30 blur-lg group-hover:opacity-60 animate-pulse"></span>
        <h3 class="relative font-extrabold text-xl text-yellow-300 group-hover:rotate-3 transition-transform">➕ Upload Item</h3>
        <p class="relative text-yellow-100/80 mt-2">Add new clothing or accessories</p>
    </a>

    <!-- Join Crew -->
    <a href="{{ route('crews.join-form') }}" 
       class="relative group block p-6 rounded-2xl bg-black/40 border border-purple-500/50 shadow-[0_0_20px_rgba(168,85,247,0.6)] overflow-hidden">
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 animate-warp bg-gradient-to-r from-purple-600/30 via-transparent to-purple-600/30"></div>
        <h3 class="relative font-extrabold text-xl text-purple-300 group-hover:scale-125 transition-transform">🔗 Join Crew</h3>
        <p class="relative text-purple-100/80 mt-2">Join an existing crew</p>
    </a>
</div>

                @else
                    <!-- Guest user landing page -->
                    <div class="bg-white/90 rounded-2xl shadow-2xl p-8 animate-fade-in">
                        <h2 class="text-2xl font-bold mb-4 text-center text-indigo-600">Welcome to Your Crew Inventory</h2>
                        <p class="text-gray-700 mb-6 text-center">
                            A private app to manage clothing and accessories with your crew members.
                        </p>
                        
                        <h3 class="font-semibold mb-3">✨ Features:</h3>
                        <ul class="space-y-3 mb-6">
                            @foreach($features as $feature)
                                <li class="flex items-center">
                                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-3 animate-pulse"></span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="flex justify-center space-x-6">
                            <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:scale-105 transition-transform duration-200 text-white shadow-lg">
                                🚀 Get Started
                            </a>
                            <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl border-2 border-blue-600 text-blue-600 hover:bg-blue-50 hover:scale-105 transition-transform duration-200 shadow-lg">
                                🔑 Login
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900/80 text-gray-300 p-4 text-center mt-10 backdrop-blur-md rounded-t-2xl">
            <p>&copy; 2025 <span class="font-semibold text-white">{{ $appName }}</span>. Built with ❤️ & Laravel.</p>
        </footer>
    </div>

    <!-- Animations -->
    <style>

@keyframes scan {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
.animate-scan::before {
  content: "";
  position: absolute;
  top:0;left:0;bottom:0;width:40%;
  background: linear-gradient(90deg, transparent, rgba(34,197,94,0.4), transparent);
  animation: scan 2s linear infinite;
}

@keyframes glitch {
  0%, 100% { clip-path: inset(0 0 0 0); }
  20% { clip-path: inset(10% 0 15% 0); }
  40% { clip-path: inset(40% 0 20% 0); }
  60% { clip-path: inset(25% 0 30% 0); }
  80% { clip-path: inset(5% 0 40% 0); }
}
.animate-glitch {
  animation: glitch 1.5s infinite;
}

@keyframes warp {
  0%,100% { transform: scaleX(0); opacity:0; }
  50% { transform: scaleX(1); opacity:1; }
}
.animate-warp {
  animation: warp 1.8s ease-in-out infinite;
}

        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-right { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        @keyframes float-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(15px); } }

        .animate-fade-in { animation: fade-in 0.8s ease-in-out; }
        .animate-slide-in-right { animation: slide-in-right 0.8s ease-in-out; }
        .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 8s ease infinite; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 10s ease-in-out infinite; }
    </style>
</body>
</html>

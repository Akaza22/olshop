<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift VTO | Virtual Try-On Experience</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .bg-cream { background-color: #FDFBF7; }
        .bg-maroon { background-color: #800000; }
        .text-maroon { color: #800000; }
        .border-maroon { border-color: #800000; }
        .text-brown { color: #5D4037; }
    </style>
</head>
<body class="bg-cream text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="/" class="text-3xl font-serif font-bold text-maroon tracking-tight">
                Thrift<span class="text-brown">VTO</span>
            </a>

            <div class="hidden md:flex items-center space-x-8 font-medium">
                <a href="{{ route('katalog') }}" 
                    class="{{ request()->routeIs('katalog') ? 'text-maroon font-bold' : 'text-gray-600' }} hover:text-maroon transition">
                    Catalog
                </a>
                <a href="{{ route('orders.index') }}" 
                    class="{{ request()->routeIs('orders.index') ? 'text-maroon font-bold' : 'text-gray-600' }} hover:text-maroon transition">
                    Orders
                </a>
                <a href="{{ route('about') }}" 
                    class="{{ request()->routeIs('about') ? 'text-maroon font-bold' : 'text-gray-600' }} hover:text-maroon transition">
                    About 
                </a>
               {{-- Dashboard Admin --}}
                @auth
                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                        class="{{ request()->routeIs('admin.*') ? 'text-maroon font-bold' : 'text-gray-600' }} hover:text-red-900 transition flex items-center gap-2">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Dashboard 
                        </a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center space-x-5">
                <button class="text-gray-600 hover:text-maroon transition">
                    <i data-lucide="search" class="w-6 h-6"></i>
                </button>
                
                <a href="/cart" class="relative text-gray-600 hover:text-maroon transition">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-maroon text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-bounce">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-sm font-semibold text-gray-700">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="/logout">
                            @csrf
                            <button class="text-red-600 text-sm font-bold hover:underline">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="hidden md:block bg-maroon text-white px-5 py-2.5 rounded-full font-semibold hover:bg-red-900 transition shadow-lg shadow-red-900/20">
                        Login
                    </a>
                @endauth


                <button class="md:hidden text-gray-600">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-6 py-10 w-full">
        @yield('content')
    </main>

    <footer class="bg-stone-900 text-stone-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-stone-800 pb-12">
            <div class="col-span-1 md:col-span-1">
                <h3 class="text-2xl font-serif font-bold text-white mb-4">ThriftVTO</h3>
                <p class="text-sm leading-relaxed">
                    Taking the vintage shopping experience to the next level with Virtual Try-On technology.
                </p>
            </div>
            
            <div>
                <h4 class="font-bold text-white mb-4 uppercase text-xs tracking-widest">Shopping</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">All Products</a></li>
                    <li><a href="#" class="hover:text-white transition">Jackets & Outerwear</a></li>
                    <li><a href="#" class="hover:text-white transition">Size Guide</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-4 uppercase text-xs tracking-widest">Help</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Delivery</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-4 uppercase text-xs tracking-widest">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="p-2 bg-stone-800 rounded-full hover:bg-maroon transition"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    <a href="#" class="p-2 bg-stone-800 rounded-full hover:bg-maroon transition"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-8 text-center text-xs text-stone-500">
            <p>&copy; 2026 ThriftVTO Project. All rights reserved.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
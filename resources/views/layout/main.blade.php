<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LockerByBrokeAngel</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
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
        [v-cloak] { display: none; }
    </style>
</head>
<body class="bg-cream text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" 
                    alt="Logo" 
                    class="h-10 w-auto object-contain transition-transform group-hover:scale-105">

                <span class="text-2xl font-serif font-bold tracking-tight">
                    <span class="text-maroon">LockerBy</span><span class="text-brown italic">BrokeAngel</span>
                </span>
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
                <form action="{{ route('product.search') }}" method="GET" class="relative flex items-center">
                    <input type="text" 
                        name="query" 
                        placeholder="Search..." 
                        value="{{ request('query') }}"
                        class="bg-stone-100 border-none rounded-full py-2 pl-4 pr-10 text-sm focus:ring-2 focus:ring-maroon transition-all w-40 md:w-60">
                    
                    <button type="submit" class="absolute right-3 text-gray-600 hover:text-maroon transition">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>
                </form>
                
                <a href="/cart" class="relative text-gray-600 hover:text-maroon transition">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    
                    {{-- PERBAIKAN: Menambahkan ID 'cart-badge' dan class hidden jika 0 --}}
                    <span id="cart-badge" class="absolute -top-2 -right-2 bg-maroon text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-bounce {{ (!session('cart') || count(session('cart')) == 0) ? 'hidden' : '' }}">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>

                @auth
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-sm font-semibold text-gray-700">
                            {{ Auth::user()->name }}
                        </span>

                        <form method="POST" action="/logout" class="flex items-center">
                            @csrf
                            <button class="text-red-600 text-sm font-bold hover:text-red-800 transition flex items-center gap-2 group">
                                <i data-lucide="log-out" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
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
                <h3 class="text-2xl font-serif font-bold text-white mb-4">LockerByBrokeAngel</h3>
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
            <p>&copy; 2026 LockerByBrokeAngel Project. All rights reserved.</p>
        </div>
        {{-- GLOBAL TOAST NOTIFICATION --}}
        <div id="global-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[100] transition-all duration-500 opacity-0 translate-y-20 pointer-events-none">
            <div class="bg-stone-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10">
                <div id="global-toast-icon-bg" class="w-8 h-8 rounded-full flex items-center justify-center text-white">
                    <i id="global-toast-icon" data-lucide="check" class="w-4 h-4"></i>
                </div>
                <div>
                    <p id="global-toast-title" class="text-[10px] font-black uppercase tracking-widest text-green-400">Success</p>
                    <p id="global-toast-message" class="text-xs text-stone-300"></p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

            function showGlobalToast(message, type = 'success') {
            const toast = document.getElementById('global-toast');
            const title = document.getElementById('global-toast-title');
            const msg = document.getElementById('global-toast-message');
            const iconBg = document.getElementById('global-toast-icon-bg');
            
            msg.innerText = message;
            
            if(type === 'success') {
                iconBg.className = "w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white";
                title.className = "text-[10px] font-black uppercase tracking-widest text-green-400";
                title.innerText = "Success";
            } else {
                iconBg.className = "w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white";
                title.className = "text-[10px] font-black uppercase tracking-widest text-red-400";
                title.innerText = "Error";
            }

            toast.classList.remove('opacity-0', 'translate-y-20');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-20');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 4000);
        }

        @if(session('success'))
            showGlobalToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error') || $errors->any())
            showGlobalToast("{{ session('error') ?? $errors->first() }}", 'error');
        @endif
    </script>
</body>
</html>
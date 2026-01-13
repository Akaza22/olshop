@extends('layout.main')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-stone-100">
        
        <div class="hidden md:block md:w-1/2 relative">
            {{-- Gambar lokal hero.jpg --}}
            <img src="{{ asset('images/hero.jpg') }}" 
                 alt="Thrift Hero" 
                 class="w-full h-full object-cover">
            
            {{-- Overlay Quote di Tengah --}}
            <div class="absolute inset-0 bg-maroon/40 backdrop-blur-[2px] flex items-center justify-center p-12 text-center">
                <div class="text-white max-w-md">
                    <i data-lucide="quote" class="w-12 h-12 mb-6 opacity-60 mx-auto"></i>
                    
                    <h2 class="text-3xl md:text-4xl font-serif font-bold leading-tight mb-6">
                        "Fashion is what you buy, style is what you do with it."
                    </h2>
                    
                    <div class="flex items-center justify-center gap-4 text-stone-300 font-medium tracking-widest uppercase text-xs">
                        <span class="h-px w-8 bg-stone-400"></span>
                        ThriftVTO
                        <span class="h-px w-8 bg-stone-400"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-20 flex flex-col justify-center bg-white">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-serif font-bold text-stone-900 mb-2">Sign In</h1>
                <p class="text-stone-500">Please sign in to continue your Virtual Try-On experience.</p>
            </div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </span>
                        <input type="email" name="email" required 
                               placeholder="name@email.com"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-stone-400">Password</label>
                        <a href="#" class="text-xs font-bold text-maroon hover:underline transition">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </span>
                        <input type="password" name="password" required 
                               placeholder="••••••••"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200">
                    </div>
                </div>

                <div class="pt-4">
                    <button class="w-full bg-maroon text-white py-4 rounded-xl font-bold text-lg hover:bg-red-900 transition shadow-lg shadow-maroon/20 flex items-center justify-center gap-2 group">
                        <span>Sign In Now</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

                <p class="text-center text-stone-500 text-sm mt-8">
                    Don't have an account? 
                    <a href="/register" class="text-maroon font-bold hover:underline transition">Sign Up here</a>
                </p>
            </form>
        </div>

    </div>
</div>

<script>
    // Inisialisasi icon lucide
    lucide.createIcons();
</script>
@endsection
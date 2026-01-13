@extends('layout.main')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-stone-100">
        
        <div class="hidden md:block md:w-1/2 relative">
            <img src="{{ asset('images/hero.jpg') }}" 
                 alt="Thrift Registration" 
                 class="w-full h-full object-cover">
            
            {{-- Overlay Quote di Tengah --}}
            <div class="absolute inset-0 bg-maroon/40 backdrop-blur-[2px] flex items-center justify-center p-12 text-center">
                <div class="text-white max-w-md">
                    <i data-lucide="sparkles" class="w-12 h-12 mb-6 opacity-60 mx-auto"></i>
                    
                    <h2 class="text-3xl md:text-4xl font-serif font-bold leading-tight mb-6">
                        "Style is a way to say who you are without having to speak."
                    </h2>
                    
                    <div class="flex items-center justify-center gap-4 text-stone-300 font-medium tracking-widest uppercase text-xs">
                        <span class="h-px w-8 bg-stone-400"></span>
                        Join ThriftVTO
                        <span class="h-px w-8 bg-stone-400"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white">
            <div class="mb-8 text-center md:text-left">
                <h1 class="text-4xl font-serif font-bold text-stone-900 mb-2">Create Account</h1>
                <p class="text-stone-500 text-sm">Join and start your virtual fashion adventure.</p>
            </div>

            <form method="POST" action="/register" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-1">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="name" required 
                               placeholder="John Doe"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" required 
                               placeholder="nama@email.com"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </span>
                            <input type="password" name="password" required 
                                   placeholder="••••••••"
                                   class="w-full bg-stone-50 border border-stone-200 rounded-xl py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-1">Confirm</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </span>
                            <input type="password" name="password_confirmation" required 
                                   placeholder="••••••••"
                                   class="w-full bg-stone-50 border border-stone-200 rounded-xl py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition duration-200 text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button class="w-full bg-maroon text-white py-4 rounded-xl font-bold text-lg hover:bg-red-900 transition shadow-lg shadow-maroon/20 flex items-center justify-center gap-2 group">
                        <span>Sign Up Now</span>
                        <i data-lucide="user-plus" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>

                <p class="text-center text-stone-500 text-sm mt-8">
                    Already have an account? 
                    <a href="/login" class="text-maroon font-bold hover:underline transition">Sign in here</a>
                </p>
            </form>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
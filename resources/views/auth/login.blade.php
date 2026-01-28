@extends('layout.main')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-stone-100">
        
        {{-- LEFT SIDE: HERO IMAGE --}}
        <div class="hidden md:block md:w-1/2 relative">
            <img src="{{ asset('images/hero.jpg') }}" 
                 alt="Thrift Hero" 
                 class="w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-maroon/40 backdrop-blur-[2px] flex items-center justify-center p-12 text-center">
                <div class="text-white max-w-md">
                    <i data-lucide="quote" class="w-12 h-12 mb-6 opacity-60 mx-auto"></i>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold leading-tight mb-6">
                        "Fashion is what you buy, style is what you do with it."
                    </h2>
                    <div class="flex items-center justify-center gap-4 text-stone-300 font-medium tracking-widest uppercase text-xs">
                        <span class="h-px w-8 bg-stone-400"></span>
                        LockerByBrokeAngel
                        <span class="h-px w-8 bg-stone-400"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: LOGIN FORM --}}
        <div class="w-full md:w-1/2 p-8 md:p-20 flex flex-col justify-center bg-white">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-4xl font-serif font-bold text-stone-900 mb-2">Sign In</h1>
                <p class="text-stone-500">Enter your credentials to access your virtual locker.</p>
            </div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                {{-- USERNAME OR EMAIL INPUT --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Email or Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </span>
                        <input type="text" name="login" required 
                               placeholder="Enter email or username"
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
                    <button type="submit" class="w-full bg-maroon text-white py-4 rounded-xl font-bold text-lg hover:bg-red-900 transition shadow-lg shadow-maroon/20 flex items-center justify-center gap-2 group">
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

{{-- MODERN TOAST NOTIFICATION --}}
<div id="auth-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 transition-all duration-500 opacity-0 translate-y-20 pointer-events-none">
    <div class="bg-stone-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10">
        <div id="toast-icon-bg" class="w-8 h-8 rounded-full flex items-center justify-center text-white">
            <i id="toast-icon" data-lucide="alert-circle" class="w-4 h-4"></i>
        </div>
        <div>
            <p id="toast-title" class="text-[10px] font-black uppercase tracking-widest text-red-400">Login Error</p>
            <p id="toast-message" class="text-xs text-stone-300"></p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('auth-toast');
        const toastMsg = document.getElementById('toast-message');

        function showToast(message, type = 'error') {
            toastMsg.innerText = message;
            const iconBg = document.getElementById('toast-icon-bg');
            const title = document.getElementById('toast-title');
            
            if(type === 'success') {
                iconBg.classList.add('bg-green-500');
                title.classList.replace('text-red-400', 'text-green-400');
                title.innerText = 'Success';
            } else {
                iconBg.classList.add('bg-red-500');
                title.innerText = 'Login Error';
            }

            toast.classList.remove('opacity-0', 'translate-y-20');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-20');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 4000);
        }

        {{-- Trigger Toast if there is a session error or validation errors --}}
        @if(session('error'))
            showToast("{{ session('error') }}");
        @endif

        @if($errors->any())
            showToast("{{ $errors->first() }}");
        @endif

        lucide.createIcons();
    });
</script>

<style>
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
</style>
@endsection
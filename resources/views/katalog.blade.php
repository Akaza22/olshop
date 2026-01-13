@extends('layout.main')

@section('content')

{{-- HERO SECTION --}}
<div class="relative rounded-[2rem] overflow-hidden mb-16 shadow-2xl">
    <div class="absolute inset-0 bg-maroon">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    </div>
    
    <div class="relative grid grid-cols-1 md:grid-cols-2 items-center">
        <div class="p-10 md:p-16 text-white">
            <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                New Experience
            </span>
            <h1 class="text-5xl md:text-6xl font-serif font-bold leading-tight mb-4">
                Try Before <br> You <span class="text-stone-300 italic">Thrift.</span>
            </h1>
            <p class="text-stone-200 text-lg mb-8 max-w-md font-light leading-relaxed">
                Revolutionizing vintage shopping. Experience trying on clothes virtually right from your device.
            </p>
            <div class="flex gap-4">
                <a href="#koleksi" class="bg-white text-maroon px-8 py-3 rounded-full font-bold hover:bg-stone-100 transition shadow-lg">
                    Expore Collection
                </a>
            </div>
        </div>
        <div class="hidden md:block h-full">
            <img src="{{ asset('images/hero.jpg') }}" 
     class="w-full h-full object-cover opacity-80" alt="Hero Image">
        </div>
    </div>
</div>

{{-- SECTION TITLE & FILTER --}}
<div id="koleksi" class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
    <div>
        <h2 class="text-3xl font-serif font-bold text-stone-900">Selected Collection</h2>
        <p class="text-stone-500">Showing {{ $products->count() }} best products for you</p>
    </div>
    <div class="flex gap-2">
        <button class="px-4 py-2 bg-white border border-stone-200 rounded-lg text-sm font-medium hover:border-maroon transition flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
        <button class="px-4 py-2 bg-white border border-stone-200 rounded-lg text-sm font-medium hover:border-maroon transition flex items-center gap-2">
            Newest <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </button>
    </div>
</div>

{{-- PRODUCT GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($products as $p)
    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-stone-100 flex flex-col">
        
        {{-- Image Container --}}
        <div class="relative overflow-hidden aspect-[3/4]">
            <img src="/images/products/{{ $p->image }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            
            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <a href="/produk/{{ $p->id }}" class="bg-white text-maroon p-3 rounded-full shadow-xl translate-y-10 group-hover:translate-y-0 transition-transform duration-500">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                </a>
            </div>

            {{-- Badge --}}
            <span class="absolute top-4 left-4 bg-maroon text-white text-[10px] font-bold px-3 py-1 rounded-full tracking-widest uppercase">
                Vintage
            </span>
        </div>

        {{-- Product Info --}}
        <div class="p-5 flex-grow flex flex-col">
            <h3 class="font-serif text-xl font-bold text-stone-800 mb-1 group-hover:text-maroon transition">
                {{ $p->name }}
            </h3>
            <p class="text-brown font-semibold text-lg mb-4">
                Rp {{ number_format($p->price, 0, ',', '.') }}
            </p>

            <a href="/produk/{{ $p->id }}" 
               class="mt-auto w-full flex items-center justify-center gap-2 bg-stone-100 text-stone-800 py-3 rounded-xl font-bold group-hover:bg-maroon group-hover:text-white transition-colors duration-300">
                <i data-lucide="camera" class="w-4 h-4"></i>
                Try Now
            </a>
        </div>
    </div>
    @endforeach
</div>

@endsection
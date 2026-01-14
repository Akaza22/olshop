@forelse($products as $p)
<div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-stone-100 flex flex-col">
    <div class="relative overflow-hidden aspect-[3/4]">
        <img src="/images/products/{{ $p->image }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <a href="/produk/{{ $p->id }}" class="bg-white text-maroon p-3 rounded-full shadow-xl translate-y-10 group-hover:translate-y-0 transition-transform duration-500">
                <i data-lucide="eye" class="w-6 h-6"></i>
            </a>
        </div>
        @php
            // Logika penentuan warna berdasarkan kategori
            $badgeColor = match($p->category) {
                'Outerwear' => 'bg-blue-600',
                'Tops'      => 'bg-green-600',
                'Bottoms'   => 'bg-stone-600',
                default     => 'bg-maroon', // Warna default untuk 'Vintage' atau kategori lain
            };
        @endphp

        <span class="absolute top-4 left-4 {{ $badgeColor }} text-white text-[10px] font-bold px-3 py-1 rounded-full tracking-widest uppercase">
            {{ $p->category }}
        </span>
    </div>
    <div class="p-5 flex-grow flex flex-col">
        <h3 class="font-serif text-xl font-bold text-stone-800 mb-1 group-hover:text-maroon transition">{{ $p->name }}</h3>
        <p class="text-brown font-semibold text-lg mb-4">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
        <a href="/produk/{{ $p->id }}" class="mt-auto w-full flex items-center justify-center gap-2 bg-stone-100 text-stone-800 py-3 rounded-xl font-bold group-hover:bg-maroon group-hover:text-white transition-colors duration-300">
            <i data-lucide="camera" class="w-4 h-4"></i> Try Now
        </a>
    </div>
</div>
@empty
<div class="col-span-full py-20 text-center">
    <i data-lucide="search-x" class="w-16 h-16 text-stone-300 mx-auto mb-4"></i>
    <h3 class="text-xl font-serif font-bold text-stone-800">No products found</h3>
    <p class="text-stone-500">Try different keywords.</p>
</div>
@endforelse
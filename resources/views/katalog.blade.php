@extends('layout.main')

@section('content')

{{-- HERO SECTION --}}
<div class="relative rounded-[2rem] overflow-hidden mb-16 shadow-2xl">
    <div class="absolute inset-0 bg-maroon">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
    </div>
    
    <div class="relative grid grid-cols-1 md:grid-cols-2 items-center">
        <div class="p-10 md:p-16 text-white">
            <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold tracking-widest uppercase mb-4">New Experience</span>
            <h1 class="text-5xl md:text-6xl font-serif font-bold leading-tight mb-4">Try Before <br> You <span class="text-stone-300 italic">Thrift.</span></h1>
            <p class="text-stone-200 text-lg mb-8 max-w-md font-light leading-relaxed">Revolutionizing vintage shopping with Virtual Try-On technology.</p>
            <div class="flex gap-4">
                <a href="#koleksi" class="bg-white text-maroon px-8 py-3 rounded-full font-bold hover:bg-stone-100 transition shadow-lg">Explore Collection</a>
            </div>
        </div>
        <div class="hidden md:block h-full">
            <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover opacity-80" alt="Hero Image">
        </div>
    </div>
</div>

{{-- MAIN LAYOUT: SIDEBAR + CONTENT --}}
<div id="koleksi" class="flex flex-col lg:flex-row gap-10 pt-10">
    
    {{-- SIDEBAR FILTER --}}
    <aside class="w-full lg:w-1/4 space-y-8">
        <div class="bg-white p-6 rounded-2xl border border-stone-100 shadow-sm sticky top-28">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-stone-900 flex items-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 text-maroon"></i> Filters
                </h3>
                <button id="clear-filters" class="text-[10px] font-bold text-stone-400 hover:text-maroon transition uppercase">Reset</button>
            </div>

            {{-- Filter Kategori --}}
            <div class="mb-8">
                <p class="text-[10px] font-black uppercase tracking-widest text-maroon mb-4">Category</p>
                <div class="space-y-3">
                    @foreach(['Outerwear', 'Tops', 'Bottoms', 'Vintage'] as $cat)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="category" value="{{ $cat }}" class="filter-input w-4 h-4 rounded border-stone-300 text-maroon focus:ring-maroon accent-maroon">
                        <span class="text-sm text-stone-600 group-hover:text-maroon transition">{{ $cat }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Filter Harga --}}
            <div class="mb-2">
                <p class="text-[10px] font-black uppercase tracking-widest text-maroon mb-4">Price Range</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" placeholder="Min" class="filter-input w-full bg-stone-50 border-stone-200 rounded-lg text-xs p-2 focus:ring-1 focus:ring-maroon outline-none">
                        <input type="number" name="max_price" placeholder="Max" class="filter-input w-full bg-stone-50 border-stone-200 rounded-lg text-xs p-2 focus:ring-1 focus:ring-maroon outline-none">
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- CONTENT AREA --}}
    <div class="w-full lg:w-3/4">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-serif font-bold text-stone-900" id="search-title">Selected Collection</h2>
                <p class="text-stone-500 text-sm" id="search-count">Showing {{ $products->count() }} best products for you</p>
            </div>
        </div>

        {{-- PRODUCT GRID CONTAINER --}}
        <div id="product-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[400px] transition-opacity duration-300">
            @include('partials.product-grid')
        </div>
    </div>
</div>

{{-- SCRIPT: LIVE SEARCH & FILTER --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="query"]');
        const filterInputs = document.querySelectorAll('.filter-input');
        const container = document.getElementById('product-container');
        const title = document.getElementById('search-title');
        const countText = document.getElementById('search-count');
        const clearBtn = document.getElementById('clear-filters');
        
        let timeout = null;

        function updateProducts() {
            clearTimeout(timeout);
            
            // Debounce agar tidak terlalu sering menembak server
            timeout = setTimeout(() => {
                container.style.opacity = '0.5';

                // Ambil semua parameter filter
                let params = new URLSearchParams();
                
                if (searchInput.value) params.append('query', searchInput.value);
                
                document.querySelectorAll('input[name="category"]:checked').forEach(el => {
                    params.append('category', el.value);
                });

                const minPrice = document.querySelector('input[name="min_price"]').value;
                const maxPrice = document.querySelector('input[name="max_price"]').value;
                if (minPrice) params.append('min_price', minPrice);
                if (maxPrice) params.append('max_price', maxPrice);

                // Update URL tanpa reload agar profesional
                const newUrl = `${window.location.pathname}?${params.toString()}`;
                window.history.replaceState({}, '', newUrl);

                // Fetch data produk
                fetch(`{{ route('product.search') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';
                    countText.innerText = `Found ${data.count} items matching your criteria`;
                    
                    if (params.get('query')) {
                        title.innerText = `Results for "${params.get('query')}"`;
                    } else {
                        title.innerText = "Selected Collection";
                    }

                    lucide.createIcons(); // Refresh icons Lucide
                });
            }, 400);
        }

        // Event Listeners
        searchInput.addEventListener('input', () => {
            updateProducts();
            document.getElementById('koleksi').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        filterInputs.forEach(input => {
            input.addEventListener('input', updateProducts);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            filterInputs.forEach(input => {
                if (input.type === 'checkbox') input.checked = false;
                else input.value = '';
            });
            updateProducts();
        });
    });
</script>

@endsection
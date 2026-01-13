@extends('layout.main')

@section('content')
<nav class="flex mb-8 text-sm text-stone-500 px-4">
    <a href="/katalog" class="hover:text-maroon transition">Catalog</a>
    <span class="mx-2">/</span>
    <span class="text-stone-800 font-semibold">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start px-4">
    <div class="space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100 overflow-hidden group">
            <img src="/images/products/{{ $product->image }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-auto object-cover rounded-lg group-hover:scale-105 transition duration-500">
        </div>
    </div>

    <div class="flex flex-col">
        <span class="text-maroon font-bold tracking-widest text-sm uppercase mb-2">Vintage Collection</span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-stone-900 mb-4 tracking-tight">
            {{ $product->name }}
        </h1>
        
        <div class="flex items-center mb-6">
            <p class="text-3xl font-semibold text-brown">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            
            @php $totalStock = $product->sizes->sum('stock'); @endphp
            
            @if($totalStock > 0)
                <span class="ml-4 px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded uppercase">Available</span>
            @else
                <span class="ml-4 px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded uppercase">Sold Out</span>
            @endif
        </div>

        <div class="prose prose-stone mb-8">
            <p class="text-stone-600 leading-relaxed italic border-l-4 border-maroon/20 pl-4">
                "{{ $product->description }}"
            </p>
        </div>

        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-10">
                <p class="text-xs text-stone-400 uppercase font-bold mb-4 tracking-widest">Choose Available Size</p>
                <div class="flex flex-wrap gap-3">
                    @forelse($product->sizes as $s)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="size_id" value="{{ $s->id }}" 
                                   data-stock="{{ $s->stock }}"
                                   class="hidden peer size-option" 
                                   {{ $s->stock <= 0 ? 'disabled' : '' }} required>
                            
                            <div class="w-20 h-20 flex flex-col items-center justify-center border-2 rounded-xl transition-all duration-300
                                @if($s->stock > 0)
                                    bg-white border-stone-200 text-stone-700 hover:border-maroon/50
                                    peer-checked:bg-maroon peer-checked:border-maroon peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-maroon/30
                                @else
                                    bg-stone-50 border-stone-100 text-stone-300 cursor-not-allowed
                                @endif
                            ">
                                <span class="text-lg font-bold">{{ $s->size }}</span>
                                <span class="text-[10px] {{ $s->stock > 0 ? 'text-stone-400 peer-checked:text-white/80' : 'text-stone-300' }}">
                                    Stok: {{ $s->stock }}
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-stone-400 italic">Size not available.</p>
                    @endforelse
                </div>
            </div>

            <div class="mb-8 p-4 bg-stone-50 rounded-2xl border border-stone-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-stone-800 uppercase tracking-widest">Quantity</p>
                    <p class="text-[10px] text-stone-400">Available: <span id="stock-display">-</span></p>
                </div>
                <div class="flex items-center gap-4 bg-white rounded-xl border border-stone-200 p-1">
                    <button type="button" id="btn-minus" class="w-8 h-8 flex items-center justify-center text-stone-400 hover:text-maroon font-bold transition">-</button>
                    <input type="number" name="quantity" id="quantity-input" value="1" min="1" readonly
                           class="w-12 text-center font-bold text-stone-800 border-none focus:ring-0 p-0 bg-transparent">
                    <button type="button" id="btn-plus" class="w-8 h-8 flex items-center justify-center text-stone-400 hover:text-maroon font-bold transition">+</button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mb-10 border-t border-stone-100 pt-8">
                <button type="submit" id="add-to-cart-btn" disabled 
                        class="flex-1 bg-maroon text-white px-8 py-4 rounded-xl font-bold hover:bg-red-900 transition shadow-lg shadow-red-900/20 flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    Add to Cart
                </button>
                <button type="button" class="p-4 border border-stone-200 rounded-xl hover:bg-stone-50 transition">
                    <i data-lucide="heart" class="w-6 h-6 text-stone-600"></i>
                </button>
            </div>
        </form>

        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-stone-200">
            <div>
                <p class="text-xs text-stone-400 uppercase font-bold tracking-widest mb-1">Condition</p>
                <p class="text-sm font-semibold text-stone-700">{{ $product->condition ?? 'Good Condition' }}</p>
            </div>
            <div>
                <p class="text-xs text-stone-400 uppercase font-bold tracking-widest mb-1">Product ID</p>
                <p class="text-sm font-semibold text-stone-700">#TVTO-{{ $product->id }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-20 px-4">
    <div class="bg-stone-900 rounded-[3rem] p-8 md:p-16 overflow-hidden shadow-2xl relative">
        <div class="absolute top-0 right-0 w-80 h-80 bg-maroon/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-5 gap-16 items-center">
            <div class="lg:col-span-2 text-white">
                <h2 class="text-4xl font-serif font-bold mb-6 tracking-tight">AI Dressing Room</h2>
                <p class="text-stone-400 mb-10 leading-relaxed">Rasakan pengalaman teknologi <span class="text-maroon font-bold italic">Virtual Try-On</span> tercanggih. AI kami akan memadukan koleksi ini langsung ke foto Anda.</p>
                
                <div class="space-y-8">
                    <div class="flex flex-col gap-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-maroon">Langkah 1: Unggah Foto Anda</label>
                        <label for="userUpload" class="cursor-pointer flex items-center justify-center gap-4 bg-white/5 border border-white/10 p-5 rounded-2xl hover:bg-white/10 transition group border-dashed">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-maroon"></i>
                            <span class="text-sm font-bold">Pilih Foto (Full Body)</span>
                            <input type="file" id="userUpload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-maroon">Langkah 2: Jalankan AI Magic</label>
                        <button id="startAiBtn" disabled 
                                class="w-full bg-maroon text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-900 transition-all shadow-xl shadow-maroon/20 disabled:opacity-30 disabled:cursor-not-allowed flex items-center justify-center gap-3">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                            Mulai Proses AI
                        </button>
                    </div>

                    <div class="pt-6 flex flex-wrap gap-4 border-t border-white/10">
                        <button id="resetTryOn" class="flex-1 text-stone-400 hover:text-white transition text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
                        </button>
                        <button id="downloadTryOn" class="flex-1 text-stone-400 hover:text-white transition text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i> Simpan Hasil
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 flex justify-center relative">
                <div class="relative bg-white p-3 rounded-[2.5rem] shadow-inner overflow-hidden border-[12px] border-stone-800 shadow-2xl w-full max-w-[450px]">
                    
                    <div id="vto-loading" class="hidden absolute inset-0 z-30 bg-stone-900/90 backdrop-blur-md flex flex-col items-center justify-center p-8 text-center">
                        <div class="relative w-24 h-24 mb-8">
                            <div class="absolute inset-0 border-4 border-maroon/20 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-t-maroon rounded-full animate-spin"></div>
                            <i data-lucide="scissors" class="w-8 h-8 text-maroon absolute inset-0 m-auto animate-bounce"></i>
                        </div>
                        <h3 class="text-white text-xl font-serif font-bold mb-2">AI Sedang Menjahit...</h3>
                        <p class="text-stone-400 text-[10px] uppercase tracking-widest leading-relaxed">AI sedang menyesuaikan lipatan kain dan pencahayaan baju ke tubuh Anda.</p>
                    </div>

                    <div id="canvas-container">
                        <canvas id="tryOnCanvas" width="450" height="600" class="max-w-full h-auto"></canvas>
                    </div>
                    
                    <img id="aiResultImg" class="hidden absolute inset-0 w-full h-full object-cover z-20">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LOGIC QUANTITY & STOCK (Tetap sama) ---
        const sizeOptions = document.querySelectorAll('.size-option');
        const qtyInput = document.getElementById('quantity-input');
        const stockDisplay = document.getElementById('stock-display');
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const addBtn = document.getElementById('add-to-cart-btn');

        sizeOptions.forEach(radio => {
            radio.addEventListener('change', function() {
                const stock = parseInt(this.getAttribute('data-stock'));
                qtyInput.value = 1;
                qtyInput.max = stock;
                stockDisplay.innerText = stock;
                addBtn.disabled = false;
            });
        });

        // --- 2. LOGIC VIRTUAL TRY-ON (Fabric.js & AI) ---
        const canvas = new fabric.Canvas('tryOnCanvas');
        let productImg;
        let userFile = null;

        function setCanvasBackground(url) {
            fabric.Image.fromURL(url, function(img) {
                const scale = Math.max(canvas.width / img.width, canvas.height / img.height);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: scale, scaleY: scale, originX: 'left', originY: 'top', left: 0, top: 0
                });
            });
        }

        setCanvasBackground('/images/models/model.jpg');

        document.getElementById('userUpload').addEventListener('change', function(e) {
            userFile = e.target.files[0];
            if (!userFile) return;

            const reader = new FileReader();
            reader.onload = function(f) {
                setCanvasBackground(f.target.result);
                document.getElementById('startAiBtn').disabled = false;
                document.getElementById('aiResultImg').classList.add('hidden');
            };
            reader.readAsDataURL(userFile);
        });

        fabric.Image.fromURL('/images/products/{{ $product->tryon_image }}', function(img) {
            productImg = img;
            img.set({
                left: 120, top: 180, scaleX: 0.45, scaleY: 0.45,
                cornerColor: '#800000', transparentCorners: false, cornerStyle: 'circle'
            });
            canvas.add(img);
        });

        // --- AI LOGIC FIXED FOR FAL.AI ---
        document.getElementById('startAiBtn').addEventListener('click', async function() {
            const loading = document.getElementById('vto-loading');
            loading.classList.remove('hidden');

            try {
                const formData = new FormData();
                formData.append('user_image', userFile);
                formData.append('product_id', {{ $product->id }});
                formData.append('_token', '{{ csrf_token() }}');

                console.log("Memulai request ke AI..."); // Debugging

                const response = await fetch("{{ route('vto.generate') }}", {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error("Server Error:", errorText);
                    throw new Error("Server mengembalikan error 500/404");
                }

                const result = await response.json();
                console.log("Respon dari AI:", result);

                if (response.ok && result.image && result.image.url) {
                    document.getElementById('aiResultImg').src = result.image.url;
                    document.getElementById('aiResultImg').classList.remove('hidden');
                    document.getElementById('vto-loading').classList.add('hidden');
                } else {
                    // Tampilkan pesan error detail jika field masih salah
                    console.error("Detail Error:", result);
                    alert("AI Gagal: Cek Console Browser");
                    document.getElementById('vto-loading').classList.add('hidden');
                }

            } catch (error) {
                console.error("Fetch Error:", error);
                alert("Koneksi terputus atau timeout. Pastikan Ngrok masih jalan.");
                loading.classList.add('hidden');
            }
        }); 

        document.getElementById('resetTryOn').addEventListener('click', () => location.reload());
        lucide.createIcons();
    });
</script>
@endsection
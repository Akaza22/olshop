@extends('layout.main')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

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
                <p class="text-xs text-stone-400 uppercase font-bold mb-4 tracking-widest">Select Size</p>
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
                                 @endif">
                                <span class="text-lg font-bold">{{ $s->size }}</span>
                                <span class="text-[10px] {{ $s->stock > 0 ? 'text-stone-400 peer-checked:text-white/80' : 'text-stone-300' }}">
                                    Stock: {{ $s->stock }}
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-stone-400 italic">No sizes available.</p>
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
                        class="flex-1 bg-maroon text-white px-8 py-4 rounded-xl font-bold hover:bg-red-900 transition shadow-lg flex justify-center items-center gap-2 disabled:opacity-50">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    Add to Cart
                </button>
            </div>
        </form>
    </div>
</div>

{{-- VIRTUAL TRY-ON SECTION --}}
<div class="mt-20 px-4 mb-20">
    <div class="bg-stone-900 rounded-[3rem] p-8 md:p-12 overflow-hidden shadow-2xl relative">
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-4 text-white space-y-8">
                <div>
                    <h2 class="text-3xl font-serif font-bold mb-4">Virtual Fitting Room</h2>
                    <p class="text-stone-400 text-sm leading-relaxed">
                        Mix and match this item with your own photo. Drag, scale, and rotate the garment to check the perfect fit.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-maroon">1. Upload Your Photo</label>
                        <label for="userUpload" class="cursor-pointer flex items-center justify-center gap-4 bg-white/5 border border-white/10 p-4 rounded-xl hover:bg-white/10 transition border-dashed">
                            <i data-lucide="camera" class="w-5 h-5 text-maroon"></i>
                            <span class="text-xs font-bold uppercase" id="upload-label">Select Full Body Photo</span>
                            <input type="file" id="userUpload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    <div class="space-y-4 bg-white/5 p-6 rounded-2xl border border-white/10">
                        <label class="text-[10px] font-black uppercase tracking-widest text-maroon">2. Garment Controls</label>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-[10px] text-stone-400 uppercase font-bold">
                                <span>Opacity</span>
                                <span id="opacityVal">100%</span>
                            </div>
                            <input type="range" id="opacitySlider" min="0" max="1" step="0.01" value="1" class="w-full accent-maroon">
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button id="flipBtn" class="p-3 bg-white/10 rounded-lg hover:bg-white/20 transition text-[10px] font-bold flex items-center justify-center gap-2">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i> MIRROR
                            </button>
                            <button id="toFrontBtn" class="p-3 bg-white/10 rounded-lg hover:bg-white/20 transition text-[10px] font-bold flex items-center justify-center gap-2">
                                <i data-lucide="layers" class="w-3 h-3"></i> FRONT
                            </button>
                        </div>
                    </div>

                    <div class="pt-6 flex gap-4 border-t border-white/10">
                        <button id="resetBtn" class="text-stone-400 hover:text-white transition text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
                        </button>
                        <button id="downloadBtn" class="bg-white text-stone-900 px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-maroon hover:text-white transition ml-auto">
                            Download Preview
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 flex justify-center">
                {{-- PERBAIKAN: p-0 dan bg-stone-800 untuk menghilangkan bingkai putih --}}
                <div class="bg-stone-800 p-0 rounded-[2.5rem] shadow-2xl overflow-hidden border-[10px] border-stone-800">
                    <canvas id="fittingCanvas" width="450" height="600" class="max-w-full h-auto"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. QUANTITY & STOCK LOGIC ---
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

        btnPlus.addEventListener('click', () => {
            if (parseInt(qtyInput.value) < parseInt(qtyInput.max)) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
            }
        });

        btnMinus.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });

        // --- 2. FABRIC.JS LOGIC ---
        const canvas = new fabric.Canvas('fittingCanvas', {
            preserveObjectStacking: true,
            backgroundColor: '#1c1917' // Mencocokkan dengan warna bg-stone-900
        });

        let garmentObj = null;

        // PERBAIKAN: Fungsi background dengan logika "Cover" agar Full Screen
        function setCanvasBackground(url) {
            fabric.Image.fromURL(url, function(img) {
                // Hitung skala terbesar untuk menutupi seluruh area (Cover)
                const scale = Math.max(canvas.width / img.width, canvas.height / img.height);
                
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: scale,
                    scaleY: scale,
                    originX: 'center',
                    originY: 'center',
                    left: canvas.width / 2,
                    top: canvas.height / 2
                });
            });
        }

        // Set Default Model
        setCanvasBackground('/images/models/model.jpg');

        // Handle User Photo Upload
        document.getElementById('userUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(f) {
                setCanvasBackground(f.target.result);
                document.getElementById('upload-label').innerText = "Photo Updated";
            };
            reader.readAsDataURL(file);
        });

        // Load Product Garment (PNG)
        fabric.Image.fromURL('/images/products/{{ $product->tryon_image }}', function(img) {
            img.set({
                left: canvas.width / 2,
                top: canvas.height / 3, // Sedikit ke atas agar pas di badan model
                originX: 'center',
                originY: 'center',
                scaleX: 0.5,
                scaleY: 0.5,
                cornerColor: '#800000',
                transparentCorners: false,
                cornerStyle: 'circle',
                borderColor: '#800000',
                cornerSize: 12
            });
            garmentObj = img;
            canvas.add(img);
            canvas.setActiveObject(img);
        });

        // Controls
        document.getElementById('opacitySlider').addEventListener('input', function() {
            if (garmentObj) {
                garmentObj.set('opacity', parseFloat(this.value));
                document.getElementById('opacityVal').innerText = Math.round(this.value * 100) + '%';
                canvas.renderAll();
            }
        });

        document.getElementById('flipBtn').addEventListener('click', () => {
            if (garmentObj) {
                garmentObj.set('flipX', !garmentObj.flipX);
                canvas.renderAll();
            }
        });

        document.getElementById('toFrontBtn').addEventListener('click', () => {
            if (garmentObj) {
                garmentObj.bringToFront();
                canvas.renderAll();
            }
        });

        document.getElementById('downloadBtn').addEventListener('click', function() {
            const dataURL = canvas.toDataURL({
                format: 'png',
                quality: 1
            });
            const link = document.createElement('a');
            link.download = 'fitting-preview-{{ $product->id }}.png';
            link.href = dataURL;
            link.click();
        });

        document.getElementById('resetBtn').addEventListener('click', () => {
            location.reload();
        });

        lucide.createIcons();
    });
</script>

<style>
    /* Styling agar canvas responsif di HP */
    .canvas-container {
        max-width: 100% !important;
        margin: 0 auto;
    }

    /* Premium Range Slider */
    input[type=range] { -webkit-appearance: none; background: rgba(255,255,255,0.1); height: 4px; border-radius: 2px; }
    input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; height: 16px; width: 16px; border-radius: 50%; background: #800000; cursor: pointer; border: 2px solid white; }
    
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
    .text-brown { color: #5C4033; }
</style>
@endsection
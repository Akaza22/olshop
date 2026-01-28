@extends('layout.main')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

{{-- BREADCRUMB NAVIGATION --}}
<nav class="flex mb-8 text-sm text-stone-500 px-4">
    <a href="/katalog" class="hover:text-maroon transition">Catalog</a>
    <span class="mx-2">/</span>
    <span class="text-stone-800 font-semibold">{{ $product->name }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start px-4">
    {{-- PRODUCT IMAGE --}}
    <div class="space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100 overflow-hidden group">
            <img src="/images/products/{{ $product->image }}" 
                 alt="{{ $product->name }}"
                 class="w-full h-auto object-cover rounded-lg group-hover:scale-105 transition duration-500">
        </div>
    </div>

    {{-- PRODUCT INFO --}}
    <div class="flex flex-col">
        <span class="text-maroon font-bold tracking-widest text-sm uppercase mb-2">{{ $product->category }} Collection</span>
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

        {{-- FORM ADD TO CART AJAX --}}
        <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- SELECT SIZE --}}
            <div class="mb-10">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-6">Select Size</p>
                <div class="flex flex-wrap gap-4">
                    @php $allSizes = ['S', 'M', 'L', 'XL']; @endphp

                    @foreach($allSizes as $sizeName)
                        @php
                            $s = $product->sizes->where('size', $sizeName)->first();
                            $stock = $s ? $s->stock : 0;
                            $sizeId = $s ? $s->id : null;
                        @endphp

                        <label class="relative group {{ $stock <= 0 ? 'sold-out-trigger cursor-pointer' : '' }}" data-size="{{ $sizeName }}">
                            <input type="radio" name="size_id" value="{{ $sizeId }}" 
                                data-stock="{{ $stock }}"
                                class="hidden peer size-option" 
                                {{ $stock <= 0 ? 'disabled' : '' }} required>
                            
                            <div class="relative w-20 h-24 flex flex-col items-center justify-center rounded-2xl border transition-all duration-500
                                @if($stock > 0)
                                    bg-white border-stone-200 text-stone-800 hover:border-maroon hover:shadow-xl hover:shadow-maroon/5 cursor-pointer
                                    peer-checked:bg-maroon peer-checked:border-maroon peer-checked:text-white peer-checked:shadow-2xl peer-checked:shadow-maroon/20
                                @else
                                    bg-stone-50/50 border-stone-200 border-dashed text-stone-300 grayscale
                                @endif">
                                
                                <span class="text-xl font-serif font-bold mb-1">{{ $sizeName }}</span>
                                <span class="text-[9px] font-bold uppercase tracking-tighter">
                                    {{ $stock > 0 ? 'Stock: '.$stock : 'OUT' }}
                                </span>
                            </div>
                            
                            @if($stock <= 0)
                                <div class="absolute inset-0 bg-white/5 backdrop-blur-[1px] rounded-2xl"></div>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- QUANTITY SELECTOR --}}
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
                    <span id="btn-text">Add to Cart</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- VIRTUAL TRY-ON SECTION (RESTORED) --}}
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
                    {{-- 1. UPLOAD --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-maroon">1. Upload Your Photo</label>
                        <label for="userUpload" class="cursor-pointer flex items-center justify-center gap-4 bg-white/5 border border-white/10 p-4 rounded-xl hover:bg-white/10 transition border-dashed">
                            <i data-lucide="camera" class="w-5 h-5 text-maroon"></i>
                            <span class="text-xs font-bold uppercase" id="upload-label">Select Full Body Photo</span>
                            <input type="file" id="userUpload" class="hidden" accept="image/*">
                        </label>
                    </div>

                    {{-- 2. CONTROLS (RESTORED) --}}
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

                    {{-- 3. ACTIONS --}}
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
                <div class="bg-stone-800 p-0 rounded-[2.5rem] shadow-2xl overflow-hidden border-[10px] border-stone-800">
                    <canvas id="fittingCanvas" width="450" height="600" class="max-w-full h-auto"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TOASTS --}}
<div id="restock-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[110] translate-y-20 opacity-0 transition-all duration-500 pointer-events-none">
    <div class="bg-stone-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10">
        <div class="w-8 h-8 bg-maroon rounded-full flex items-center justify-center"><i data-lucide="bell" class="w-4 h-4 text-white"></i></div>
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-maroon">Request Logged</p>
            <p class="text-xs text-stone-300">Size <span id="toast-size" class="font-bold text-white"></span> is being processed for restock.</p>
        </div>
    </div>
</div>

<div id="success-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[110] translate-y-20 opacity-0 transition-all duration-500 pointer-events-none">
    <div class="bg-white text-stone-900 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-stone-100">
        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-4 h-4 text-white"></i></div>
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Added to Bag</p>
            <p class="text-xs text-stone-500">{{ $product->name }} added successfully!</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- A. QUANTITY & STOCK LOGIC ---
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

        // --- B. AJAX ADD TO CART ---
        const cartForm = document.getElementById('add-to-cart-form');
        const successToast = document.getElementById('success-toast');

        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btnText = document.getElementById('btn-text');
            addBtn.disabled = true;
            btnText.innerText = "Adding...";

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    if (typeof showGlobalToast === 'function') showGlobalToast(data.message, 'error');
                    throw new Error(data.message);
                }
                return data;
            })
            .then(data => {
                if(data.success) {
                    const badge = document.getElementById('cart-badge');
                    if(badge) { badge.innerText = data.cart_count; badge.classList.remove('hidden'); }
                    successToast.classList.remove('translate-y-20', 'opacity-0');
                    successToast.classList.add('translate-y-0', 'opacity-100');
                    setTimeout(() => {
                        successToast.classList.add('translate-y-20', 'opacity-0');
                        successToast.classList.remove('translate-y-0', 'opacity-100');
                    }, 3000);
                }
            })
            .catch(error => console.error(error))
            .finally(() => {
                btnText.innerText = "Add to Cart";
                addBtn.disabled = false;
            });
        });

        // --- C. RESTOCK ALERT ---
        const soldOutTriggers = document.querySelectorAll('.sold-out-trigger');
        const restockToast = document.getElementById('restock-toast');
        const toastSize = document.getElementById('toast-size');

        soldOutTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const size = this.getAttribute('data-size');
                toastSize.innerText = size;
                restockToast.classList.remove('translate-y-20', 'opacity-0');
                restockToast.classList.add('translate-y-0', 'opacity-100');
                setTimeout(() => {
                    restockToast.classList.add('translate-y-20', 'opacity-0');
                    restockToast.classList.remove('translate-y-0', 'opacity-100');
                }, 3000);
            });
        });

        // --- D. VIRTUAL FITTING (FABRIC.JS) (FIXED & RESTORED) ---
        const canvas = new fabric.Canvas('fittingCanvas', { preserveObjectStacking: true, backgroundColor: '#1c1917' });
        let garmentObj = null;

        function setCanvasBackground(url) {
            fabric.Image.fromURL(url, function(img) {
                const scale = Math.max(canvas.width / img.width, canvas.height / img.height);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: scale, scaleY: scale, originX: 'center', originY: 'center',
                    left: canvas.width / 2, top: canvas.height / 2
                });
            });
        }

        setCanvasBackground('/images/models/model.jpg');

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

        fabric.Image.fromURL('/images/products/{{ $product->tryon_image }}', function(img) {
            img.set({ 
                left: canvas.width / 2, top: canvas.height / 2.5, 
                originX: 'center', originY: 'center', scaleX: 0.6, scaleY: 0.6,
                cornerColor: '#800000', transparentCorners: false, cornerStyle: 'circle',
                borderColor: '#800000', cornerSize: 12
            });
            garmentObj = img;
            canvas.add(img);
            canvas.setActiveObject(img);
        });

        // RESTORED JS HANDLERS
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
            const dataURL = canvas.toDataURL({ format: 'png', quality: 1 });
            const link = document.createElement('a');
            link.download = 'fitting-preview-{{ $product->id }}.png';
            link.href = dataURL;
            link.click();
        });

        document.getElementById('resetBtn').addEventListener('click', () => location.reload());

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>

<style>
    .canvas-container { max-width: 100% !important; margin: 0 auto; }
    input[type=range] { -webkit-appearance: none; background: rgba(255,255,255,0.1); height: 4px; border-radius: 2px; }
    input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; height: 16px; width: 16px; border-radius: 50%; background: #800000; cursor: pointer; border: 2px solid white; }
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
    .text-brown { color: #5C4033; }
    .size-option + div { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    label:not(.sold-out-trigger) div:hover { transform: translateY(-4px); }
</style>
@endsection
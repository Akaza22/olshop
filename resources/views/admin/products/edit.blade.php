@extends('layout.main')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    
    <div class="flex items-center gap-5 mb-10">
        <a href="{{ route('admin.products.index') }}" 
           class="group flex items-center justify-center w-12 h-12 bg-white border border-stone-200 rounded-2xl shadow-sm hover:bg-stone-50 transition-all duration-300">
            <i data-lucide="chevron-left" class="w-6 h-6 text-stone-600 group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-3xl font-serif font-bold text-stone-900 tracking-tight">Edit Collection</h1>
            <p class="text-sm text-stone-500">Updating product details: <span class="text-stone-800 font-bold">{{ $product->name }}</span></p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm">
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100 space-y-8">
                
                <div class="space-y-6">
                    <h2 class="text-lg font-serif font-bold text-stone-800 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5 text-maroon"></i> Product Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Product Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                                   class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Price (IDR)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required 
                                   class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Condition</label>
                        <input type="text" name="condition" value="{{ old('condition', $product->condition) }}" 
                               class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-stone-50">
                    <h2 class="text-lg font-serif font-bold text-stone-800 mb-6 flex items-center gap-2">
                        <i data-lucide="box" class="w-5 h-5 text-maroon"></i> Stock Management
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($product->sizes as $s)
                        <div class="bg-stone-50 border border-stone-200 p-5 rounded-2xl text-center group">
                            <span class="block text-xs font-black mb-3 text-stone-400">{{ $s->size }}</span>
                            <input type="number" name="sizes[{{ $s->id }}]" value="{{ old('sizes.'.$s->id, $s->stock) }}" min="0" 
                                   class="w-full bg-white border border-stone-200 rounded-xl py-2 px-2 text-center font-bold text-stone-800 outline-none focus:border-maroon">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-stone-100">
                <h2 class="text-[11px] font-bold uppercase tracking-widest text-stone-400 mb-4 ml-1">Main Photo</h2>
                <div class="relative group aspect-[3/4] w-full">
                    <div class="w-full h-full bg-stone-50 rounded-[2rem] border-2 border-dashed border-stone-200 flex flex-col items-center justify-center overflow-hidden relative">
                        <img id="main-preview" src="/images/products/{{ $product->image }}" alt="Preview" class="w-full h-full object-cover">
                        <input type="file" name="image" id="main-image-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>
                <p class="text-[9px] text-stone-400 mt-3 text-center italic">Click on the image to change</p>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-stone-100">
                <h2 class="text-[11px] font-bold uppercase tracking-widest text-stone-400 mb-4 ml-1 uppercase">Asset Try-On (PNG)</h2>
                <div class="relative group aspect-square w-full">
                    <div class="w-full h-full bg-stone-900/5 rounded-[2rem] border-2 border-dashed border-stone-200 flex flex-col items-center justify-center overflow-hidden relative">
                        <img id="tryon-preview" src="/images/products/{{ $product->tryon_image }}" alt="Preview" class="w-full h-full object-contain p-6">
                        <input type="file" name="tryon_image" id="tryon-image-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/png">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-stone-900 text-white py-6 rounded-[2rem] font-bold hover:bg-black transition-all shadow-xl shadow-stone-200 flex items-center justify-center gap-3">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                <span class="uppercase tracking-widest text-xs">Update Product</span>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupPreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        setupPreview('main-image-input', 'main-preview');
        setupPreview('tryon-image-input', 'tryon-preview');
        lucide.createIcons();
    });
</script>
@endsection
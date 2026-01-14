@extends('layout.main')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    
    {{-- Header & Navigation --}}
    <div class="flex items-center gap-5 mb-10">
        <a href="{{ route('admin.products.index') }}" 
           class="group flex items-center justify-center w-12 h-12 bg-white border border-stone-200 rounded-2xl shadow-sm hover:bg-stone-50 transition-all duration-300">
            <i data-lucide="chevron-left" class="w-6 h-6 text-stone-600 group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-3xl font-serif font-bold text-stone-900 tracking-tight">Add New Collection</h1>
            <p class="text-sm text-stone-500">Complete the details to add a premium product to the catalog.</p>
        </div>
    </div>

    {{-- Error Handling --}}
    @if ($errors->any())
        <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                <p class="text-sm font-bold text-red-700">Please check your input again:</p>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1 ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Start --}}
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100 space-y-8">
                
                {{-- Basic Information --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-serif font-bold text-stone-800 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-maroon"></i> Basic Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Product Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Vintage Harrington Jacket" required 
                                   class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none text-stone-800">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Price (IDR)</label>
                            <input type="number" name="price" value="{{ old('price') }}" placeholder="0" required 
                                   class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none text-stone-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Condition</label>
                            <input type="text" name="condition" value="{{ old('condition') }}" placeholder="Contoh: Excellent / 9.5/10" 
                                   class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none text-stone-800">
                        </div>

                        {{-- SEKSI KATEGORI BARU --}}
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Category</label>
                            <div class="relative">
                                <select name="category" required 
                                        class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none text-stone-800 appearance-none cursor-pointer">
                                    <option value="" disabled selected>Choose Category</option>
                                    @foreach(['Outerwear', 'Tops', 'Bottoms', 'Vintage'] as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-stone-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-stone-400 ml-1">Detail Description</label>
                        <textarea name="description" rows="5" placeholder="Write specifications, size details, or any flaws..." 
                                  class="w-full bg-stone-50 border border-stone-200 rounded-2xl py-4 px-5 focus:ring-4 focus:ring-maroon/5 focus:border-maroon transition-all outline-none text-stone-800 resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Stock Size --}}
                <div class="pt-6 border-t border-stone-50">
                    <h2 class="text-lg font-serif font-bold text-stone-800 mb-6 flex items-center gap-2">
                        <i data-lucide="box" class="w-5 h-5 text-maroon"></i> Stock Size
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['S', 'M', 'L', 'XL'] as $sz)
                        <div class="bg-stone-50 border border-stone-200 p-5 rounded-2xl text-center hover:border-maroon/30 transition-colors group">
                            <span class="block text-xs font-black mb-3 text-stone-400 group-hover:text-maroon transition-colors">{{ $sz }}</span>
                            <input type="number" name="sizes[{{ $sz }}]" value="{{ old('sizes.'.$sz, 0) }}" min="0" 
                                   class="w-full bg-white border border-stone-200 rounded-xl py-2 px-2 text-center font-bold text-stone-800 outline-none focus:border-maroon shadow-sm">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Photo Upload --}}
        <div class="space-y-6">
            
            {{-- Main Catalog Photo --}}
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-stone-100">
                <h2 class="text-[11px] font-bold uppercase tracking-widest text-stone-400 mb-4 ml-1 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4"></i> Main Catalog Photo
                </h2>
                <div class="relative group aspect-[3/4] w-full">
                    <div id="main-preview-container" class="w-full h-full bg-stone-50 rounded-[2rem] border-2 border-dashed border-stone-200 flex flex-col items-center justify-center overflow-hidden relative transition-all group-hover:bg-stone-100 group-hover:border-maroon/20">
                        <img id="main-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        <div id="main-placeholder" class="text-center p-6 pointer-events-none">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="image-plus" class="w-6 h-6 text-stone-300"></i>
                            </div>
                            <p class="text-[10px] text-stone-400 font-bold uppercase tracking-tighter">Choose Photo</p>
                        </div>
                        <input type="file" name="image" id="main-image-input" required 
                               class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>
            </div>

            {{-- Try-On Asset --}}
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-stone-100">
                <h2 class="text-[11px] font-bold uppercase tracking-widest text-stone-400 mb-4 ml-1 flex items-center gap-2">
                    <i data-lucide="user-check" class="w-4 h-4"></i> Virtual Try-On Asset
                </h2>
                <div class="relative group aspect-square w-full">
                    <div id="tryon-preview-container" class="w-full h-full bg-stone-900/5 rounded-[2rem] border-2 border-dashed border-stone-200 flex flex-col items-center justify-center overflow-hidden relative group-hover:bg-stone-900/10 transition-all">
                        <img id="tryon-preview" src="#" alt="Preview" class="hidden w-full h-full object-contain p-6">
                        <div id="tryon-placeholder" class="text-center p-6 pointer-events-none">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="shirt" class="w-5 h-5 text-stone-300"></i>
                            </div>
                            <p class="text-[10px] text-stone-400 font-bold uppercase tracking-tighter">Upload Transparent PNG</p>
                        </div>
                        <input type="file" name="tryon_image" id="tryon-image-input" required 
                               class="absolute inset-0 opacity-0 cursor-pointer" accept="image/png">
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-maroon text-white py-6 rounded-[2rem] font-bold hover:bg-red-900 transition-all shadow-xl shadow-maroon/20 flex items-center justify-center gap-3 group">
                <i data-lucide="save" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="uppercase tracking-widest text-xs">Save Collection</span>
            </button>
        </div>
    </form>
</div>

{{-- Preview Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupPreview(inputId, previewId, placeholderId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        setupPreview('main-image-input', 'main-preview', 'main-placeholder');
        setupPreview('tryon-image-input', 'tryon-preview', 'tryon-placeholder');
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
@extends('layout.main')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center justify-center w-12 h-12 bg-white border border-stone-200 rounded-2xl shadow-sm hover:bg-stone-50 transition-all duration-300">
                <i data-lucide="chevron-left" class="w-6 h-6 text-stone-600 group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-serif font-bold text-stone-900 tracking-tight">Product Management</h1>
                <p class="text-sm text-stone-500">Total {{ $products->count() }} collection listed in your catalog.</p>
            </div>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-maroon text-white px-8 py-4 rounded-2xl font-bold hover:bg-red-900 transition-all shadow-xl shadow-maroon/20 flex items-center justify-center gap-3 group">
            <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>
            <span>Add Product</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 rounded-xl text-green-700 font-bold text-sm flex items-center gap-3 animate-fade-in">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-stone-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-50/50 border-b border-stone-100">
                    <tr class="text-[11px] font-bold uppercase tracking-[0.15em] text-stone-400">
                        <th class="px-8 py-5">Product</th>
                        <th class="px-8 py-5">Price</th>
                        <th class="px-8 py-5 text-center">Stock (S/M/L/XL)</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($products as $p)
                    <tr class="hover:bg-stone-50/30 transition-colors duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative group">
                                    <img src="/images/products/{{ $p->image }}" class="w-14 h-20 object-cover rounded-xl shadow-md border border-stone-100 transition-transform group-hover:scale-105">
                                </div>
                                <span class="font-serif font-bold text-stone-800 text-lg">{{ $p->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 font-bold text-stone-600">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center gap-3">
                                @foreach($p->sizes as $s)
                                    <div class="flex flex-col items-center bg-stone-50 px-2 py-1 rounded-lg min-w-[35px] border border-stone-100">
                                        <span class="text-[9px] font-black text-stone-400 uppercase leading-tight">{{ $s->size }}</span>
                                        <span class="text-xs font-bold {{ $s->stock <= 0 ? 'text-red-500' : 'text-stone-700' }}">{{ $s->stock }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.products.edit', $p->id) }}" 
                                   class="p-3 text-blue-500 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-300 shadow-sm shadow-blue-100">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </a>
                                
                                <button type="button" 
                                        onclick="confirmDelete('{{ $p->id }}', '{{ $p->name }}')"
                                        class="p-3 text-red-500 bg-red-50 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm shadow-red-100">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>

                                <form id="delete-form-{{ $p->id }}" action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4 overflow-hidden">
    <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 transform transition-all scale-95 opacity-0 duration-300" id="modalCard">
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-6">
                <i data-lucide="alert-triangle" class="w-10 h-10 text-red-500"></i>
            </div>
            
            <h3 class="text-2xl font-serif font-bold text-stone-900 mb-2">Hapus Produk?</h3>
            <p class="text-stone-500 mb-8 leading-relaxed">
                Anda akan menghapus <span id="productNameLabel" class="font-bold text-stone-800"></span>. <br>
                Tindakan ini tidak dapat dibatalkan dan semua data terkait akan hilang.
            </p>

            <div class="flex gap-4 w-full">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-6 py-4 rounded-2xl border border-stone-200 text-stone-600 font-bold hover:bg-stone-50 transition-colors">
                    Batalkan
                </button>
                <button id="confirmDeleteBtn" 
                        class="flex-1 px-6 py-4 rounded-2xl bg-red-500 text-white font-bold hover:bg-red-600 transition-all shadow-lg shadow-red-200">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDeleteId = null;

    function confirmDelete(id, name) {
        currentDeleteId = id;
        document.getElementById('productNameLabel').innerText = '"' + name + '"';
        
        const modal = document.getElementById('deleteModal');
        const card = document.getElementById('modalCard');
        
        modal.classList.remove('hidden');
        // Animasi masuk
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const card = document.getElementById('modalCard');
        card.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            document.getElementById('deleteModal').classList.add('hidden');
        }, 300);
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if(currentDeleteId) {
            document.getElementById('delete-form-' + currentDeleteId).submit();
        }
    });

    // Close modal if clicking backdrop
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if(e.target === this) closeDeleteModal();
    });
</script>
@endsection
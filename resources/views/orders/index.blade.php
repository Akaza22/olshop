@extends('layout.main')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-4xl font-serif font-bold text-stone-900 tracking-tight">Order History</h1>
        <i data-lucide="package" class="w-10 h-10 text-stone-300"></i>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-[2rem] shadow-sm border border-stone-100 overflow-hidden hover:shadow-md transition">
                    <div class="bg-stone-50 px-8 py-4 flex justify-between items-center border-b border-stone-100">
                        <div class="flex gap-6 text-xs font-bold uppercase tracking-widest text-stone-400">
                            <span>ID: #TVTO-{{ $order->id }}</span>
                            <span>Date: {{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <span class="px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $order->status == 'Sent' || $order->status == 'Terkirim' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="flex -space-x-4 overflow-hidden">
                            @foreach($order->items as $item)
                                <img src="/images/products/{{ $item->product->image }}" 
                                     class="inline-block h-16 w-12 object-cover rounded-lg ring-4 ring-white shadow-sm"
                                     title="{{ $item->product->name }}">
                            @endforeach
                        </div>

                        <div class="text-center md:text-left flex-grow md:ml-8">
                            <p class="text-stone-500 text-sm">Total Payment</p>
                            <p class="text-2xl font-bold text-maroon font-serif">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        <button onclick="toggleModal('modal-{{ $order->id }}')" 
                                class="px-6 py-3 border-2 border-stone-200 rounded-xl text-sm font-bold text-stone-600 hover:border-maroon hover:text-maroon transition flex items-center gap-2">
                            <i data-lucide="eye" class="w-4 h-4"></i> View Details
                        </button>
                    </div>
                </div>

                <div id="modal-{{ $order->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" onclick="toggleModal('modal-{{ $order->id }}')"></div>
                    
                    <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                        <div class="bg-stone-50 px-8 py-6 border-b border-stone-100 flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-serif font-bold text-stone-900">Order Details</h2>
                                <p class="text-xs font-bold text-stone-400 uppercase tracking-widest">#TVTO-{{ $order->id }}</p>
                            </div>
                            <button onclick="toggleModal('modal-{{ $order->id }}')" class="p-2 hover:bg-stone-200 rounded-full transition">
                                <i data-lucide="x" class="w-6 h-6 text-stone-500"></i>
                            </button>
                        </div>

                        <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6 bg-stone-50 p-6 rounded-2xl border border-stone-100">
                                <div>
                                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Recipient</p>
                                    <p class="font-bold text-stone-800">{{ $order->name }}</p>
                                    <p class="text-sm text-stone-600">{{ $order->contact }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Delivery Address</p>
                                    <p class="text-sm text-stone-600 leading-relaxed">{{ $order->address }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Product List</p>
                                @foreach($order->items as $item)
                                <div class="flex items-center gap-4 py-3 border-b border-stone-50 last:border-0">
                                    <img src="/images/products/{{ $item->product->image }}" class="w-16 h-20 object-cover rounded-xl shadow-sm">
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-stone-800">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-stone-500">Size: <span class="font-bold text-maroon">{{ $item->size }}</span></p>
                                    </div>
                                    <p class="font-bold text-stone-800">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-8 py-6 bg-stone-900 text-white flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">Grand Total</p>
                                <p class="text-2xl font-serif font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="px-4 py-2 bg-white/10 rounded-xl text-xs font-bold uppercase tracking-widest">
                                Status: {{ $order->status }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-stone-300">
            <div class="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shopping-bag" class="w-10 h-10 text-stone-300"></i>
            </div>
            <p class="text-stone-500 italic">You don't have any order history yet.</p>
            <a href="/katalog" class="mt-6 inline-flex items-center gap-2 bg-maroon text-white px-8 py-3 rounded-xl font-bold hover:bg-red-900 transition shadow-lg shadow-maroon/20">
                Start Shopping Now
            </a>
        </div>
    @endif
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Stop scroll
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Enable scroll
        }
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f5f5f4;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d6d3d1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #800000;
    }
</style>
@endsection
@extends('layout.main')

@section('content')
<div class="max-w-4xl mx-auto py-10 grid grid-cols-1 md:grid-cols-2 gap-12">
    
    <div>
        <h1 class="text-3xl font-serif font-bold text-stone-900 mb-6">Shipping Information</h1>
        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full bg-white border border-stone-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-maroon/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">WhatsApp / Contact</label>
                <input type="text" name="contact" required class="w-full bg-white border border-stone-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-maroon/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-stone-400 mb-2">Full Address</label>
                <textarea name="address" rows="4" required class="w-full bg-white border border-stone-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-maroon/20 outline-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-maroon text-white py-4 rounded-xl font-bold hover:bg-red-900 transition shadow-lg">
                Confirm Order
            </button>
        </form>
    </div>

    <div class="bg-stone-50 p-8 rounded-[2rem] border border-stone-100 h-fit">
        <h2 class="text-xl font-serif font-bold mb-6 text-stone-800">Shopping Summary</h2>
        <div class="space-y-4 mb-6">
            @foreach($cart as $item)
            <div class="flex justify-between text-sm">
                <span class="text-stone-600">{{ $item['name'] }} ({{ $item['size'] }})</span>
                <span class="font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="border-t border-stone-200 pt-4 flex justify-between items-center">
            <span class="font-bold text-stone-900">Total Price</span>
            <span class="text-2xl font-bold text-maroon font-serif">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

</div>
@endsection
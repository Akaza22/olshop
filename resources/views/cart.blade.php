@extends('layout.main')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <h1 class="text-4xl font-serif font-bold text-stone-900 mb-10">Shopping Cart</h1>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-stone-100">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-50 border-b border-stone-100">
                    <tr>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-stone-400">Product</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-stone-400 text-center">Size</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-stone-400 text-center">Quantity</th>
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-widest text-stone-400 text-right">Subtotal</th>
                        <th class="px-8 py-5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($cart as $id => $details)
                    <tr class="hover:bg-stone-50/50 transition duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <img src="/images/products/{{ $details['image'] }}" class="w-16 h-20 object-cover rounded-lg shadow-sm border border-stone-100">
                                <div>
                                    <span class="font-serif font-bold text-stone-800 text-lg block">{{ $details['name'] }}</span>
                                    <span class="text-[10px] text-stone-400 uppercase tracking-widest">Unit Price: Rp{{ number_format($details['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-stone-100 border border-stone-200 rounded-xl">
                                <span class="text-base font-bold text-stone-700 uppercase">{{ $details['size'] }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-stone-50 border border-stone-200 rounded-xl">
                                <span class="text-base font-bold text-stone-800">{{ $details['quantity'] }}</span>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <span class="font-bold text-brown text-lg">
                                Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-stone-300 hover:text-maroon hover:bg-red-50 rounded-lg transition-all group">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-8 bg-stone-50 border-t border-stone-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="text-center sm:text-left">
                    <p class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-1">Total Estimated Payment</p>
                    <p class="text-4xl font-bold text-maroon font-serif">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('checkout.index') }}" class="w-full sm:w-auto bg-maroon text-white px-12 py-5 rounded-2xl font-bold hover:bg-red-900 transition-all shadow-xl shadow-maroon/20 text-center flex items-center justify-center gap-3 group">
                    <span>Checkout Now</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="/katalog" class="text-stone-400 hover:text-maroon font-bold text-sm uppercase tracking-widest transition flex items-center justify-center gap-2">
                <i data-lucide="chevron-left" class="w-4 h-4"></i> Continue shopping
            </a>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-stone-200 shadow-inner">
            <div class="w-24 h-24 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-stone-100">
                <i data-lucide="shopping-bag" class="w-12 h-12 text-stone-200"></i>
            </div>
            <h2 class="text-2xl font-serif font-bold text-stone-800 mb-2">Wow, Your Cart Is Still Empty</h2>
            <p class="text-stone-500 mb-8 italic">It seems you haven't found your dream jacket today.</p>
            <a href="/katalog" class="bg-maroon text-white px-10 py-4 rounded-xl font-bold hover:bg-red-900 transition shadow-lg">
                Start Hunting Catalog
            </a>
        </div>
    @endif
</div>
@endsection
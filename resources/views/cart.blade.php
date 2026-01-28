@extends('layout.main')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <h1 class="text-4xl font-serif font-bold text-stone-900 mb-10">Shopping Cart</h1>

    <div id="cart-container">
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
                    <tbody class="divide-y divide-stone-100" id="cart-items-body">
                        @foreach($cart as $id => $details)
                        {{-- Tambahkan ID unik pada setiap baris untuk manipulasi DOM --}}
                        <tr class="hover:bg-stone-50/50 transition duration-300 cart-row" id="row-{{ $id }}">
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
                                {{-- Form Hapus dengan Class khusus AJAX --}}
                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="remove-from-cart-form">
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
                        {{-- ID 'cart-total' untuk update harga otomatis --}}
                        <p class="text-4xl font-bold text-maroon font-serif" id="cart-total">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
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
            {{-- VIEW KOSONG (Dipanggil juga via JS jika barang habis) --}}
            <div id="empty-cart-view" class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-stone-200 shadow-inner">
                <div class="w-24 h-24 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-stone-100">
                    <i data-lucide="shopping-bag" class="w-12 h-12 text-stone-200"></i>
                </div>
                <h2 class="text-2xl font-serif font-bold text-stone-800 mb-2">Wow, Your Cart Is Still Empty</h2>
                <p class="text-stone-500 mb-8 italic">It seems you haven't found your dream jacket today.</p>
                <a href="/katalog" class="bg-maroon text-white px-10 py-4 rounded-xl font-bold hover:bg-red-900 transition shadow-lg inline-block">
                    Start Hunting Catalog
                </a>
            </div>
        @endif
    </div>
</div>

{{-- TOAST: REMOVE FROM CART SUCCESS --}}
<div id="remove-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 translate-y-20 opacity-0 transition-all duration-500 pointer-events-none">
    <div class="bg-stone-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10">
        <div class="w-8 h-8 bg-maroon rounded-full flex items-center justify-center text-white">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </div>
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-maroon">Item Removed</p>
            <p class="text-xs text-stone-300">The product has been removed from your bag.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const removeForms = document.querySelectorAll('.remove-from-cart-form');
        const removeToast = document.getElementById('remove-toast');

        removeForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const url = this.action;
                const rowId = this.closest('.cart-row').id;
                
                // Animasi Keluar Baris (Fade & Slide)
                const rowElement = document.getElementById(rowId);
                rowElement.style.transition = 'all 0.5s ease';
                rowElement.style.opacity = '0';
                rowElement.style.transform = 'translateX(20px)';

                fetch(url, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // 1. Update Badge Navbar (menggunakan data dari controller)
                    const badge = document.getElementById('cart-badge');
                    if(badge) {
                        if(data.cart_count > 0) {
                            badge.innerText = data.cart_count;
                        } else {
                            badge.classList.add('hidden');
                        }
                    }

                    // 2. Update Total Harga
                    document.getElementById('cart-total').innerText = 'Rp ' + data.new_total;

                    // 3. Tampilkan Toast
                    removeToast.classList.remove('translate-y-20', 'opacity-0');
                    removeToast.classList.add('translate-y-0', 'opacity-100');

                    // 4. Hapus Baris dari DOM setelah animasi selesai
                    setTimeout(() => {
                        rowElement.remove();
                        // Jika keranjang benar-benar kosong, reload untuk ganti view
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    }, 500);

                    // Hilangkan Toast
                    setTimeout(() => {
                        removeToast.classList.add('translate-y-20', 'opacity-0');
                        removeToast.classList.remove('translate-y-0', 'opacity-100');
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    rowElement.style.opacity = '1';
                    rowElement.style.transform = 'translateX(0)';
                });
            });
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection
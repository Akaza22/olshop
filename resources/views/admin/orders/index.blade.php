@extends('layout.main')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    
    <div class="flex items-center gap-5 mb-10">
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center justify-center w-12 h-12 bg-white border border-stone-200 rounded-2xl shadow-sm hover:bg-stone-50 transition-all duration-300">
            <i data-lucide="chevron-left" class="w-6 h-6 text-stone-600 group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-3xl font-serif font-bold text-stone-900 tracking-tight">Order Management</h1>
            <p class="text-sm text-stone-500">Monitor shopping activity and manage customer shipping status.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-xl text-green-700 font-bold text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-stone-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-stone-50/50 border-b border-stone-100">
                    <tr class="text-[11px] font-bold uppercase tracking-[0.15em] text-stone-400">
                        <th class="px-8 py-5">Order ID</th>
                        <th class="px-8 py-5">Customer</th>
                        <th class="px-8 py-5">Total Payment</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-stone-50/30 transition-colors duration-300">
                        <td class="px-8 py-6">
                            <span class="font-mono font-bold text-stone-400 text-xs">#TVTO-{{ $order->id }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="font-bold text-stone-800">{{ $order->name }}</span>
                                <span class="text-xs text-stone-400 flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $order->contact }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-bold text-maroon">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($order->status == 'Sent')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase rounded-full border border-green-100">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    {{ $order->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black uppercase rounded-full border border-orange-100">
                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                                    {{ $order->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($order->status == 'Pending')
                            <form action="{{ route('admin.orders.ship', $order->id) }}" method="POST">
                                @csrf
                                <button class="group relative inline-flex items-center gap-2 bg-maroon text-white px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-red-900 transition-all shadow-lg shadow-maroon/10">
                                    <i data-lucide="truck" class="w-4 h-4"></i>
                                    Mark as Sent
                                </button>
                            </form>
                            @else
                            <div class="flex items-center justify-end gap-1 text-stone-300">
                                <i data-lucide="check-check" class="w-4 h-4"></i>
                                <span class="text-[10px] font-bold uppercase italic">Finished</span>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <i data-lucide="inbox" class="w-12 h-12 text-stone-200"></i>
                                <p class="text-stone-400 font-medium italic">No orders received at this time.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
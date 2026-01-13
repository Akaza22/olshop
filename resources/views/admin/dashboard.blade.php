@extends('layout.main')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-serif font-bold text-stone-900 tracking-tight">Admin Dashboard</h1>
            <p class="text-stone-500">Summary of your VTO Thrift boutique performance.</p>
        </div>
        <form action="{{ route('admin.dashboard') }}" method="GET" id="yearFilterForm" class="flex flex-col gap-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Select Analytical Year</label>
            <select name="year" onchange="document.getElementById('yearFilterForm').submit()" 
                    class="bg-white border border-stone-200 text-stone-700 text-xs font-bold py-3 px-5 rounded-2xl outline-none focus:ring-4 focus:ring-maroon/5 shadow-sm">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-stone-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center text-stone-600">
                <i data-lucide="package" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Total Products</p>
                <p class="text-2xl font-bold text-stone-800">{{ $totalProducts }}</p>
            </div>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-stone-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center text-stone-600">
                <i data-lucide="shopping-cart" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Total Orders</p>
                <p class="text-2xl font-bold text-stone-800">{{ $totalOrders }}</p>
            </div>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-stone-100 flex items-center gap-5 border-l-4 border-l-orange-400">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                <i data-lucide="clock" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Pending</p>
                <p class="text-2xl font-bold text-stone-800">{{ $pendingOrders }}</p>
            </div>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-stone-100 flex items-center gap-5 border-l-4 border-l-maroon">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-maroon">
                <i data-lucide="banknote" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Revenue</p>
                <p class="text-xl font-bold text-maroon font-serif">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100 overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-serif font-bold text-stone-900">Monthly Sales Trend</h2>
                <div class="flex items-center gap-2 text-[10px] font-bold text-stone-400 uppercase">
                    <span class="w-2 h-2 bg-maroon rounded-full"></span> Completed
                </div>
            </div>
            <div class="overflow-x-auto scrollbar-hide">
                <div class="h-[320px] min-w-[650px]">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <p class="text-[9px] text-stone-400 mt-4 text-center italic md:hidden">Scroll the chart to see monthly details</p>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-stone-100">
            <h2 class="text-xl font-serif font-bold text-stone-900 mb-8">Order Status</h2>
            <div class="h-[320px] flex items-center justify-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="{{ route('admin.products.index') }}" class="group bg-stone-900 p-8 rounded-[2.5rem] shadow-xl hover:bg-black transition-all duration-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl group-hover:bg-white/10 transition"></div>
            <div class="relative z-10 text-white">
                <div class="w-12 h-12 bg-maroon rounded-xl flex items-center justify-center mb-6">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <h2 class="text-2xl font-serif font-bold mb-2">Product Management</h2>
                <p class="text-stone-400 text-sm">Manage stock, prices, and Virtual Try-On assets.</p>
                <div class="mt-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-maroon">
                    Open Catalog <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="group bg-white p-8 rounded-[2.5rem] shadow-xl border border-stone-100 hover:border-maroon/20 transition-all duration-500 relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center mb-6 text-stone-600">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
                <h2 class="text-2xl font-serif font-bold mb-2 text-stone-900">Incoming Orders</h2>
                <p class="text-stone-500 text-sm">Update the delivery status for the customer.</p>
                <div class="mt-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-maroon">
                    View Order <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart: Penjualan
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($chartData),
                    borderColor: '#800000',
                    backgroundColor: 'rgba(128, 0, 0, 0.05)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#800000',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1c1917',
                        titleFont: { family: 'serif', size: 14 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f5f5f4' },
                        ticks: { 
                            font: { size: 10, weight: 'bold' },
                            color: '#a8a29e',
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#a8a29e' }
                    }
                }
            }
        });

        // Doughnut Chart: Status
        const orderCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Sent'],
                datasets: [{
                    data: [{{ $pendingOrders }}, {{ $totalOrders - $pendingOrders }}],
                    backgroundColor: ['#fbbf24', '#800000'],
                    borderWidth: 8,
                    borderColor: '#fff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 30, font: { size: 12, weight: 'bold' }, color: '#444' }
                    }
                }
            }
        });
    });
</script>

<style>
    /* Menyembunyikan scrollbar tapi tetap bisa di-scroll */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
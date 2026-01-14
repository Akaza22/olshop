<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. DASHBOARD: Statistik Dinamis & Grafik dengan Filter Tahun
    public function dashboard(Request $request) {
        $selectedYear = $request->get('year', date('Y'));

        // Statistik Ringkas
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $totalRevenue  = Order::where('status', 'Sent')->sum('total_price');

        // Query Data Penjualan per Bulan (Line Chart)
        $salesData = Order::where('status', 'Sent')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartLabels = [];
        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = $months[$i - 1];
            $chartData[] = $salesData[$i] ?? 0;
        }

        // Ambil Daftar Tahun yang Ada di Database untuk Filter
        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'pendingOrders', 'totalRevenue',
            'chartLabels', 'chartData', 'selectedYear', 'availableYears'
        ));
    }

    // 2. DAFTAR PRODUK
    public function index() {
        $products = Product::with('sizes')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // 3. FORM TAMBAH PRODUK
    public function create() {
        return view('admin.products.create');
    }

    // 4. SIMPAN PRODUK BARU
    public function store(Request $request) {
        // Tambahkan validasi untuk 'category'
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string', // Perbaikan: Validasi kategori
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tryon_image' => 'required|image|mimes:png|max:2048',
            'sizes' => 'required|array'
        ]);

        // Handling Main Image
        $imageName = time().'_main.'.$request->image->extension();  
        $request->image->move(public_path('images/products'), $imageName);

        // Handling Try-On Image
        $tryOnName = time().'_tryon.'.$request->tryon_image->extension();
        $request->tryon_image->move(public_path('images/products'), $tryOnName);

        // Simpan ke Database
        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category, // Perbaikan: Simpan kategori
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => $imageName,
            'tryon_image' => $tryOnName
        ]);

        // Simpan Stok per Ukuran
        foreach($request->sizes as $size => $stock) {
            ProductSize::create([
                'product_id' => $product->id,
                'size' => $size,
                'stock' => $stock ?? 0
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 5. FORM EDIT PRODUK
    public function edit($id) {
        $product = Product::with('sizes')->findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // 6. UPDATE PRODUK
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string', // Perbaikan: Validasi kategori
            'price' => 'required|numeric',
        ]);

        // Update Main Image jika ada file baru
        if ($request->hasFile('image')) {
            if(File::exists(public_path('images/products/'.$product->image))) {
                File::delete(public_path('images/products/'.$product->image));
            }
            $imageName = time().'_main.'.$request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        // Update Try-On Image jika ada file baru
        if ($request->hasFile('tryon_image')) {
            if($product->tryon_image && File::exists(public_path('images/products/'.$product->tryon_image))) {
                File::delete(public_path('images/products/'.$product->tryon_image));
            }
            $tryOnName = time().'_tryon.'.$request->tryon_image->extension();
            $request->tryon_image->move(public_path('images/products'), $tryOnName);
            $product->tryon_image = $tryOnName;
        }

        // Update Data Produk ke Database
        $product->update([
            'name' => $request->name,
            'category' => $request->category, // Perbaikan: Update kategori di sini
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
        ]);

        // Penting: Simpan perubahan image/tryon_image jika manual di-assign di atas
        $product->save(); 

        // Update Stok Ukuran
        if ($request->has('sizes')) {
            foreach($request->sizes as $sizeId => $stock) {
                ProductSize::where('id', $sizeId)->update(['stock' => $stock]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 7. HAPUS PRODUK
    public function destroy($id) {
        $product = Product::findOrFail($id);
        try {
            if(File::exists(public_path('images/products/'.$product->image))) {
                File::delete(public_path('images/products/'.$product->image));
            }
            if(File::exists(public_path('images/products/'.$product->tryon_image))) {
                File::delete(public_path('images/products/'.$product->tryon_image));
            }
            $product->delete(); 
            return back()->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Produk gagal dihapus karena riwayat transaksi.');
        }
    }

    // 8. DAFTAR PESANAN
    public function orders() {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // 9. UPDATE STATUS PENGIRIMAN
    public function shipOrder($id) {
        Order::findOrFail($id)->update(['status' => 'Sent']);
        return back()->with('success', 'Pesanan telah ditandai sebagai Sent!');
    }
}
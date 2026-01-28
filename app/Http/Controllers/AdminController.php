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
        // 1. Ambil Parameter Filter
        $viewMode = $request->get('view_mode', 'monthly'); // default bulanan
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', date('n'));

        // 2. Statistik Dasar (Global)
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $totalRevenue  = Order::where('status', 'Sent')->sum('total_price');

        $chartLabels = [];
        $chartData = [];

        if ($viewMode == 'monthly') {
            // Logika View Per Bulan (Harian)
            $daysInMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth)->daysInMonth;
            
            $salesData = Order::where('status', 'Sent')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->selectRaw('DAY(created_at) as day, SUM(total_price) as total')
                ->groupBy('day')
                ->pluck('total', 'day')->toArray();

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $chartLabels[] = "$d";
                $chartData[] = $salesData[$d] ?? 0;
            }
        } else {
            // Logika View Per Tahun (Bulanan)
            $salesData = Order::where('status', 'Sent')
                ->whereYear('created_at', $selectedYear)
                ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
                ->groupBy('month')
                ->pluck('total', 'month')->toArray();

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = $months[$m - 1];
                $chartData[] = $salesData[$m] ?? 0;
            }
        }

        // 3. Data untuk Filter Dropdown
        $availableYears = Order::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        if ($availableYears->isEmpty()) $availableYears = collect([date('Y')]);
        
        $listMonths = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'pendingOrders', 'totalRevenue',
            'chartLabels', 'chartData', 'selectedYear', 'selectedMonth', 'viewMode', 'availableYears', 'listMonths'
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

        // The key is the ->with('success', '...') part
    return redirect()->route('admin.products.index')
                     ->with('success', 'New collection has been published successfully!');
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
            foreach($request->sizes as $sizeName => $stock) {
                // updateOrCreate: Jika kombinasi product_id & size ada, update stock-nya. 
                // Jika tidak ada, buat baris baru di tabel product_sizes.
                ProductSize::updateOrCreate(
                    [
                        'product_id' => $product->id, 
                        'size' => $sizeName
                    ],
                    [
                        'stock' => $stock ?? 0
                    ]
                );
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    // 7. HAPUS PRODUK
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->name;
        $product->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Product '{$productName}' deleted successfully!"
            ]);
        }

        return redirect()->back()->with('success', 'Product deleted!');
    }

    // 8. DAFTAR PESANAN
    public function orders() {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // 9. UPDATE STATUS PENGIRIMAN
    public function shipOrder($id) {
        Order::findOrFail($id)->update(['status' => 'Sent']);
        return back()->with('success', 'Order marked as sent!');
    }
}
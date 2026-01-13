<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $size = ProductSize::findOrFail($request->size_id);
        
        // Ambil quantity dari input, default 1 jika tidak ada
        $quantity = $request->input('quantity', 1);

        // Validasi tambahan: Pastikan quantity tidak melebihi stok asli di DB
        if ($quantity > $size->stock) {
            return redirect()->back()->with('error', 'The quantity exceeds the available stock!');
        }

        $cart = session()->get('cart', []);
        $cartId = $product->id . '-' . $size->id;

        if(isset($cart[$cartId])) {
            // Jika barang sudah ada, tambahkan jumlahnya
            $cart[$cartId]['quantity'] += $quantity;
        } else {
            $cart[$cartId] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image,
                "size" => $size->size
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Successfully added!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product removed!');
    }

    // Tampilkan Form Checkout
    public function checkout() {
        $cart = session()->get('cart', []);
        if(count($cart) == 0) return redirect('/katalog');
        
        $total = 0;
        foreach($cart as $item) { $total += $item['price'] * $item['quantity']; }
        
        return view('checkout', compact('cart', 'total'));
    }

    // Proses Simpan Pesanan
    public function storeCheckout(Request $request) {
        $cart = session()->get('cart');

        // Gunakan Transaction agar jika ada error di tengah, database tetap aman
        DB::transaction(function () use ($request, $cart) {
            // 1. Simpan ke tabel Orders
            $order = Order::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'contact' => $request->contact,
                'address' => $request->address,
                'total_price' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
                'status' => 'Pending'
            ]);

            foreach ($cart as $id => $details) {
                // 2. Simpan detail barang ke OrderItems
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'size' => $details['size'],
                    'price' => $details['price'],
                ]);

                // 3. KURANGI STOK secara otomatis
                $productSize = ProductSize::where('product_id', $details['product_id'])
                                        ->where('size', $details['size'])
                                        ->first();
                if ($productSize) {
                    $productSize->decrement('stock', $details['quantity']);
                }
            }
        });

        // 4. KOSONGKAN KERANJANG
        session()->forget('cart');

        return redirect('/orders')->with('success', 'Your order has been successfully sent!');
    }

    // Menampilkan Riwayat Pesanan User
    public function orderHistory()
    {
        // Mengambil pesanan milik user login, diurutkan dari yang terbaru
        $orders = Order::with('items.product')
                        ->where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('orders.index', compact('orders'));
    }
}
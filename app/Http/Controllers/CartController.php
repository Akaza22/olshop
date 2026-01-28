<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize; // Konsisten menggunakan ProductSize sesuai tabel product_sizes
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        // 1. Validasi input dasar menggunakan tabel product_sizes
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $size = ProductSize::findOrFail($request->size_id); // Gunakan ProductSize
        
        // 2. Ambil keranjang dari session
        $cart = session()->get('cart', []);
        $cartKey = $request->product_id . '-' . $request->size_id;

        // 3. Hitung total: yang sudah di cart + yang baru mau ditambah
        $existingQty = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $totalRequestedQty = $existingQty + $request->quantity;

        // 4. CEK STOK: Jika melebihi stok di tabel product_sizes, kirim error
        if ($totalRequestedQty > $size->stock) {
            return response()->json([
                'success' => false,
                'message' => "Stock limit reached. You already have {$existingQty} in your bag. Only " . ($size->stock - $existingQty) . " more available."
            ], 422);
        }

        // 5. Jika aman, update session
        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $totalRequestedQty;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id, // PENTING: Harus ada untuk storeCheckout
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->image,
                "size" => $size->size,
                "size_id" => $size->id
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'message' => 'Product successfully added to bag!'
        ]);
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $newTotal = 0;
        foreach((session('cart') ?? []) as $item) {
            $newTotal += $item['price'] * $item['quantity'];
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => count(session('cart') ?? []),
                'new_total' => number_format($newTotal, 0, ',', '.')
            ]);
        }

        return redirect()->back()->with('success', 'Product removed from bag.');
    }

    public function checkout() {
        $cart = session()->get('cart', []);
        if(count($cart) == 0) return redirect('/katalog');
        
        $total = 0;
        foreach($cart as $item) { $total += $item['price'] * $item['quantity']; }
        
        return view('checkout', compact('cart', 'total'));
    }

    public function storeCheckout(Request $request) {
        $cart = session()->get('cart');
        if (!$cart) return redirect('/katalog');

        DB::transaction(function () use ($request, $cart) {
            // 1. Simpan ke tabel Orders
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'contact' => $request->contact,
                'address' => $request->address,
                'total_price' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
                'status' => 'Pending'
            ]);

            foreach ($cart as $id => $details) {
                // 2. Simpan detail ke OrderItems
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['product_id'],
                    'size' => $details['size'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'], // Jangan lupa quantity di OrderItem
                ]);

                // 3. Kurangi Stok di tabel product_sizes
                $productSize = ProductSize::where('product_id', $details['product_id'])
                                        ->where('size', $details['size'])
                                        ->first();
                if ($productSize) {
                    $productSize->decrement('stock', $details['quantity']);
                }
            }
        });

        session()->forget('cart');
        return redirect('/orders')->with('success', 'Checkout successful! Your order is being processed.');
    }

    public function orderHistory()
    {
        $orders = Order::with('items.product')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('orders.index', compact('orders'));
    }
}
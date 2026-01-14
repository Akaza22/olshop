<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('katalog', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        return view('detail', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $products = Product::query()
            // Filter Pencarian Teks
            ->when($query, function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            // Filter Kategori
            ->when($category, function($q) use ($category) {
                $q->where('category', $category);
            })
            // Filter Rentang Harga
            ->when($min_price, function($q) use ($min_price) {
                $q->where('price', '>=', $min_price);
            })
            ->when($max_price, function($q) use ($max_price) {
                $q->where('price', '<=', $max_price);
            })
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.product-grid', compact('products'))->render(),
                'count' => $products->count()
            ]);
        }

        return view('katalog', compact('products', 'query'));
    }
}
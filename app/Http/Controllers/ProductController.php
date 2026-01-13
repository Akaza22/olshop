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

    

    // Hapus atau abaikan fungsi checkVtoStatus jika pakai Fal.ai synchronous
}
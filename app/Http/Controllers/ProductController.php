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

    public function generateAiVto(Request $request) 
    {
        $request->validate([
            'user_image' => 'required|image|max:2048',
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        $path = $request->file('user_image')->store('temp_vto', 'public');

        // Pastikan APP_URL di .env sudah benar (link Ngrok HTTPS)
        $baseUrl = rtrim(config('app.url'), '/');
        $userImageUrl = $baseUrl . '/storage/' . $path;
        $garmentUrl = $baseUrl . '/images/products/' . $product->tryon_image;

        // Panggil Fal.ai dengan Field yang SUDAH DIPERBAIKI
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . env('FAL_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://fal.run/fal-ai/idm-vton', [
            'human_image_url'   => $userImageUrl,   // Sebelumnya human_img
            'garment_image_url' => $garmentUrl,     // Sebelumnya garm_img
            'description'       => $product->name,  // Sebelumnya garment_des
            'is_checked'        => true,
            'seed'              => 42
        ]);

        if ($response->failed()) {
            // Jika gagal, kirim error asli dari Fal.ai agar bisa dibaca di Console
            return response()->json($response->json(), $response->status());
        }

        return response()->json($response->json());
    }

    // Hapus atau abaikan fungsi checkVtoStatus jika pakai Fal.ai synchronous
}
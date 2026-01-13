<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSize;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Input Data Produk Utama
        $product = Product::create([
            'name' => 'Gorpcore Jacket',
            'description' => 'Gorpcore berwarna hitam, bahan tebal, water repellent dan sangat nyaman dipakai untuk outdoor.',
            'price' => 120000,
            'condition' => '9/10 (Pre-loved)',
            'image' => 'gorpcore.jpg',
            'tryon_image' => 'gorpcore.png',
        ]);

        // 2. Input Data Ukuran & Stok (Menggunakan Relasi)
        $product->sizes()->createMany([
            ['size' => 'S', 'stock' => 5],
            ['size' => 'M', 'stock' => 2],
            ['size' => 'L', 'stock' => 0], // Contoh stok habis
            ['size' => 'XL', 'stock' => 3],
        ]);

        // Tambah produk lain jika perlu
        $product2 = Product::create([
            'name' => 'Vintage Windbreaker',
            'description' => 'Jaket vintage tahun 90-an dengan warna retro yang ikonik.',
            'price' => 150000,
            'condition' => '8.5/10 (Vintage)',
            'image' => 'windbreaker.jpg',
            'tryon_image' => 'windbreaker.png',
        ]);

        $product2->sizes()->createMany([
            ['size' => 'M', 'stock' => 4],
            ['size' => 'L', 'stock' => 1],
        ]);
    }
}
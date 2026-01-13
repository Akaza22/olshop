<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Database (Hapus data lama)
        // Matikan foreign key check agar bisa truncate tabel yang berelasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Product::truncate();
        ProductSize::truncate();
        Order::truncate();
        OrderItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Users (Berdasarkan screenshot tabel users kamu)
        $admin = User::create([
            'name' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'cust1',
            'email' => 'cust1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // 3. Seed Products (4 Produk sesuai gambar)
        $productData = [
            ['name' => 'Flannel Shirt', 'price' => 150000, 'img' => 'kemeja', 'desc' => 'Vintage flannel shirt made of lightweight wool.'],
            ['name' => 'Premium Batik', 'price' => 250000, 'img' => 'batik', 'desc' => 'Authentic hand-drawn batik with classic motifs.'],
            ['name' => 'Gorpcore Jacket', 'price' => 450000, 'img' => 'gorpcore', 'desc' => 'Gorpcore-style outdoor jacket, windproof and waterproof.'],
            ['name' => 'Striped Shirt', 'price' => 120000, 'img' => 'baju', 'desc' => 'Retro striped t-shirt made of 30s combed cotton.'],
        ];

        foreach ($productData as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['desc'],
                'price' => $data['price'],
                'condition' => 'Excellent',
                'image' => $data['img'] . '.jpg', // File JPG
                'tryon_image' => $data['img'] . '.png', // File PNG Transparan
            ]);

            // Tambahkan Stok untuk tiap ukuran
            foreach (['S', 'M', 'L', 'XL'] as $size) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock' => rand(5, 15),
                ]);
            }
        }

        // 4. Seed Orders (6 Transaksi untuk simulasi Chart)
        // Kita buat transaksi mundur 4 bulan agar Line Chart terbentuk
        $transactions = [
            ['month' => -3, 'status' => 'Sent', 'total' => 300000], // 3 bulan lalu
            ['month' => -2, 'status' => 'Sent', 'total' => 450000], // 2 bulan lalu
            ['month' => -1, 'status' => 'Sent', 'total' => 750000], // 1 bulan lalu
            ['month' => 0,  'status' => 'Sent', 'total' => 500000], // Bulan ini
            ['month' => 0,  'status' => 'Pending',  'total' => 200000], // Pending bulan ini
            ['month' => 0,  'status' => 'Sent', 'total' => 350000], // Bulan ini
        ];

        foreach ($transactions as $index => $t) {
            $date = Carbon::now()->addMonths($t['month'])->subDays(rand(1, 20));
            
            $order = Order::create([
                'user_id' => $customer->id,
                'name' => $customer->name,
                'contact' => '08123456789',
                'address' => 'Jl. Thrift No. ' . ($index + 1),
                'total_price' => $t['total'],
                'status' => $t['status'],
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Tambahkan item dummy ke tiap order
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => rand(1, 4),
                'size' => 'L',
                'price' => $t['total']
            ]);
        }
    }
}
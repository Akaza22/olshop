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
        // 1. Reset Database
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Product::truncate();
        ProductSize::truncate();
        Order::truncate();
        OrderItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Users
        User::create([
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

        // 3. Seed Products
        $productData = [
            [
                'name' => 'Coquette Pinkish',
                'category' => 'Tops',
                'price' => 85000,
                'img' => 'TOP 1.jpg',
                'tryon' => 'TOP 1.png', 
                'desc' => 'Beautiful pinkish coquette style top with delicate lace details.'
            ],
            [
                'name' => 'City of London Tank',
                'category' => 'Tops',
                'price' => 95000,
                'img' => 'TOP 2.png',
                'tryon' => 'TOP 2.png',
                'desc' => 'Graphic tank top featuring City of London print, edgy and stylish.'
            ],
            [
                'name' => 'Ribbon Soft Pink',
                'category' => 'Tops',
                'price' => 85000,
                'img' => 'TOP 3.jpg',
                'tryon' => 'TOP 3.png',
                'desc' => 'Soft pink tank top with ribbon accents, perfect for daily wear.'
            ],
            [
                'name' => 'Aqua Blue Lace',
                'category' => 'Tops',
                'price' => 85000,
                'img' => 'TOP 4.jpg',
                'tryon' => 'TOP 4.png',
                'desc' => 'Vibrant aqua blue top with white lace trim and coquette aesthetic.'
            ],
        ];

        foreach ($productData as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'category' => $data['category'],
                'description' => $data['desc'],
                'price' => $data['price'],
                'condition' => 'Excellent',
                'image' => $data['img'],
                'tryon_image' => $data['tryon'],
            ]);

            // SEAKARANG HANYA SEED UKURAN S DENGAN STOK 1
            ProductSize::create([
                'product_id' => $product->id,
                'size'       => 'S',
                'stock'      => 1,
            ]);
        }

        // 4. Seed Orders (Simulasi transaksi)
        $transactions = [
            ['month' => -1, 'status' => 'Sent', 'total' => 170000],
            ['month' => 0,  'status' => 'Sent', 'total' => 85000],
            ['month' => 0,  'status' => 'Pending', 'total' => 95000],
        ];

        foreach ($transactions as $index => $t) {
            $date = Carbon::now()->addMonths($t['month'])->subDays(rand(1, 15));
            
            $order = Order::create([
                'user_id' => $customer->id,
                'name' => $customer->name,
                'contact' => '08123456789',
                'address' => 'Jl. Coquette No. ' . ($index + 1),
                'total_price' => $t['total'],
                'status' => $t['status'],
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => rand(1, 4),
                'size' => 'S', // Disesuaikan karena hanya ada ukuran S
                'price' => $t['total']
            ]);
        }
    }
}
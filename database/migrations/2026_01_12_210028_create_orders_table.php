<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID User yang beli
            $table->string('name');      // Nama penerima
            $table->string('contact');   // Nomor WA/Telepon
            $table->text('address');     // Alamat lengkap
            $table->integer('total_price'); // Total harga belanja
            $table->string('status')->default('Pending'); // Status: Pending, Sent, Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

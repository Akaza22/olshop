<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    // Masukkan kolom agar bisa diisi (Mass Assignment)
    protected $fillable = ['product_id', 'size', 'stock'];

    // Relasi balik: Ukuran ini milik sebuah produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

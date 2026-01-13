<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Pastikan kolom condition sudah masuk fillable
    protected $fillable = ['name', 'description', 'price', 'condition', 'image', 'tryon_image'];

    // Relasi: Satu produk memiliki banyak ukuran
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}

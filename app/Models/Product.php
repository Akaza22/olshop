<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
    'name',
    'category', 
    'price',
    'condition',
    'description',
    'image',
    'tryon_image',
];

    // Relasi: Satu produk memiliki banyak ukuran
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}

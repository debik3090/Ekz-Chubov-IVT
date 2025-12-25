<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Поля, которые можно массово заполнять
    protected $fillable = [
        'name',
        'description'
    ];

    // Связь: одна категория имеет много товаров
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

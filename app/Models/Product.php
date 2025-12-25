<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category_id',
        'image'
    ];

    // Автоматическое приведение типов
    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Связь: товар принадлежит одной категории
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Связь: товар может быть во многих позициях заказов
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

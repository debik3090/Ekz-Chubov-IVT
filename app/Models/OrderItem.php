<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Связь: позиция принадлежит заказу
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Связь: позиция связана с товаром
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Связь: заказ принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь: заказ имеет много позиций
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

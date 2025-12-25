<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'uploaded_by'
    ];

    // Связь: файл загружен пользователем
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvImport extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'status',
        'rows_count',
        'error_message',
    ];

    public function rows()
    {
        return $this->hasMany(CsvRow::class);
    }
}
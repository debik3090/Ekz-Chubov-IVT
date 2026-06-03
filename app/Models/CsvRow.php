<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvRow extends Model
{
    protected $fillable = [
        'csv_import_id',
        'name',
        'email',
        'phone',
    ];

    public function import()
    {
        return $this->belongsTo(CsvImport::class, 'csv_import_id');
    }
}

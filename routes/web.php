<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CsvUploadController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Защищённые маршруты (требуют авторизации)
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    // Загрузка Excel
    Route::get('/upload', [App\Http\Controllers\ExcelUploadController::class, 'index'])->name('excel.upload');
    Route::post('/upload', [App\Http\Controllers\ExcelUploadController::class, 'upload'])->name('excel.store');
});

Route::get('/csv-upload', [CsvUploadController::class, 'index'])->name('csv.upload');
Route::post('/csv-upload', [CsvUploadController::class, 'upload'])->name('csv.store');
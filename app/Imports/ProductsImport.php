<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Создаёт модель из каждой строки Excel
     */
    public function model(array $row)
    {
        // Ищем или создаём категорию
        $category = Category::firstOrCreate(
            ['name' => $row['category'] ?? 'Без категории'],
            ['description' => 'Автоматически созданная категория']
        );

        return new Product([
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'price' => $row['price'],
            'quantity' => $row['quantity'] ?? 0,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Правила валидации
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Сообщения об ошибках
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Название товара обязательно',
            'price.required' => 'Цена обязательна',
            'price.numeric' => 'Цена должна быть числом',
        ];
    }
}

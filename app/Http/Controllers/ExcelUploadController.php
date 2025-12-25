<?php

namespace App\Http\Controllers;

use App\Models\ExcelFile;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelUploadController extends Controller
{
    public function index()
    {
        return view('excel.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        try {
            $file = $request->file('file');

            // Проверяем расширение
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['csv', 'xlsx', 'xls'];

            if (!in_array($extension, $allowedExtensions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверный формат файла. Разрешены: CSV, XLSX, XLS',
                ], 422);
            }

            \Log::info('Starting import', [
                'file' => $file->getClientOriginalName(),
                'extension' => $extension,
            ]);

            // Сохраняем файл
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('excel-uploads', $filename, 'public');

            // Импортируем данные
            $imported = $this->importFromExcel($file->getRealPath());

            \Log::info("Import completed. Imported: {$imported}");

            // Сохраняем информацию о файле в БД
            ExcelFile::create([
                'filename' => $filename,
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Файл успешно загружен! Импортировано товаров: {$imported}",
                'filename' => $filename,
            ]);

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обработке файла: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function importFromExcel($filePath)
    {
        // Загружаем файл
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Получаем все данные
        $rows = $worksheet->toArray();

        if (count($rows) < 2) {
            throw new \Exception('Файл пустой или содержит только заголовки');
        }

        // Первая строка - заголовки
        $headers = array_map('trim', $rows[0]);

        \Log::info('Headers:', $headers);

        // Создаём маппинг заголовков к индексам
        $nameIndex = array_search('name', $headers);
        $categoryIndex = array_search('category', $headers);
        $descriptionIndex = array_search('description', $headers);
        $priceIndex = array_search('price', $headers);
        $quantityIndex = array_search('quantity', $headers);

        if ($nameIndex === false || $priceIndex === false) {
            throw new \Exception('В файле отсутствуют обязательные столбцы: name, price');
        }

        $imported = 0;

        // Обрабатываем строки данных (пропускаем первую с заголовками)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            // Пропускаем пустые строки
            if (empty($row[$nameIndex]) || trim($row[$nameIndex]) === '') {
                continue;
            }

            try {
                // Категория
                $categoryName = ($categoryIndex !== false && !empty($row[$categoryIndex]))
                    ? trim($row[$categoryIndex])
                    : 'Без категории';

                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['description' => 'Автоматически созданная категория']
                );

                // Создаём товар
                Product::create([
                    'name' => trim($row[$nameIndex]),
                    'description' => ($descriptionIndex !== false && !empty($row[$descriptionIndex]))
                        ? trim($row[$descriptionIndex])
                        : null,
                    'price' => floatval($row[$priceIndex]),
                    'quantity' => ($quantityIndex !== false && !empty($row[$quantityIndex]))
                        ? intval($row[$quantityIndex])
                        : 0,
                    'category_id' => $category->id,
                ]);

                $imported++;

            } catch (\Exception $e) {
                \Log::error("Row {$i} error: " . $e->getMessage());
                continue;
            }
        }

        if ($imported === 0) {
            throw new \Exception('Не удалось импортировать ни одной записи');
        }

        return $imported;
    }
}

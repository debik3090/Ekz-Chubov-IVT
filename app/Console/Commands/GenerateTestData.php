<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateTestData extends Command
{
    // Название команды (будем вызывать: php artisan data:generate)
    protected $signature = 'data:generate {count=10 : Количество записей}';

    // Описание команды
    protected $description = 'Генерация тестовых данных для приложения';

    public function handle()
    {
        $count = $this->argument('count');

        $this->info("🚀 Генерация {$count} тестовых записей...");

        // 1. Создаём категории
        $this->info('📁 Создание категорий...');
        $categories = [
            ['name' => 'Электроника', 'description' => 'Телефоны, компьютеры, гаджеты'],
            ['name' => 'Одежда', 'description' => 'Мужская и женская одежда'],
            ['name' => 'Книги', 'description' => 'Художественная и техническая литература'],
            ['name' => 'Дом и сад', 'description' => 'Товары для дома и дачи'],
            ['name' => 'Спорт', 'description' => 'Спортивное оборудование и одежда'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }

        $this->info('✅ Создано ' . count($categories) . ' категорий');

        // 2. Создаём продукты
        $this->info('🛍️  Создание товаров...');
        $categoryIds = Category::pluck('id')->toArray();

        $productNames = [
            'Ноутбук', 'Смартфон', 'Наушники', 'Клавиатура', 'Мышь',
            'Футболка', 'Джинсы', 'Куртка', 'Кроссовки', 'Шапка',
            'Роман', 'Учебник', 'Справочник', 'Журнал', 'Комикс',
            'Стул', 'Стол', 'Лампа', 'Ваза', 'Подушка',
            'Мяч', 'Велосипед', 'Гантели', 'Коврик для йоги', 'Скакалка'
        ];

        for ($i = 0; $i < $count; $i++) {
            $name = $productNames[array_rand($productNames)] . ' ' . ($i + 1);

            Product::create([
                'name' => $name,
                'description' => 'Описание товара: ' . $name,
                'price' => rand(500, 50000) / 100, // от 5 до 500 руб
                'quantity' => rand(0, 100),
                'category_id' => $categoryIds[array_rand($categoryIds)],
            ]);
        }

        $this->info('✅ Создано ' . $count . ' товаров');

        // 3. Создаём тестового пользователя (если его нет)
        $this->info('👤 Создание тестового пользователя...');
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Тестовый пользователь',
                'password' => Hash::make('password'),
            ]
        );

        $this->info('✅ Пользователь: test@example.com (пароль: password)');

        // 4. Создаём заказы
        $this->info('🛒 Создание заказов...');
        $productIds = Product::pluck('id')->toArray();
        $orderCount = max(5, intval($count / 2)); // минимум 5 заказов

        for ($i = 0; $i < $orderCount; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => 0,
                'status' => ['pending', 'processing', 'completed'][array_rand(['pending', 'processing', 'completed'])],
            ]);

            // Добавляем позиции в заказ
            $totalPrice = 0;
            $itemCount = rand(1, 5); // от 1 до 5 товаров в заказе

            for ($j = 0; $j < $itemCount; $j++) {
                $product = Product::find($productIds[array_rand($productIds)]);
                if (!$product) continue;

                $quantity = rand(1, 3);
                $price = $product->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalPrice += $price;
            }

            // Обновляем общую стоимость заказа
            $order->update(['total_price' => $totalPrice]);
        }

        $this->info('✅ Создано ' . $orderCount . ' заказов');

        $this->info('🎉 Генерация завершена успешно!');
        $this->newLine();
        $this->info('📊 Статистика:');
        $this->table(
            ['Сущность', 'Количество'],
            [
                ['Категории', Category::count()],
                ['Товары', Product::count()],
                ['Пользователи', User::count()],
                ['Заказы', Order::count()],
                ['Позиции заказов', OrderItem::count()],
            ]
        );

        return 0;
    }
}


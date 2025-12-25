<?php

echo "Тест подключения к MySQL...\n\n";

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=lab_work_1',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Подключение успешно!\n";
    
    $result = $pdo->query('SELECT 1 as test');
    $row = $result->fetch(PDO::FETCH_ASSOC);
    
    echo "✅ Запрос выполнен: " . print_r($row, true) . "\n";
    
} catch (PDOException $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "Код ошибки: " . $e->getCode() . "\n";
}

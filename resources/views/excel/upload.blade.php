@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Загрузка товаров из Excel</h1>

            <div class="alert alert-info">
                <strong>Формат файла:</strong> Excel файл должен содержать следующие столбцы:
                <ul class="mb-0 mt-2">
                    <li><strong>name</strong> - Название товара (обязательно)</li>
                    <li><strong>category</strong> - Категория товара</li>
                    <li><strong>description</strong> - Описание товара</li>
                    <li><strong>price</strong> - Цена (обязательно)</li>
                    <li><strong>quantity</strong> - Количество</li>
                </ul>
            </div>

            <!-- Vue компонент для загрузки -->
            <excel-upload></excel-upload>

            <div class="mt-4">
                <h4>Пример структуры файла:</h4>
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>name</th>
                            <th>category</th>
                            <th>description</th>
                            <th>price</th>
                            <th>quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ноутбук HP</td>
                            <td>Электроника</td>
                            <td>Мощный ноутбук</td>
                            <td>45000</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Футболка</td>
                            <td>Одежда</td>
                            <td>Хлопковая футболка</td>
                            <td>1500</td>
                            <td>20</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

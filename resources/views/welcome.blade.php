<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №1</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/">ЛР №1</a>
                <div class="navbar-nav ms-auto">
                    @guest
                        <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                    @else
                        <a class="nav-link" href="{{ route('excel.upload') }}">Загрузка Excel</a>
                        <a class="nav-link" href="{{ route('products.index') }}">Товары</a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Выход</button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h1>Добро пожаловать!</h1>
            <p>Лабораторная работа №1 - Laravel + Vue.js</p>

            <!-- Тестовый Vue компонент -->
            <example-component></example-component>
        </div>
    </div>
</body>
</html>

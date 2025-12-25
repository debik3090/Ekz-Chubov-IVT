@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4 mb-4">Товары</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg">
                Добавить товар
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr style="font-size: 1.1rem;">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Название</th>
                                    <th class="px-4 py-3">Категория</th>
                                    <th class="px-4 py-3">Цена</th>
                                    <th class="px-4 py-3">Количество</th>
                                    <th class="px-4 py-3 text-center">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr style="font-size: 1.05rem;">
                                        <td class="px-4 py-3">{{ $product->id }}</td>
                                        <td class="px-4 py-3">
                                            <strong>{{ $product->name }}</strong>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-secondary px-3 py-2">
                                                {{ $product->category->name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <strong class="text-success">{{ number_format($product->price, 2) }} ₽</strong>
                                        </td>
                                        <td class="px-4 py-3">{{ $product->quantity }} шт.</td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('products.show', $product) }}" 
                                                   class="btn btn-info btn-sm px-3">
                                                    Просмотр
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" 
                                                   class="btn btn-warning btn-sm px-3">
                                                    Редактировать
                                                </a>
                                                <form action="{{ route('products.destroy', $product) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-sm px-3" 
                                                            onclick="return confirm('Удалить товар?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <h5 class="text-muted">Товары не найдены</h5>
                                            <p class="text-muted">Добавьте первый товар или загрузите Excel файл</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Улучшенная пагинация БЕЗ иконок -->
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Навигация по страницам">
                    <ul class="pagination pagination-lg">
                        {{-- Предыдущая страница --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link px-4">Назад</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link px-4" href="{{ $products->previousPageUrl() }}">
                                    Назад
                                </a>
                            </li>
                        @endif

                        {{-- Номера страниц --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="page-item active">
                                    <span class="page-link px-4">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link px-4" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Следующая страница --}}
                        @if ($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link px-4" href="{{ $products->nextPageUrl() }}">
                                    Вперёд
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link px-4">Вперёд</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>

            {{-- Информация о странице --}}
            <div class="text-center mt-3 text-muted">
                <p style="font-size: 1.1rem;">
                    Показано {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                    из {{ $products->total() }} товаров
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

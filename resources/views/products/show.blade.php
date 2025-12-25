@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h2 class="mb-0">{{ $product->name }}</h2>
                    <div>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-lg px-4">
                            Редактировать
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">
                            Назад к списку
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 400px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light text-center p-5 rounded shadow-sm" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-image text-muted mb-3" viewBox="0 0 16 16">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                        </svg>
                                        <p class="text-muted mb-0" style="font-size: 1.1rem;">Нет изображения</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="35%" class="px-4 py-3" style="font-size: 1.1rem;">ID:</th>
                                        <td class="px-4 py-3" style="font-size: 1.1rem;">{{ $product->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Категория:</th>
                                        <td class="px-4 py-3" style="font-size: 1.1rem;">
                                            <span class="badge bg-secondary px-3 py-2" style="font-size: 1rem;">
                                                {{ $product->category->name }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Цена:</th>
                                        <td class="px-4 py-3">
                                            <strong class="text-success" style="font-size: 1.3rem;">
                                                {{ number_format($product->price, 2) }} ₽
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Количество:</th>
                                        <td class="px-4 py-3" style="font-size: 1.1rem;">
                                            {{ $product->quantity }} шт.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Описание:</th>
                                        <td class="px-4 py-3" style="font-size: 1.05rem; line-height: 1.6;">
                                            {{ $product->description ?: 'Нет описания' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Создан:</th>
                                        <td class="px-4 py-3" style="font-size: 1.05rem;">
                                            {{ $product->created_at->format('d.m.Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3" style="font-size: 1.1rem;">Обновлён:</th>
                                        <td class="px-4 py-3" style="font-size: 1.05rem;">
                                            {{ $product->updated_at->format('d.m.Y H:i') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-4">
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-lg px-4" 
                                            onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">
                                        Удалить товар
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

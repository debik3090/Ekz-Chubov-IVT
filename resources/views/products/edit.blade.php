@extends('layouts.app')

@section('content')
<div class="row">
        <div class="col-md-10 offset-md-1">  {{-- Было col-md-8 offset-md-2 --}}
            <h1 class="display-5 mb-4">Добавить новый товар</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <h5 class="alert-heading">Ошибки валидации:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Название товара *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Категория *</label>
                    <select class="form-control @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Цена (₽) *</label>
                            <input type="number" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Количество *</label>
                            <input type="number" min="0"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    @if($product->image)
                        <div class="mb-2">
                            <label class="form-label">Текущее изображение:</label><br>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                 style="max-width: 200px; max-height: 200px;">
                        </div>
                    @endif

                    <label for="image" class="form-label">Изменить изображение</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Максимальный размер: 2 МБ</small>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Сохранить изменения</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

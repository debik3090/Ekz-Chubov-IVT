<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка CSV</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f5f5f5;
        }

        .success {
            background: #d4edda;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h1>Импорт CSV через очередь Laravel</h1>

@if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('csv.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="file" name="csv_file" required>

    <button type="submit">
        Загрузить CSV
    </button>
</form>

<h2>История импортов</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Файл</th>
            <th>Статус</th>
            <th>Строк</th>
            <th>Создан</th>
        </tr>
    </thead>
    <tbody>
        @foreach($imports as $import)
            <tr>
                <td>{{ $import->id }}</td>
                <td>{{ $import->file_name }}</td>
                <td>{{ $import->status }}</td>
                <td>{{ $import->rows_count }}</td>
                <td>{{ $import->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
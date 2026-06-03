<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsvJob;
use App\Models\CsvImport;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    public function index()
    {
        $imports = CsvImport::latest()->get();

        return view('csv-upload', compact('imports'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        $path = $file->store('csv');

        $import = CsvImport::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        ImportCsvJob::dispatch($import->id);

        return redirect()
            ->route('csv.upload')
            ->with('success', 'CSV-файл загружен. Обработка добавлена в очередь.');
    }
}
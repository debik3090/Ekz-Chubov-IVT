<?php

namespace App\Jobs;

use App\Models\CsvImport;
use App\Models\CsvRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportCsvJob implements ShouldQueue
{
    use Queueable;

    protected $importId;

    public function __construct($importId)
    {
        $this->importId = $importId;
    }

    public function handle(): void
    {
        $import = CsvImport::find($this->importId);

        if (!$import) {
            return;
        }

        $import->update([
            'status' => 'processing'
        ]);

        $path = storage_path('app/private/' . $import->file_path);

        if (!file_exists($path)) {
            $import->update([
                'status' => 'failed',
                'error_message' => 'Файл не найден'
            ]);

            return;
        }

        $handle = fopen($path, 'r');

        if (!$handle) {
            return;
        }

        $count = 0;

       fgetcsv($handle, 1000, ';');

		while (($row = fgetcsv($handle, 1000, ';')) !== false) {

            CsvRow::create([
                'csv_import_id' => $import->id,
                'name' => $row[0] ?? null,
                'email' => $row[1] ?? null,
                'phone' => $row[2] ?? null,
            ]);

            $count++;
        }

        fclose($handle);

        $import->update([
            'status' => 'completed',
            'rows_count' => $count
        ]);
    }
}
<?php

namespace App\Jobs;

use App\Models\Import;
use App\Pipes\SaveBook;
use App\Pipes\ValidateBookRow;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

class BookImport implements ShouldQueue
{
    use Queueable;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $history = Import::find($this->id);
        if (!$history) {
            return;
        }

        $history->update([
            'status' => 'processing'
        ]);

        $path = Storage::path($history->path);
        $reader = SimpleExcelReader::create($path);

        $headers = $reader->getHeaders();
        $expectedHeaders = ['name', 'isbn', 'author', 'stores'];
        if (count(array_diff($expectedHeaders, $headers)) > 0) {
            $history->update([
                'status' => 'failed'
            ]);
            return;
        }

        $rows = $reader->getRows();
        $history->update([
            'total_rows' => count($rows)
        ]);

        try {
            $reader->getRows()->chunk(500)->each(function ($chunk) {
                DB::transaction(function () use ($chunk) {
                    foreach ($chunk as $row) {
                        $data = [
                            'row' => $row,
                            'status' => 'pending',
                        ];
                        $result = app(Pipeline::class)
                            ->send($data)
                            ->through([
                                ValidateBookRow::class,
                                SaveBook::class,
                            ])
                            ->thenReturn();

                        if ($result['status'] !== 'success') {
                            throw new Exception('Failed!');
                        }
                    }
                });

            });
            $history->update([
                'status' => 'completed',
            ]);

        } catch (Throwable $e) {
            $history->update([
                'status' => 'failed'
            ]);
        }
    }
}

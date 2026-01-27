<?php

namespace App\Jobs;

use App\Models\Import;
use App\Pipes\CheckDuplicate;
use App\Pipes\SaveAuthor;
use App\Pipes\ValidateRow;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

class AuthorImport implements ShouldQueue
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
            'status' => 'processing',
        ]);

        $path = Storage::path($history->path);

        $reader = SimpleExcelReader::create($path);
        $headers = $reader->getHeaders();

        if (count($headers) !== 1 || $headers[0] !== 'name') {
            $history->update([
                'status' => 'failed',
            ]);
            return;
        }

        $rows = $reader->getRows();

        $history->update([
            'total_rows' => count($rows),
        ]);

        try {
            DB::transaction(function () use ($rows) {
                $rows->each(function (array $rowProperties) {
                    $data = [
                        'row' => $rowProperties,
                        'status' => 'pending',
                    ];

                    $result = app(Pipeline::class)
                        ->send($data)
                        ->through([
                            ValidateRow::class,
                            CheckDuplicate::class,
                            SaveAuthor::class,
                        ])
                        ->then(function ($data) {
                            return $data;
                        });

                    if ($result['status'] !== 'success') {
                        throw new Exception('Failed!');
                    }
                });
            });

            $history->update([
                'status' => 'completed',
            ]);

        } catch (Throwable $e) {
            $history->update([
                'status' => 'failed',
            ]);
        }
    }
}

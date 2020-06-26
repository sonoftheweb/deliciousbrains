<?php

namespace App\Listeners;

use App\Events\CsvFileUploaded;
use App\Events\UploadImportDone;
use App\Imports\ActivitiesImport;
use App\Models\AccountActivity;
use App\Models\AccountBalance;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessCsvFileUploaded implements ShouldQueue
{
    use InteractsWithQueue;

    public $delay = 2;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CsvFileUploaded $event
     * @return void
     */
    public function handle(CsvFileUploaded $event)
    {
        $importClass = new ActivitiesImport;

        Excel::import($importClass, storage_path('/app/public/csv/').$event->uploadData['path']);

        $rows = $importClass->data->toArray();

        unset($rows[0]);

        $rows = array_map(function ($row) use ($event) {
            return [
                'amount' => $row[1] * 100,
                'description' => $row[0],
                'activity_date' => $row[2],
                'user_id' => $event->uploadData['user']->id
            ];
        }, $rows);

        //$amountSums = array_sum(array_column($rows, 'amount'));

        DB::transaction(function () use ($rows) {
            AccountActivity::query()->insert($rows);
        }, 3);

        event(new UploadImportDone($event->uploadData['user']));
    }

    public function failed(CsvFileUploaded $event, $exception)
    {
        Log::error('Unable to import file ' . $event->uploadData['path']);
        dump($exception);
    }
}

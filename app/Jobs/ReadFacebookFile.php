<?php

namespace App\Jobs;

use App\Models\Facebook\File;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadFacebookFile implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileModel;
    /**
     * @var null
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileModel)
    {
        //
        $this->fileModel = $fileModel;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $this->fileModel->update(['status' => File::IN_PROGRESS_STATUS]);
        $file = new \SplFileObject(storage_path('facebook/' . $this->fileModel->file_path));
        if (!$this->fileModel->total_line) {
            $file->seek(PHP_INT_MAX);

// get total lines
            $totalLines = $file->key();

// move to first line
            $file->rewind();

            $this->fileModel->update(['total_line' => $totalLines]);
            $LineNum=0;
        }else{
            $LineNum = $this->fileModel->file_line;
            $file->seek($LineNum);
        }
        $data = [];
        $ids = [];
        for ($i = 0; $i < intval(config('env.FACEBOOK_IMPORT_COUNT')); $i++) {
            $raw = $this->fileModel->lineToArray($file->current());
            $id = $this->fileModel->getIdData($raw);
            $datum = $this->fileModel->mapUser($raw);
            if ((!sizeof($datum)) || (!$id))
                continue;
            $LineNum++;
//            $this->fileModel->update(['file_line' => $LineNum]);
            if ($file->eof()) {
                $this->fileModel->update(['status' => File::COMPLETE_STATUS]);
                break;
            }
            $file->next();
            $datum['created_at'] = Carbon::now();
            $datum['updated_at'] = Carbon::now();
            if (!isset($data[$id])) {
                $data[$id] = $datum;
                $ids[] = $id;
            }
        }
        $this->fileModel->update(['status' => File::IDLE_STATUS, 'file_line' => $LineNum]);
        $founded_ids = \App\Models\Facebook\User::whereIn('facebook_id', $ids)->pluck('facebook_id')->toArray();
        foreach ($founded_ids as $found) {
            unset($data[$found]);
        }

        $data = array_values($data);
        \Illuminate\Support\Facades\Log::info(sizeof($data));
//        if($this->command)
//            $this->command->info(sizeof($data));
        \App\Models\Facebook\User::insert($data);
    }
}

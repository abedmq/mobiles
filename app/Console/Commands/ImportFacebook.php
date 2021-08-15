<?php

namespace App\Console\Commands;

use App\Jobs\ReadFacebookFile;
use App\Models\Facebook\File;
use Carbon\Carbon;
use GPBMetadata\Google\Api\Log;
use Illuminate\Console\Command;

class ImportFacebook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileDb = File::canStartImport()->first();
        \Illuminate\Support\Facades\Log::info('start import');
        if ($fileDb) {
            \Illuminate\Support\Facades\Log::info('file founded :' . $fileDb->file_path);

            $file = new \SplFileObject(storage_path('facebook/' . $fileDb->file_path));

            if (!$fileDb->total_line) {

                $file->seek(PHP_INT_MAX);
// get total lines
                $totalLines = $file->key();
// move to first line
                $file->rewind();

                $fileDb->update(['total_line' => $totalLines]);
            }

            $LineNum = $fileDb->file_line;

            $file->seek($LineNum);
            \Illuminate\Support\Facades\Log::info('file line read  :' . $LineNum);
            try {
                $fileDb->update(['status' => File::IN_PROGRESS_STATUS]);

                $data = [];
                $ids = [];
                for ($j = 0; $j < intval(config('env.FACEBOOK_IMPORT_COUNT')); $j++) {
                    $LineNum++;
                    $raw = $fileDb->lineToArray($file->current());
                    $id = $fileDb->getIdData($raw);
                    $datum = $fileDb->mapUser($raw);
                    if ((!sizeof($datum)) || (!@$datum['mobile_local']))
                        continue;
//            $fileDb->update(['file_line' => $LineNum]);
                    if ($file->eof()) {
                        $fileDb->update(['status' => File::COMPLETE_STATUS]);
                        break;
                    }
                    $file->next();
                    $datum['created_at'] = Carbon::now();
                    $datum['updated_at'] = Carbon::now();
                    if (!isset($data[$id])) {
                        $data[$datum['mobile_local']] = $datum;
                        $ids[] = $datum['mobile_local'];
                    }
                }
                \Illuminate\Support\Facades\Log::info('lines read  :' . $LineNum);
                $founded_ids = \App\Models\Facebook\User::whereIn('mobile_local', $ids)->pluck('mobile_local')->toArray();
                \Illuminate\Support\Facades\Log::info('duplicated lines  :' . sizeof($founded_ids));
                foreach ($founded_ids as $found) {
                    if (isset($data[$found]))
                        unset($data[$found]);
                }

                $data = array_values($data);
                \Illuminate\Support\Facades\Log::info('last insert lines  :' . sizeof($data));

                \App\Models\Facebook\User::insert($data);
                $status = $fileDb->update(['file_line' => $LineNum, 'status' => File::IDLE_STATUS]);
                \Illuminate\Support\Facades\Log::info('import  :' . sizeof($data));
            } catch (\Exception $e) {
//                \Illuminate\Support\Facades\Log::info($e->getMessage(), is_array($raw)?$raw:[]);
                $fileDb->update(['status' => File::IDLE_STATUS]);

            }

        }
    }
}

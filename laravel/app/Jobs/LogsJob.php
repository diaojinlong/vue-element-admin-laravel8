<?php

namespace App\Jobs;

use App\Models\LogsModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 待插入数据
    public $insertData = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($insertData)
    {
        $this->insertData = $insertData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = new LogsModel();
        foreach ($this->insertData as $field => $value) {
            $model->{$field} = $value;
        }
        $model->save();
    }
}

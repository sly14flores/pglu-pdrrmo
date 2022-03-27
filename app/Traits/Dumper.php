<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Dumper
{

    public function dumpToSlack($message)
    {
        if (env('LOG_SLACK_DUMP_ENABLED',false)) {
            Log::channel('dump_debug')->debug($message);
        }
    }

}
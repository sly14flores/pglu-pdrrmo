<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Traits\IncidentHelpers;

class GenerateIncidentNo extends Command
{
    use IncidentHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:incident';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Incident number';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $no = $this->incidentNumber();

        $this->info($no);

        return 0;
    }
}

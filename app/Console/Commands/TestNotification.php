<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\TestNotification as TN;
use App\Models\User;

use Illuminate\Support\Facades\Notification;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test';

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
        $user = User::find('37c2f09b-938c-45f2-a9cc-edb91bed1e61');

        Notification::send($user, new TN());

        return 0;
    }
}

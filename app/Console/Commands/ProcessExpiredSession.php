<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExpiredSessionJob;
use App\Models\ClassSession;
use Illuminate\Console\Command;

class ProcessExpiredSession extends Command
{
   
    protected $signature = 'app:process-expired-session';

    protected $description = 'Command description';

    public function handle(): int
    {
        $sessions = ClassSession::where('is_active', true)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->get();

        foreach($sessions as $session) {
            ProcessExpiredSessionJob::dispatch($session->id);
        }

        return Command::SUCCESS;

    }
}

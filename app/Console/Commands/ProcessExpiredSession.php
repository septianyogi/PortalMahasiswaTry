<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExpiredSessionJob;
use App\Models\ClassSession;
use Illuminate\Console\Command;

class ProcessExpiredSession extends Command
{
    protected $signature = 'app:process-expired-session';
    protected $description = 'Process expired class sessions and dispatch jobs';

    public function handle(): int
    {
        $sessions = ClassSession::where('is_active', true)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now())
            ->get();

        if ($sessions->isEmpty()) {
            $this->info('No expired sessions found.');
            return Command::SUCCESS;
        }

        foreach ($sessions as $session) {
            ProcessExpiredSessionJob::dispatch($session->id);
            $this->info("Dispatched job for session ID: {$session->id}");
        }

        $this->info("Processed {$sessions->count()} expired session(s).");
        return Command::SUCCESS;
    }
}
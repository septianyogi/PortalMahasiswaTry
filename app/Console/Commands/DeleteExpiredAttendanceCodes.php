<?php

namespace App\Console\Commands;

use App\Models\Kelas;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteExpiredAttendanceCodes extends Command
{
    protected $signature = 'attendance:cleanup';
    protected $description = 'Delete expired attendance codes from the database';

    public function handle()
    {
        $expiredClasses = Kelas::whereNotNull('attendance')
            ->where('expires_at', '<', now())
            ->update([
                'attendance' => null,
                'expires_at' => null
            ]);

        $this->info("$expiredClasses attendance codes deleted.");
    }
}
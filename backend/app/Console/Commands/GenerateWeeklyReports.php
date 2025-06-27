<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class GenerateWeeklyReports extends Command
{
    protected $signature = 'reports:weekly';
    protected $description = 'Generate and send weekly reports to administrators';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Generating weekly reports...');

        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super-admin', 'organization-admin']);
        })->get();

        $count = 0;
        foreach ($admins as $admin) {
            $notificationService->sendWeeklyReport($admin);
            $count++;
        }

        $this->info("Weekly reports sent to {$count} administrators.");

        return Command::SUCCESS;
    }
}
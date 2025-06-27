<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dataset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyDatasetSubmitted(Dataset $dataset): void
    {
        try {
            // Notify reviewers and admins
            $reviewers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['reviewer', 'organization-admin', 'super-admin']);
            })->get();

            foreach ($reviewers as $reviewer) {
                // Send email notification
                Mail::send('emails.dataset-submitted', [
                    'dataset' => $dataset,
                    'reviewer' => $reviewer,
                ], function ($message) use ($reviewer, $dataset) {
                    $message->to($reviewer->email)
                           ->subject("Dataset Baru Menunggu Review: {$dataset->title}");
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send dataset submission notification', [
                'dataset_id' => $dataset->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyDatasetApproved(Dataset $dataset): void
    {
        try {
            // Notify dataset creator
            if ($dataset->creator) {
                Mail::send('emails.dataset-approved', [
                    'dataset' => $dataset,
                    'user' => $dataset->creator,
                ], function ($message) use ($dataset) {
                    $message->to($dataset->creator->email)
                           ->subject("Dataset Disetujui: {$dataset->title}");
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send dataset approval notification', [
                'dataset_id' => $dataset->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyDatasetRejected(Dataset $dataset, string $reason): void
    {
        try {
            // Notify dataset creator
            if ($dataset->creator) {
                Mail::send('emails.dataset-rejected', [
                    'dataset' => $dataset,
                    'user' => $dataset->creator,
                    'reason' => $reason,
                ], function ($message) use ($dataset) {
                    $message->to($dataset->creator->email)
                           ->subject("Dataset Ditolak: {$dataset->title}");
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send dataset rejection notification', [
                'dataset_id' => $dataset->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function notifyHighDownloadActivity(Dataset $dataset, int $downloadCount): void
    {
        try {
            // Notify dataset creator and organization admins
            $users = collect([$dataset->creator]);
            
            if ($dataset->organization) {
                $orgAdmins = $dataset->organization->users()
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'organization-admin');
                    })->get();
                
                $users = $users->merge($orgAdmins);
            }

            foreach ($users->unique('id') as $user) {
                Mail::send('emails.high-download-activity', [
                    'dataset' => $dataset,
                    'user' => $user,
                    'downloadCount' => $downloadCount,
                ], function ($message) use ($user, $dataset, $downloadCount) {
                    $message->to($user->email)
                           ->subject("Dataset Populer: {$dataset->title} - {$downloadCount} unduhan");
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send high download activity notification', [
                'dataset_id' => $dataset->id,
                'download_count' => $downloadCount,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendWeeklyReport(User $user): void
    {
        try {
            $stats = app(AnalyticsService::class)->getDashboardStats();
            
            Mail::send('emails.weekly-report', [
                'user' => $user,
                'stats' => $stats,
            ], function ($message) use ($user) {
                $message->to($user->email)
                       ->subject('Laporan Mingguan Portal Data Terbuka');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send weekly report', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
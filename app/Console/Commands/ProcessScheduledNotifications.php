<?php

namespace App\Console\Commands;

use App\Models\NotificationHistory;
use App\Models\ScheduledNotification;
use Illuminate\Console\Command;
use Throwable;

class ProcessScheduledNotifications extends Command
{
    protected $signature = 'notifications:process-scheduled';

    protected $description = 'Process scheduled desktop notifications';

    public function handle()
    {
        $notifications = ScheduledNotification::where(
            'is_processed',
            false
        )
            ->where(
                'scheduled_at',
                '<=',
                now()
            )
            ->orderBy('scheduled_at')
            ->get();

        if ($notifications->isEmpty()) {

            $this->info(
                'No scheduled notifications are ready.'
            );

            return Command::SUCCESS;
        }

        foreach ($notifications as $scheduled) {

            $this->info(
                "Processing: {$scheduled->title}"
            );

            $icon = public_path(
                $scheduled->icon ?: 'logo.png'
            );

            if (! file_exists($icon)) {
                $icon = public_path('logo.png');
            }

            $history = NotificationHistory::create([
                'title' => $scheduled->title,
                'message' => $scheduled->message,
                'type' => $scheduled->type,
                'icon' => basename($icon),
                'delay' => $scheduled->delay,
                'status' => 'sent',
                'source' => 'scheduled',
                'sent_at' => now(),
            ]);

            try {

                if ($scheduled->delay > 0) {
                    sleep($scheduled->delay);
                }

                $this->notify(
                    $scheduled->title,
                    $scheduled->message,
                    $icon
                );

                $history->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $scheduled->update([
                    'is_processed' => true,
                    'processed_at' => now(),
                ]);

                $this->info(
                    "Notification sent: {$scheduled->title}"
                );

            } catch (Throwable $e) {

                $history->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'sent_at' => now(),
                ]);

                $scheduled->update([
                    'is_processed' => true,
                    'processed_at' => now(),
                ]);

                $this->error(
                    "Failed: {$e->getMessage()}"
                );
            }
        }

        return Command::SUCCESS;
    }
}
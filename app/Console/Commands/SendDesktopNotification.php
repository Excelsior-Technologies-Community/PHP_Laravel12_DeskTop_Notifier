<?php

namespace App\Console\Commands;

use App\Models\NotificationHistory;
use Illuminate\Console\Command;
use Throwable;

class SendDesktopNotification extends Command
{
    protected $signature = 'notify:desktop
                            {title? : Notification Title}
                            {message? : Notification Message}
                            {--type=info : success|warning|error|info}
                            {--delay=3 : Delay in seconds}';

    protected $description = 'Send Desktop Notification with Custom Options';

    public function handle()
    {
        $title = $this->argument('title')
            ?? 'Laravel Desktop Notifier';

        $message = $this->argument('message')
            ?? 'Your Laravel command finished successfully!';

        $type = strtolower($this->option('type'));

        if (! in_array($type, [
            'success',
            'warning',
            'error',
            'info'
        ])) {
            $type = 'info';
        }

        $delay = max(0, (int) $this->option('delay'));

        $icon = match ($type) {
            'success' => public_path('success.png'),
            'warning' => public_path('warning.png'),
            'error' => public_path('error.png'),
            default => public_path('logo.png'),
        };

        if (! file_exists($icon)) {
            $icon = public_path('logo.png');
        }

        $history = NotificationHistory::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => basename($icon),
            'delay' => $delay,
            'status' => 'sent',
            'source' => 'artisan',
            'sent_at' => now(),
        ]);

        try {

            $this->info("Starting Process...");
            $this->info("Notification Type : {$type}");
            $this->info("Waiting {$delay} second(s)...");

            if ($delay > 0) {
                sleep($delay);
            }

            $this->info("Process Completed!");

            $this->notify(
                $title,
                $message,
                $icon
            );

            $history->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $this->info("Desktop Notification Sent Successfully!");

            return Command::SUCCESS;

        } catch (Throwable $e) {

            $history->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => now(),
            ]);

            $this->error(
                "Desktop Notification Failed: "
                . $e->getMessage()
            );

            return Command::FAILURE;
        }
    }
}
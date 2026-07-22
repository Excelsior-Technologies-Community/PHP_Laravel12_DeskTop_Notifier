<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $title = $this->argument('title') ?? 'Laravel Desktop Notifier';
        $message = $this->argument('message') ?? 'Your Laravel command finished successfully!';
        $type = strtolower($this->option('type'));

        if (! in_array($type, ['success', 'warning', 'error', 'info'])) {
            $type = 'info';
        }
        $delay = (int) $this->option('delay');

        $this->info("Starting Process...");
        $this->info("Notification Type : {$type}");
        $this->info("Waiting {$delay} second(s)...");

        sleep($delay);

        switch ($type) {
            case 'success':
                $icon = public_path('success.png');
                break;

            case 'warning':
                $icon = public_path('warning.png');
                break;

            case 'error':
                $icon = public_path('error.png');
                break;

            default:
                $icon = public_path('logo.png');
                break;
        }

        if (! file_exists($icon)) {
            $icon = public_path('logo.png');
        }

        $this->info("Process Completed!");

        $this->notify(
            $title,
            $message,
            $icon
        );

        return Command::SUCCESS;
    }
}

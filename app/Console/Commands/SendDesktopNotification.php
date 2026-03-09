<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendDesktopNotification extends Command
{
    protected $signature = 'notify:desktop';

    protected $description = 'Send Desktop Notification with Icon';

    public function handle()
    {
        $this->info("Starting Process...");

        // simulate long task
        sleep(3);

        $this->info("Process Completed!");

        // Notification with Icon
        $this->notify(
            'Laravel Desktop Notifier',
            'Your Laravel command finished successfully!',
            public_path('logo.png')
        );

        return Command::SUCCESS;
    }
}
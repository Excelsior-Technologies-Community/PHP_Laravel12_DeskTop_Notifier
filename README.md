# PHP_Laravel12_DeskTop_Notifier

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Package](https://img.shields.io/badge/Package-laravel--desktop--notifier-green)


---

# Overview

During development, many Laravel commands take time to complete. This project integrates the **Laravel Desktop Notifier package** to automatically send a **Windows desktop notification** after a command finishes.

The notification includes:

* Notification Title
* Notification Message
* Optional Custom Icon

Internally the package uses:

* **JoliNotif**
* **SnoreToast (Windows Notification Tool)**

---

# Features

* Works with **Laravel 12**
* Sends **desktop notifications from Artisan commands**
* Supports **custom notification icons**
* Improves developer workflow
* Easy integration with existing Laravel projects
* Useful for long-running commands


---

#  Folder Structure

```
PHP_Laravel12_DeskTop_Notifier
│
├── app
│   └── Console
│       └── Commands
│           └── SendDesktopNotification.php
│
├── public
│   └── logo.png
│
├── routes
│   └── web.php
│
├── resources
├── storage
├── vendor
│
├── composer.json
└── artisan
```

---

# Requirements

Before installing the project, ensure your system has:

* PHP **8.2+**
* Composer
* Laravel **12**
* Windows OS
* XAMPP / Laravel Herd / Local development environment

---

# Step 1 — Install Laravel 12

Open your terminal and run:

```
composer create-project laravel/laravel PHP_Laravel12_DeskTop_Notifier
```

Check the installation:

```
php artisan serve
```

Open the browser:

```
http://127.0.0.1:8000
```

If the Laravel welcome page appears, the installation is successful.

## .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

# Step 2 — Install Desktop Notifier Package

Install the notification package using Composer:

```
composer require nunomaduro/laravel-desktop-notifier
```

This package allows Laravel commands to use the **notify() method** to trigger desktop notifications.

Internally it uses:

* JoliNotif
* SnoreToast (Windows Notification Tool)

---

# Step 3 — Create an Artisan Command

Generate a new command:

```
php artisan make:command SendDesktopNotification
```

Laravel will create the file:

```
app/Console/Commands/SendDesktopNotification.php
```

---

# Step 4 — Add Command Code

Open the file:

```
app/Console/Commands/SendDesktopNotification.php
```

Replace the code with the following:

```php
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

        // simulate long running task
        sleep(3);

        $this->info("Process Completed!");

        // send notification with icon
        $this->notify(
            'Laravel Desktop Notifier',
            'Your Laravel command finished successfully!',
            public_path('logo.png')
        );

        return Command::SUCCESS;
    }
}
```

This command will:

1. Start a task
2. Wait 3 seconds
3. Finish the task
4. Send a desktop notification with an icon

---

# Step 5 — Add Notification Icon

Place an icon image inside the **public folder**.

Example:

```
public/logo.png
```
<img width="194" height="117" alt="Screenshot 2026-03-09 160112" src="https://github.com/user-attachments/assets/7821935f-3b5d-474c-9614-1eac182e9f1e" />

---

# Step 6 — Verify Command Registration

Run the following command:

```
php artisan list
```

You should see:

<img width="490" height="37" alt="Screenshot 2026-03-09 160618" src="https://github.com/user-attachments/assets/92908c2b-8fde-4ce4-95dc-019158b33f76" />

---
Laravel 11 and Laravel 12 automatically detect commands inside:

```
app/Console/Commands
```

So **Kernel.php is not required**.

---

# Step 7 — Run the Command

Execute the command:

```
php artisan notify:desktop
```

Terminal output:

```
Starting Process...
Process Completed!
```

After execution, a **Windows desktop notification popup will appear with the icon**.

<img width="1673" height="321" alt="Screenshot 2026-03-09 152621" src="https://github.com/user-attachments/assets/d252eafd-d1fc-46c5-b070-eb46b2c7f01f" />


---

# Advantages

* No need to watch the terminal constantly
* Useful for long-running commands
* Improves development workflow
* Easy to integrate into any Laravel project

---


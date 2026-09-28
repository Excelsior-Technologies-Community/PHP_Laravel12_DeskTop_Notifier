<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Send Desktop Notification
|--------------------------------------------------------------------------
*/

Route::get('/notify', function (Request $request) {

    Artisan::call('notify:desktop', [

        'title' => $request->input(
            'title',
            'Laravel Desktop Notifier'
        ),

        'message' => $request->input(
            'message',
            'Notification Triggered From Browser'
        ),

        '--type' => $request->input(
            'type',
            'info'
        ),

        '--delay' => (int) $request->input(
            'delay',
            3
        ),

    ]);

    return redirect('/')
        ->with(
            'success',
            'Desktop Notification Sent Successfully!'
        );

})->name('notify');

/*
|--------------------------------------------------------------------------
| Notification Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/notifications/dashboard',
    [NotificationController::class, 'dashboard']
)->name('notifications.dashboard');

/*
|--------------------------------------------------------------------------
| Schedule Notification
|--------------------------------------------------------------------------
*/

Route::post(
    '/notifications/schedule',
    [NotificationController::class, 'schedule']
)->name('notifications.schedule');

/*
|--------------------------------------------------------------------------
| Delete Scheduled Notification
|--------------------------------------------------------------------------
*/

Route::delete(
    '/notifications/scheduled/{scheduledNotification}',
    [NotificationController::class, 'destroyScheduled']
)->name('notifications.scheduled.destroy');
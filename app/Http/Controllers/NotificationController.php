<?php

namespace App\Http\Controllers;

use App\Models\NotificationHistory;
use App\Models\ScheduledNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Notification statistics and history dashboard.
     */
    public function dashboard(Request $request)
    {
        $query = NotificationHistory::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Type filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        /*
        |--------------------------------------------------------------------------
        | Date filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'sent_at',
                '>=',
                $request->input('from_date')
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'sent_at',
                '<=',
                $request->input('to_date')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalNotifications = NotificationHistory::count();

        $successfulNotifications = NotificationHistory::where(
            'status',
            'sent'
        )->count();

        $failedNotifications = NotificationHistory::where(
            'status',
            'failed'
        )->count();

        $todayNotifications = NotificationHistory::whereDate(
            'sent_at',
            today()
        )->count();

        $successCount = NotificationHistory::where(
            'type',
            'success'
        )->count();

        $warningCount = NotificationHistory::where(
            'type',
            'warning'
        )->count();

        $errorCount = NotificationHistory::where(
            'type',
            'error'
        )->count();

        $infoCount = NotificationHistory::where(
            'type',
            'info'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Filtered history
        |--------------------------------------------------------------------------
        */

        $notifications = $query
            ->latest('sent_at')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Scheduled notifications
        |--------------------------------------------------------------------------
        */

        $scheduledNotifications = ScheduledNotification::where(
            'is_processed',
            false
        )
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        return view('notifications.dashboard', compact(
            'totalNotifications',
            'successfulNotifications',
            'failedNotifications',
            'todayNotifications',
            'successCount',
            'warningCount',
            'errorCount',
            'infoCount',
            'notifications',
            'scheduledNotifications'
        ));
    }

    /**
     * Create scheduled notification.
     */
    public function schedule(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                'in:success,warning,error,info',
            ],

            'delay' => [
                'required',
                'integer',
                'min:0',
                'max:60',
            ],

            'scheduled_at' => [
                'required',
                'date',
                'after:now',
            ],
        ]);

        $icon = match ($validated['type']) {
            'success' => 'success.png',
            'warning' => 'warning.png',
            'error' => 'error.png',
            default => 'logo.png',
        };

        ScheduledNotification::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'icon' => $icon,
            'delay' => $validated['delay'],
            'scheduled_at' => $validated['scheduled_at'],
            'is_processed' => false,
        ]);

        return redirect()
            ->route('notifications.dashboard')
            ->with(
                'success',
                'Desktop notification scheduled successfully!'
            );
    }

    /**
     * Delete a scheduled notification.
     */
    public function destroyScheduled(
        ScheduledNotification $scheduledNotification
    ) {
        $scheduledNotification->delete();

        return redirect()
            ->route('notifications.dashboard')
            ->with(
                'success',
                'Scheduled notification deleted successfully!'
            );
    }
}
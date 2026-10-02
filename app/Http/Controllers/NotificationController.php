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

    /*
    |--------------------------------------------------------------------------
    | Interactive Desktop Notification Studio & Live Sound/Icon Tester View
    |--------------------------------------------------------------------------
    */
    public function studio(Request $request)
    {
        $soundTones = [
            'gentle_chime' => 'Gentle Chime (Soft Ring)',
            'digital_bell' => 'Digital Bell (Crisp Notification)',
            'soft_whistle' => 'Soft Whistle (Subtle Alert)',
            'emergency_siren' => 'Emergency Siren (Loud High Priority)',
            'ping' => 'Ping (Default Light Sound)',
        ];

        $recentNotifications = NotificationHistory::latest()->take(10)->get();

        return view('notifications.studio', compact('soundTones', 'recentNotifications'));
    }

    public function triggerStudioNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:success,warning,error,info',
            'sound_tone' => 'nullable|string',
            'priority' => 'nullable|in:low,normal,high,urgent,critical',
            'icon' => 'nullable|string',
            'icon_url' => 'nullable|string',
            'action_url' => 'nullable|url',
        ]);

        $type = $validated['type'];
        $title = $validated['title'];
        $message = $validated['message'];
        $soundTone = $validated['sound_tone'] ?? 'gentle_chime';
        $priority = $validated['priority'] ?? 'normal';
        $icon = ($validated['icon'] ?? ($validated['icon_url'] ?? null)) ?: match ($type) {
            'success' => 'https://cdn-icons-png.flaticon.com/512/190/190411.png',
            'warning' => 'https://cdn-icons-png.flaticon.com/512/595/595067.png',
            'error' => 'https://cdn-icons-png.flaticon.com/512/753/753345.png',
            default => 'https://cdn-icons-png.flaticon.com/512/471/471662.png',
        };

        try {
            \Illuminate\Support\Facades\Artisan::call('notify:desktop', [
                'title' => $title,
                'message' => $message,
                '--type' => $type,
            ]);
        } catch (\Throwable $e) {
            // Silence if environment does not support native popup
        }

        NotificationHistory::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'sound_tone' => $soundTone,
            'priority' => $priority,
            'action_url' => $validated['action_url'] ?? null,
            'delay' => 0,
            'status' => 'sent',
            'source' => 'desktop_studio',
            'sent_at' => now(),
        ]);

        return redirect()->route('notifications.studio')->with('success', "🔔 OS Desktop Notification '{$title}' triggered with {$soundTone} sound tone!");
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Delivery Analytics & Interaction Dashboard View
    |--------------------------------------------------------------------------
    */
    public function analytics(Request $request)
    {
        $totalSent = NotificationHistory::where('status', 'sent')->count();
        $totalFailed = NotificationHistory::where('status', 'failed')->count();
        $totalAll = NotificationHistory::count();

        $totalDelivered = $totalSent;
        $deliveryRate = $totalAll > 0 ? round(($totalSent / $totalAll) * 100, 1) : 100.0;

        $totalClicked = NotificationHistory::where('is_clicked', true)->count();
        $ctrRate = $totalSent > 0 ? round(($totalClicked / $totalSent) * 100, 1) : 0;
        $clickThroughRate = $ctrRate;
        $mutedCount = max(0, $totalSent - $totalClicked);

        $categoryBreakdown = [
            'info' => NotificationHistory::where('type', 'info')->count(),
            'success' => NotificationHistory::where('type', 'success')->count(),
            'warning' => NotificationHistory::where('type', 'warning')->count(),
            'error' => NotificationHistory::where('type', 'error')->count(),
        ];
        $typeBreakdown = $categoryBreakdown;

        $soundBreakdown = [
            'gentle_chime' => NotificationHistory::where('sound_tone', 'gentle_chime')->count(),
            'digital_bell' => NotificationHistory::where('sound_tone', 'digital_bell')->count(),
            'soft_whistle' => NotificationHistory::where('sound_tone', 'soft_whistle')->count(),
            'emergency_siren' => NotificationHistory::where('sound_tone', 'emergency_siren')->count(),
            'ping' => NotificationHistory::where('sound_tone', 'ping')->orWhereNull('sound_tone')->count(),
        ];

        $logs = NotificationHistory::latest()->paginate(10);
        $recentHistories = $logs;

        return view('notifications.analytics', compact(
            'totalSent',
            'totalFailed',
            'totalDelivered',
            'deliveryRate',
            'totalClicked',
            'ctrRate',
            'clickThroughRate',
            'mutedCount',
            'categoryBreakdown',
            'typeBreakdown',
            'soundBreakdown',
            'logs',
            'recentHistories'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Record Click Interaction Tracking
    |--------------------------------------------------------------------------
    */
    public function recordClick(NotificationHistory $notificationHistory)
    {
        $notificationHistory->update([
            'is_clicked' => true,
            'clicked_at' => now(),
        ]);

        if ($notificationHistory->action_url) {
            return redirect()->away($notificationHistory->action_url);
        }

        return redirect()->route('notifications.analytics')->with('success', "🎯 Interaction recorded for notification #{$notificationHistory->id}!");
    }
}
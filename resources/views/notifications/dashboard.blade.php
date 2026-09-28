<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Notification Dashboard
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f1f5f9;
            font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(
                135deg,
                #2563eb,
                #7c3aed
            );

            color: white;

            border-radius: 20px;

            padding: 35px;

            margin-bottom: 25px;
        }

        .stat-card {
            border: 0;

            border-radius: 16px;

            padding: 25px;

            color: white;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, .08);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        .card {
            border: 0;
            border-radius: 16px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, .06);
        }

        .table {
            vertical-align: middle;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 20px;
        }

        .type-success {
            background: #dcfce7;
            color: #166534;
        }

        .type-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .type-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-info {
            background: #dbeafe;
            color: #1e40af;
        }

    </style>

</head>

<body>

<div class="container py-5">

    <!-- HEADER -->

    <div class="dashboard-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h1 class="fw-bold mb-2">

                    <i class="bi bi-bell-fill"></i>

                    Notification Dashboard

                </h1>

                <p class="mb-0">

                    Monitor desktop notification activity,
                    statistics and scheduled notifications.

                </p>

            </div>

            <div class="mt-3 mt-md-0">

                <a
                    href="{{ url('/') }}"
                    class="btn btn-light"
                >

                    <i class="bi bi-house"></i>

                    Notification Center

                </a>

            </div>

        </div>

    </div>

    <!-- SUCCESS MESSAGE -->

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    <!-- STATISTICS -->

    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3">

            <div class="stat-card bg-primary">

                <i class="bi bi-bell-fill fs-2"></i>

                <div class="stat-number">

                    {{ $totalNotifications }}

                </div>

                <div>
                    Total Notifications
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="stat-card bg-success">

                <i class="bi bi-check-circle-fill fs-2"></i>

                <div class="stat-number">

                    {{ $successfulNotifications }}

                </div>

                <div>
                    Successful
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="stat-card bg-danger">

                <i class="bi bi-x-circle-fill fs-2"></i>

                <div class="stat-number">

                    {{ $failedNotifications }}

                </div>

                <div>
                    Failed
                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="stat-card bg-warning text-dark">

                <i class="bi bi-calendar-day-fill fs-2"></i>

                <div class="stat-number">

                    {{ $todayNotifications }}

                </div>

                <div>
                    Today's Notifications
                </div>

            </div>

        </div>

    </div>

    <!-- TYPE STATISTICS -->

    <div class="card mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                <i class="bi bi-bar-chart-fill"></i>

                Notification Type Statistics

            </h5>

            <div class="row text-center">

                <div class="col-md-3 mb-3">

                    <div class="p-3 rounded bg-success bg-opacity-10">

                        <h3 class="text-success">
                            {{ $successCount }}
                        </h3>

                        <strong>
                            Success
                        </strong>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="p-3 rounded bg-warning bg-opacity-10">

                        <h3 class="text-warning">
                            {{ $warningCount }}
                        </h3>

                        <strong>
                            Warning
                        </strong>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="p-3 rounded bg-danger bg-opacity-10">

                        <h3 class="text-danger">
                            {{ $errorCount }}
                        </h3>

                        <strong>
                            Error
                        </strong>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="p-3 rounded bg-primary bg-opacity-10">

                        <h3 class="text-primary">
                            {{ $infoCount }}
                        </h3>

                        <strong>
                            Info
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- SEARCH AND FILTER -->

    <div class="card mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">

                <i class="bi bi-search"></i>

                Search & Filter Notifications

            </h5>

            <form
                method="GET"
                action="{{ route('notifications.dashboard') }}"
            >

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search title or message..."
                            value="{{ request('search') }}"
                        >

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Type
                        </label>

                        <select
                            name="type"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            <option
                                value="success"
                                {{ request('type') == 'success' ? 'selected' : '' }}
                            >
                                Success
                            </option>

                            <option
                                value="warning"
                                {{ request('type') == 'warning' ? 'selected' : '' }}
                            >
                                Warning
                            </option>

                            <option
                                value="error"
                                {{ request('type') == 'error' ? 'selected' : '' }}
                            >
                                Error
                            </option>

                            <option
                                value="info"
                                {{ request('type') == 'info' ? 'selected' : '' }}
                            >
                                Info
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-grid w-100">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-search"></i>

                                Filter

                            </button>

                        </div>

                    </div>

                </div>

                <div class="mt-3">

                    <a
                        href="{{ route('notifications.dashboard') }}"
                        class="btn btn-outline-secondary btn-sm"
                    >

                        <i class="bi bi-arrow-clockwise"></i>

                        Reset Filters

                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- HISTORY -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                <i class="bi bi-clock-history"></i>

                Notification History

            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Title</th>

                            <th>Message</th>

                            <th>Type</th>

                            <th>Source</th>

                            <th>Status</th>

                            <th>Delay</th>

                            <th>Sent At</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($notifications as $notification)

                        <tr>

                            <td>
                                {{ $notification->id }}
                            </td>

                            <td>

                                <strong>
                                    {{ $notification->title }}
                                </strong>

                            </td>

                            <td>

                                <span
                                    title="{{ $notification->message }}"
                                >

                                    {{ \Illuminate\Support\Str::limit(
                                        $notification->message,
                                        45
                                    ) }}

                                </span>

                            </td>

                            <td>

                                <span
                                    class="badge type-{{ $notification->type }}"
                                >

                                    {{ ucfirst($notification->type) }}

                                </span>

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ ucfirst($notification->source) }}

                                </span>

                            </td>

                            <td>

                                @if($notification->status === 'sent')

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle"></i>

                                        Sent

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        <i class="bi bi-x-circle"></i>

                                        Failed

                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $notification->delay }} sec
                            </td>

                            <td>

                                {{ $notification->sent_at
                                    ? $notification->sent_at->format('d M Y, h:i A')
                                    : '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <i
                                    class="bi bi-bell-slash fs-1 text-muted"
                                ></i>

                                <p class="mt-3 mb-0 text-muted">

                                    No notification history found.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($notifications->hasPages())

            <div class="card-footer">

                {{ $notifications->links('pagination::bootstrap-5') }}

            </div>

        @endif

    </div>

    <!-- SCHEDULED NOTIFICATIONS -->

    <div class="card mb-4">

        <div class="card-header bg-warning">

            <h5 class="mb-0">

                <i class="bi bi-calendar-event"></i>

                Upcoming Scheduled Notifications

            </h5>

        </div>

        <div class="card-body">

            @if($scheduledNotifications->count())

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead>

                            <tr>

                                <th>Title</th>
                                <th>Type</th>
                                <th>Scheduled At</th>
                                <th>Delay</th>
                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($scheduledNotifications as $scheduled)

                            <tr>

                                <td>

                                    <strong>
                                        {{ $scheduled->title }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ \Illuminate\Support\Str::limit(
                                            $scheduled->message,
                                            60
                                        ) }}

                                    </small>

                                </td>

                                <td>

                                    <span
                                        class="badge type-{{ $scheduled->type }}"
                                    >

                                        {{ ucfirst($scheduled->type) }}

                                    </span>

                                </td>

                                <td>

                                    {{ $scheduled->scheduled_at
                                        ->format('d M Y, h:i A') }}

                                </td>

                                <td>

                                    {{ $scheduled->delay }} sec

                                </td>

                                <td>

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'notifications.scheduled.destroy',
                                            $scheduled
                                        ) }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this scheduled notification?')"
                                        >

                                            <i class="bi bi-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-4">

                    <i class="bi bi-calendar-x fs-1"></i>

                    <p class="mt-2 mb-0">

                        No upcoming scheduled notifications.

                    </p>

                </div>

            @endif

        </div>

    </div>

    <!-- SCHEDULE FORM -->

    <div class="card">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="bi bi-calendar-plus"></i>

                Schedule Desktop Notification

            </h5>

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('notifications.schedule') }}"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Notification Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Example: Backup Reminder"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Notification Type
                        </label>

                        <select
                            name="type"
                            class="form-select"
                            required
                        >

                            <option value="info">
                                Info
                            </option>

                            <option value="success">
                                Success
                            </option>

                            <option value="warning">
                                Warning
                            </option>

                            <option value="error">
                                Error
                            </option>

                        </select>

                    </div>

                    <div class="col-12">

                        <label class="form-label fw-bold">
                            Notification Message
                        </label>

                        <textarea
                            name="message"
                            rows="3"
                            class="form-control"
                            placeholder="Enter notification message..."
                            required
                        ></textarea>

                    </div>

                    <div class="col-md-5">

                        <label class="form-label fw-bold">
                            Schedule Date & Time
                        </label>

                        <input
                            type="datetime-local"
                            name="scheduled_at"
                            class="form-control"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            required
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Delay
                        </label>

                        <select
                            name="delay"
                            class="form-select"
                        >

                            <option value="0">
                                No Delay
                            </option>

                            <option value="1">
                                1 Second
                            </option>

                            <option value="2">
                                2 Seconds
                            </option>

                            <option value="3">
                                3 Seconds
                            </option>

                            <option value="5">
                                5 Seconds
                            </option>

                            <option value="10">
                                10 Seconds
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="bi bi-calendar-check"></i>

                            Schedule Notification

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Delivery Analytics & Click Radar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }

        .analytics-header {
            background: linear-gradient(135deg, #0284c7, #2563eb, #4f46e5);
            color: white;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.2);
        }

        .card-custom {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }

        .kpi-card {
            border-radius: 16px;
            padding: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .kpi-card .kpi-val {
            font-size: 36px;
            font-weight: 800;
        }

        .kpi-card .kpi-sub {
            font-size: 13px;
            opacity: 0.85;
        }

        .kpi-icon {
            position: absolute;
            right: 15px;
            bottom: 10px;
            font-size: 60px;
            opacity: 0.15;
        }

        .bg-gradient-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        .bg-gradient-green { background: linear-gradient(135deg, #16a34a, #15803d); }
        .bg-gradient-purple { background: linear-gradient(135deg, #9333ea, #7e22ce); }
        .bg-gradient-amber { background: linear-gradient(135deg, #d97706, #b45309); }

        .progress {
            height: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- HEADER & NAV -->
    <div class="analytics-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-2">
                    <i class="bi bi-bar-chart-line-fill text-warning"></i> Delivery Analytics & CTR Radar
                </h1>
                <p class="mb-0 text-light opacity-75">
                    Real-time notification delivery logs, sound tone breakdown, and click-through interaction tracking.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('notifications.dashboard') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('notifications.studio') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-sliders"></i> Studio
                </a>
                <a href="{{ url('/') }}" class="btn btn-warning rounded-pill px-4 fw-bold">
                    <i class="bi bi-bell-fill"></i> Trigger Center
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- KPI STAT CARDS -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card bg-gradient-blue shadow-sm">
                <span class="text-uppercase small fw-bold tracking-wider d-block mb-1">Total Notifications Sent</span>
                <div class="kpi-val">{{ number_format($totalSent) }}</div>
                <div class="kpi-sub mt-1">Dispatched via Web & Desktop API</div>
                <i class="bi bi-send-fill kpi-icon"></i>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card bg-gradient-green shadow-sm">
                <span class="text-uppercase small fw-bold tracking-wider d-block mb-1">Delivery Success Rate</span>
                <div class="kpi-val">{{ number_format($deliveryRate, 1) }}%</div>
                <div class="kpi-sub mt-1">{{ number_format($totalDelivered) }} / {{ number_format($totalSent) }} Toast Delivered</div>
                <i class="bi bi-check-circle-fill kpi-icon"></i>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card bg-gradient-purple shadow-sm">
                <span class="text-uppercase small fw-bold tracking-wider d-block mb-1">Click-Through Rate (CTR)</span>
                <div class="kpi-val">{{ number_format($ctrRate, 1) }}%</div>
                <div class="kpi-sub mt-1">{{ number_format($totalClicked) }} User Action Clicks</div>
                <i class="bi bi-cursor-fill kpi-icon"></i>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card bg-gradient-amber shadow-sm">
                <span class="text-uppercase small fw-bold tracking-wider d-block mb-1">Muted / Dismissed</span>
                <div class="kpi-val">{{ number_format($mutedCount) }}</div>
                <div class="kpi-sub mt-1">Notifications without interaction</div>
                <i class="bi bi-bell-slash-fill kpi-icon"></i>
            </div>
        </div>
    </div>

    <!-- BREAKDOWNS SECTION -->
    <div class="row g-4 mb-4">
        <!-- CATEGORY DISTRIBUTION -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-pie-chart-fill me-2 text-primary"></i> Event Type Category Breakdown
                </h5>
                <p class="text-muted small mb-4">Distribution of desktop alerts by urgency & channel type.</p>

                @php
                    $categories = [
                        'info' => ['name' => 'Information Alerts', 'color' => 'bg-info', 'count' => $categoryBreakdown['info'] ?? 0],
                        'success' => ['name' => 'Success Confirmation', 'color' => 'bg-success', 'count' => $categoryBreakdown['success'] ?? 0],
                        'warning' => ['name' => 'Warning Alerts', 'color' => 'bg-warning', 'count' => $categoryBreakdown['warning'] ?? 0],
                        'error' => ['name' => 'Error / System Failure', 'color' => 'bg-danger', 'count' => $categoryBreakdown['error'] ?? 0],
                    ];
                @endphp

                <div class="d-flex flex-column gap-3">
                    @foreach($categories as $key => $cat)
                        @php
                            $pct = $totalSent > 0 ? ($cat['count'] / $totalSent) * 100 : 0;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span>{{ $cat['name'] }}</span>
                                <span>{{ $cat['count'] }} ({{ number_format($pct, 1) }}%)</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $cat['color'] }}" role="progressbar" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SOUND TONE DISTRIBUTION -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-music-note-beamed me-2 text-primary"></i> Audio Sound Tone Mappings
                </h5>
                <p class="text-muted small mb-4">Frequency of audio sound effects played with notifications.</p>

                <div class="row g-3">
                    @foreach($soundBreakdown as $tone => $count)
                        @php
                            $pct = $totalSent > 0 ? ($count / $totalSent) * 100 : 0;
                        @endphp
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-dark d-block text-capitalize">{{ str_replace('_', ' ', $tone) }}</span>
                                    <span class="text-muted small">{{ $count }} played ({{ number_format($pct, 1) }}%)</span>
                                </div>
                                <i class="bi bi-volume-up-fill text-primary fs-4"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- INTERACTION LOG & CLICK DISPATCHER -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-list-check me-2 text-primary"></i> Delivery Logs & Click Radar Inspector
                </h5>
                <p class="text-muted small mb-0">Track user clicks and trigger simulated user click actions for testing.</p>
            </div>
            <a href="{{ route('notifications.studio') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Studio Configurator
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Notification Details</th>
                        <th>Priority & Sound</th>
                        <th>Click Interaction</th>
                        <th class="text-end">Simulate Click</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="fw-bold text-secondary">#{{ $log->id }}</td>
                            <td class="small text-muted">{{ $log->created_at->format('M d, H:i:s') }}</td>
                            <td>
                                <strong class="d-block">{{ $log->title }}</strong>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 280px;">{{ $log->message }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary mb-1 d-inline-block">Priority: {{ ucfirst($log->priority ?? 'normal') }}</span>
                                <span class="badge bg-light text-dark border d-block">
                                    <i class="bi bi-music-note me-1"></i> {{ $log->sound_tone ?? 'gentle_chime' }}
                                </span>
                            </td>
                            <td>
                                @if($log->is_clicked)
                                    <span class="badge bg-success">
                                        <i class="bi bi-cursor-fill me-1"></i> Clicked ({{ $log->clicked_at ? \Carbon\Carbon::parse($log->clicked_at)->diffForHumans() : 'Recorded' }})
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary border">Unclicked / Pending</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(!$log->is_clicked)
                                    <form action="{{ route('notifications.record-click', $log->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-cursor me-1"></i> Simulate Click
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-light text-muted rounded-pill px-3" disabled>
                                        <i class="bi bi-check2-all me-1"></i> Recorded
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No delivery interaction logs recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>

</body>
</html>

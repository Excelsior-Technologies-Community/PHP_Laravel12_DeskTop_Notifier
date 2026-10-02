<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desktop Notification Studio & Sound Tester</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }

        .studio-header {
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
            color: white;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
        }

        .card-custom {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        .card-custom:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .sound-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sound-card:hover, .sound-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .priority-badge-urgent { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .priority-badge-high { background: #ffedd5; color: #9a3412; border: 1px solid #fb923c; }
        .priority-badge-normal { background: #e0f2fe; color: #075985; border: 1px solid #38bdf8; }
        .priority-badge-low { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

        .toast-preview-box {
            background: #1e293b;
            color: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- HEADER & NAV -->
    <div class="studio-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-2">
                    <i class="bi bi-sliders text-warning"></i> Desktop Notification Studio
                </h1>
                <p class="mb-0 text-light opacity-75">
                    Configure multi-type toast simulators, custom audio tone synthesizers, and click-through URL actions.
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('notifications.dashboard') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('notifications.analytics') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-bar-chart-line-fill"></i> Analytics
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

    <div class="row g-4">
        <!-- SIMULATOR FORM -->
        <div class="col-lg-7">
            <div class="card card-custom p-4">
                <h4 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-send-plus-fill me-2"></i> Live Toast Notification Configurator
                </h4>
                <p class="text-muted small mb-4">
                    Create custom notification payloads with custom audio tones, icons, priorities, and action links.
                </p>

                <form action="{{ route('notifications.studio.trigger') }}" method="POST" id="studioForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Notification Title</label>
                        <input type="text" name="title" id="inputTitle" class="form-control form-control-lg rounded-3" value="System Security Audit Complete" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Message Content</label>
                        <textarea name="message" id="inputMessage" class="form-control rounded-3" rows="3" required>All system vulnerabilities have been scanned successfully. 0 critical errors detected.</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Alert Type</label>
                            <select name="type" id="inputType" class="form-select rounded-3">
                                <option value="info" selected>ℹ️ Info Alert</option>
                                <option value="success">✅ Success Alert</option>
                                <option value="warning">⚠️ Warning Alert</option>
                                <option value="error">🚨 Error / Failure Alert</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Priority Level</label>
                            <select name="priority" id="inputPriority" class="form-select rounded-3" onchange="autoMapSoundTone(this.value)">
                                <option value="low">Low Priority</option>
                                <option value="normal">Normal Priority</option>
                                <option value="high" selected>High Priority</option>
                                <option value="urgent">Urgent Priority</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Audio Tone Preset</label>
                        <div class="row g-2" id="toneSelectorGroup">
                            <div class="col-6 col-md-4">
                                <div class="sound-card text-center active" onclick="selectTone('gentle_chime', this)">
                                    <i class="bi bi-music-note-beamed text-primary fs-4 d-block mb-1"></i>
                                    <span class="fw-bold d-block small">Gentle Chime</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small" onclick="event.stopPropagation(); playTone('gentle_chime');">
                                        <i class="bi bi-play-circle-fill"></i> Test
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="sound-card text-center" onclick="selectTone('digital_bell', this)">
                                    <i class="bi bi-bell-fill text-warning fs-4 d-block mb-1"></i>
                                    <span class="fw-bold d-block small">Digital Bell</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small text-warning" onclick="event.stopPropagation(); playTone('digital_bell');">
                                        <i class="bi bi-play-circle-fill"></i> Test
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="sound-card text-center" onclick="selectTone('soft_whistle', this)">
                                    <i class="bi bi-soundwave text-info fs-4 d-block mb-1"></i>
                                    <span class="fw-bold d-block small">Soft Whistle</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small text-info" onclick="event.stopPropagation(); playTone('soft_whistle');">
                                        <i class="bi bi-play-circle-fill"></i> Test
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="sound-card text-center" onclick="selectTone('emergency_siren', this)">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 d-block mb-1"></i>
                                    <span class="fw-bold d-block small">Emergency Siren</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small text-danger" onclick="event.stopPropagation(); playTone('emergency_siren');">
                                        <i class="bi bi-play-circle-fill"></i> Test
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="sound-card text-center" onclick="selectTone('ping', this)">
                                    <i class="bi bi-broadcast text-success fs-4 d-block mb-1"></i>
                                    <span class="fw-bold d-block small">Ping Tone</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small text-success" onclick="event.stopPropagation(); playTone('ping');">
                                        <i class="bi bi-play-circle-fill"></i> Test
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="sound_tone" id="inputSoundTone" value="gentle_chime">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Custom Icon URL</label>
                            <input type="url" name="icon" id="inputIcon" class="form-control rounded-3" value="https://cdn-icons-png.flaticon.com/512/1828/1828640.png" placeholder="https://example.com/icon.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-secondary">Click-Action Target URL</label>
                            <input type="url" name="action_url" id="inputActionUrl" class="form-control rounded-3" value="http://127.0.0.1:8000/notifications/analytics" placeholder="http://127.0.0.1:8000/...">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-3 px-4 flex-grow-1">
                            <i class="bi bi-send-fill me-2"></i> Save & Dispatch Desktop Toast
                        </button>
                        <button type="button" class="btn btn-dark btn-lg rounded-3 px-4" onclick="triggerClientDesktopNotification()">
                            <i class="bi bi-display me-2"></i> Test Native Toast
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- LIVE PREVIEW & SOUND TESTER -->
        <div class="col-lg-5">
            <!-- PREVIEW CARD -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-eye-fill text-info me-2"></i> Live OS Toast Preview
                </h5>

                <div class="toast-preview-box">
                    <div class="d-flex align-items-start gap-3">
                        <img id="previewIcon" src="https://cdn-icons-png.flaticon.com/512/1828/1828640.png" width="45" height="45" class="rounded-3 bg-white p-1" alt="Icon">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold fs-6" id="previewTitle">System Security Audit Complete</span>
                                <span class="badge bg-secondary small" id="previewPriorityBadge">High</span>
                            </div>
                            <p class="small text-light opacity-75 mb-2" id="previewMessage">All system vulnerabilities have been scanned successfully. 0 critical errors detected.</p>
                            <div class="d-flex justify-content-between align-items-center small border-top border-secondary pt-2">
                                <span class="text-info"><i class="bi bi-volume-up-fill me-1"></i> <span id="previewToneName">Gentle Chime</span></span>
                                <span class="text-warning small text-truncate" style="max-width: 150px;"><i class="bi bi-link-45deg"></i> <span id="previewActionUrl">analytics</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AUDIO TONE CUSTOMIZER -->
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="bi bi-music-note-list text-primary me-2"></i> Audio Tone Customizer
                </h5>
                <p class="text-muted small">
                    Synthesize custom frequencies or sound profiles mapped to event priorities.
                </p>

                <div class="list-group list-group-flush rounded-3 border">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold d-block">Gentle Chime</span>
                            <span class="text-muted small">Soft sine chord for regular notifications</span>
                        </div>
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="playTone('gentle_chime')">
                            <i class="bi bi-play-fill"></i> Play
                        </button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold d-block">Digital Bell</span>
                            <span class="text-muted small">Harmonic bell ring for success alerts</span>
                        </div>
                        <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="playTone('digital_bell')">
                            <i class="bi bi-play-fill"></i> Play
                        </button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold d-block">Soft Whistle</span>
                            <span class="text-muted small">Ascending whistle frequency tone</span>
                        </div>
                        <button class="btn btn-sm btn-outline-info rounded-pill px-3" onclick="playTone('soft_whistle')">
                            <i class="bi bi-play-fill"></i> Play
                        </button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold d-block">Emergency Siren</span>
                            <span class="text-muted small">Dual siren oscillator sweep for critical alerts</span>
                        </div>
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="playTone('emergency_siren')">
                            <i class="bi bi-play-fill"></i> Play
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT STUDIO DISPATCHES -->
    <div class="card card-custom p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history me-2 text-primary"></i> Recent Notification Dispatches & Sound Tests
            </h5>
            <a href="{{ route('notifications.analytics') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                View Full Analytics <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>Title & Message</th>
                        <th>Type & Priority</th>
                        <th>Sound Tone</th>
                        <th>Action URL</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentNotifications as $notif)
                        <tr>
                            <td class="small text-muted">{{ $notif->created_at->format('M d, H:i:s') }}</td>
                            <td>
                                <strong class="d-block">{{ $notif->title }}</strong>
                                <span class="small text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $notif->message }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $notif->type == 'error' ? 'danger' : ($notif->type == 'warning' ? 'warning text-dark' : ($notif->type == 'success' ? 'success' : 'info')) }} me-1">
                                    {{ strtoupper($notif->type) }}
                                </span>
                                <span class="badge priority-badge-{{ $notif->priority ?? 'normal' }}">
                                    {{ ucfirst($notif->priority ?? 'normal') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-volume-up me-1 text-primary"></i> {{ $notif->sound_tone ?? 'gentle_chime' }}
                                </span>
                            </td>
                            <td class="small">
                                @if($notif->action_url)
                                    <a href="{{ $notif->action_url }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Link
                                    </a>
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill me-1" onclick="playTone('{{ $notif->sound_tone ?? 'gentle_chime' }}')">
                                    <i class="bi bi-soundwave"></i> Sound
                                </button>
                                <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="triggerClientCustomNotif('{{ addslashes($notif->title) }}', '{{ addslashes($notif->message) }}', '{{ $notif->sound_tone ?? 'gentle_chime' }}', '{{ $notif->action_url }}')">
                                    <i class="bi bi-bell"></i> Re-Trigger
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No notification histories recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Sound tone selector state
    function selectTone(toneName, element) {
        document.querySelectorAll('.sound-card').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        }
        document.getElementById('inputSoundTone').value = toneName;
        document.getElementById('previewToneName').innerText = toneName.replace('_', ' ').toUpperCase();
        playTone(toneName);
    }

    // Auto map priority to default tone
    function autoMapSoundTone(priority) {
        let tone = 'gentle_chime';
        if (priority === 'urgent') tone = 'emergency_siren';
        else if (priority === 'high') tone = 'digital_bell';
        else if (priority === 'low') tone = 'ping';
        
        document.getElementById('inputSoundTone').value = tone;
        document.getElementById('previewToneName').innerText = tone.replace('_', ' ').toUpperCase();
    }

    // Web Audio Synthesizer for Custom Tones
    function playTone(toneType) {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();

            if (toneType === 'gentle_chime') {
                const notes = [523.25, 659.25, 783.99]; // C5, E5, G5 major chord
                notes.forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + (idx * 0.08));
                    gain.gain.setValueAtTime(0.2, ctx.currentTime + (idx * 0.08));
                    gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + (idx * 0.08) + 0.6);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(ctx.currentTime + (idx * 0.08));
                    osc.stop(ctx.currentTime + (idx * 0.08) + 0.6);
                });
            } else if (toneType === 'digital_bell') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                gain.gain.setValueAtTime(0.4, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.8);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.8);
            } else if (toneType === 'soft_whistle') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(400, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.3);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.35);
            } else if (toneType === 'emergency_siren') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(600, ctx.currentTime);
                osc.frequency.linearRampToValueAtTime(1400, ctx.currentTime + 0.25);
                osc.frequency.linearRampToValueAtTime(600, ctx.currentTime + 0.5);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.55);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.55);
            } else { // ping
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(1046.50, ctx.currentTime); // C6
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.2);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.2);
            }
        } catch(e) {
            console.error("Audio Context Error:", e);
        }
    }

    // Real-time Preview Binding
    document.getElementById('inputTitle').addEventListener('input', function(e) {
        document.getElementById('previewTitle').innerText = e.target.value || 'Notification Title';
    });
    document.getElementById('inputMessage').addEventListener('input', function(e) {
        document.getElementById('previewMessage').innerText = e.target.value || 'Notification message text...';
    });
    document.getElementById('inputActionUrl').addEventListener('input', function(e) {
        document.getElementById('previewActionUrl').innerText = e.target.value ? new URL(e.target.value).pathname : 'link';
    });
    document.getElementById('inputIcon').addEventListener('input', function(e) {
        document.getElementById('previewIcon').src = e.target.value || 'https://cdn-icons-png.flaticon.com/512/1828/1828640.png';
    });

    // Native Browser Desktop Notification
    function triggerClientDesktopNotification() {
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notifications.");
            return;
        }

        const title = document.getElementById('inputTitle').value;
        const msg = document.getElementById('inputMessage').value;
        const tone = document.getElementById('inputSoundTone').value;
        const icon = document.getElementById('inputIcon').value;
        const url = document.getElementById('inputActionUrl').value;

        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                playTone(tone);
                const notification = new Notification(title, {
                    body: msg,
                    icon: icon
                });
                if (url) {
                    notification.onclick = function() {
                        window.open(url, '_blank');
                    };
                }
            } else {
                alert("Desktop notification permission denied. Please allow notifications in your browser.");
            }
        });
    }

    function triggerClientCustomNotif(title, msg, tone, url) {
        if (!("Notification" in window)) return;
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                playTone(tone);
                const notification = new Notification(title, {
                    body: msg
                });
                if (url) {
                    notification.onclick = function() {
                        window.open(url, '_blank');
                    };
                }
            }
        });
    }
</script>

</body>
</html>

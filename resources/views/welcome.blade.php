<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laravel Desktop Notifier</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            min-height: 100vh;
            font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, .95);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .18);
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .section-subtitle {
            color: #64748b;
        }

        .preview-card {
            border-radius: 15px;
            border: 2px dashed #0d6efd;
            background: #f8fbff;
            padding: 25px;
        }

        .stat-card {
            border-radius: 15px;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
        }

        .blue {
            background: #2563eb;
        }

        .green {
            background: #16a34a;
        }

        .orange {
            background: #ea580c;
        }

        .purple {
            background: #7c3aed;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            margin: auto;
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
        }

        .btn {
            border-radius: 10px;
        }

        .preview-icon {
            font-size: 45px;
        }

        .footer-text {
            color: #94a3b8;
            font-size: 14px;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="glass-card">

            <div class="text-center mb-5">

                <div class="icon-circle">
                    <i class="bi bi-bell-fill"></i>
                </div>

                <h1 class="section-title">
                    Laravel Desktop Notification Center
                </h1>

                <p class="section-subtitle">
                    Send professional desktop notifications with templates, preview, browser notifications and custom settings.
                </p>

            </div>

            @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <strong>
                    <i class="bi bi-check-circle-fill"></i>
                    Success!
                </strong>

                {{ session('success') }}

                <button
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

            @endif

            <div class="row mb-4">

                <div class="col-md-3 mb-3">

                    <div class="stat-card blue">

                        <i class="bi bi-windows fs-1"></i>

                        <h5 class="mt-3">
                            Windows
                        </h5>

                        <small>
                            Desktop Notifications
                        </small>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="stat-card green">

                        <i class="bi bi-browser-chrome fs-1"></i>

                        <h5 class="mt-3">
                            Browser
                        </h5>

                        <small>
                            Web Notifications
                        </small>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="stat-card orange">

                        <i class="bi bi-clock-history fs-1"></i>

                        <h5 class="mt-3">
                            Delay
                        </h5>

                        <small>
                            Custom Timer
                        </small>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="stat-card purple">

                        <i class="bi bi-layout-text-window fs-1"></i>

                        <h5 class="mt-3">
                            Templates
                        </h5>

                        <small>
                            Ready Messages
                        </small>

                    </div>

                </div>

            </div>

            <div class="row">

                <!-- LEFT COLUMN -->

                <div class="col-lg-7">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-primary text-white">

                            <h5 class="mb-0">
                                <i class="bi bi-send-fill"></i>
                                Notification Configuration
                            </h5>

                        </div>

                        <div class="card-body">

                            <form action="{{ route('notify') }}" method="GET" id="notificationForm">

                                {{-- Notification Template --}}
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Notification Template
                                    </label>

                                    <select class="form-select" id="template">

                                        <option value="">
                                            Select Template
                                        </option>

                                        <option value="backup">
                                            Backup Completed
                                        </option>

                                        <option value="deployment">
                                            Deployment Success
                                        </option>

                                        <option value="migration">
                                            Migration Completed
                                        </option>

                                        <option value="cache">
                                            Cache Cleared
                                        </option>

                                        <option value="queue">
                                            Queue Restarted
                                        </option>

                                        <option value="custom">
                                            Custom Notification
                                        </option>

                                    </select>

                                </div>

                                {{-- Title --}}
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Notification Title
                                    </label>

                                    <input
                                        type="text"
                                        name="title"
                                        id="title"
                                        class="form-control"
                                        placeholder="Enter notification title">

                                </div>

                                {{-- Message --}}
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Notification Message
                                    </label>

                                    <textarea
                                        class="form-control"
                                        rows="4"
                                        name="message"
                                        id="message"
                                        placeholder="Enter notification message"></textarea>

                                </div>

                                <div class="row">

                                    {{-- Type --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">
                                            Notification Type
                                        </label>

                                        <select
                                            class="form-select"
                                            name="type"
                                            id="type">

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

                                    {{-- Delay --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label fw-bold">
                                            Delay
                                        </label>

                                        <select
                                            class="form-select"
                                            name="delay"
                                            id="delay">

                                            <option value="1">1 Second</option>
                                            <option value="2">2 Seconds</option>
                                            <option value="3" selected>3 Seconds</option>
                                            <option value="5">5 Seconds</option>
                                            <option value="10">10 Seconds</option>

                                        </select>

                                    </div>

                                </div>

                                <div class="d-grid gap-2">

                                    <button
                                        type="button"
                                        class="btn btn-warning"
                                        id="randomBtn">

                                        🎲 Generate Random Notification

                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-info text-white"
                                        id="browserBtn">

                                        🌐 Browser Notification

                                    </button>

                                    <button
                                        class="btn btn-primary btn-lg">

                                        🚀 Send Desktop Notification

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- RIGHT COLUMN -->

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-success text-white">

                            <h5 class="mb-0">

                                <i class="bi bi-phone"></i>

                                Live Preview

                            </h5>

                        </div>

                        <div class="card-body">

                            <div
                                class="preview-card"
                                id="previewCard">

                                <div class="text-center mb-3">

                                    <div
                                        id="previewIcon"
                                        class="preview-icon">

                                        🔔

                                    </div>

                                </div>

                                <h4
                                    id="previewTitle">

                                    Laravel Desktop Notifier

                                </h4>

                                <p
                                    id="previewMessage">

                                    Your notification preview will appear here.

                                </p>

                                <hr>

                                <div class="row text-center">

                                    <div class="col-6">

                                        <strong>
                                            Type
                                        </strong>

                                        <div id="previewType">
                                            Info
                                        </div>

                                    </div>

                                    <div class="col-6">

                                        <strong>
                                            Delay
                                        </strong>

                                        <div id="previewDelay">
                                            3 Seconds
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card mt-4 shadow-sm border-0">

                        <div class="card-body">

                            <h5>
                                <i class="bi bi-check2-circle"></i>
                                Features Included
                            </h5>

                            <ul class="mb-0">

                                <li>✅ Notification Templates</li>

                                <li>✅ Live Notification Preview</li>

                                <li>✅ Browser Notification</li>

                                <li>✅ Windows Desktop Notification</li>

                                <li>✅ Random Notification Generator</li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

            <hr class="my-5">

            <div class="text-center footer-text">

                Laravel 12 Desktop Notifier Demo |
                Enhanced UI with Bootstrap 5 |
                Made for Learning

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const templates = {

            backup: {
                title: "Backup Completed",
                message: "Database backup completed successfully.",
                type: "success"
            },

            deployment: {
                title: "Deployment Success",
                message: "Application deployed successfully.",
                type: "success"
            },

            migration: {
                title: "Migration Completed",
                message: "Database migration finished successfully.",
                type: "info"
            },

            cache: {
                title: "Cache Cleared",
                message: "Application cache has been cleared.",
                type: "warning"
            },

            queue: {
                title: "Queue Restarted",
                message: "Queue workers restarted successfully.",
                type: "info"
            }

        };

        const randomNotifications = [

            {
                title: "Build Successful",
                message: "Your application compiled successfully.",
                type: "success"
            },

            {
                title: "Server Warning",
                message: "CPU usage is above 80%.",
                type: "warning"
            },

            {
                title: "Database Backup",
                message: "Nightly backup completed successfully.",
                type: "success"
            },

            {
                title: "Cache Cleared",
                message: "Cache cleared without issues.",
                type: "info"
            },

            {
                title: "Deployment Failed",
                message: "Deployment encountered an unexpected error.",
                type: "error"
            },

            {
                title: "Queue Restarted",
                message: "Queue workers restarted.",
                type: "info"
            }

        ];

        // Inputs

        const template = document.getElementById("template");
        const title = document.getElementById("title");
        const message = document.getElementById("message");
        const type = document.getElementById("type");
        const delay = document.getElementById("delay");

        // Preview

        const previewTitle = document.getElementById("previewTitle");
        const previewMessage = document.getElementById("previewMessage");
        const previewType = document.getElementById("previewType");
        const previewDelay = document.getElementById("previewDelay");
        const previewIcon = document.getElementById("previewIcon");

        // -------------------------

        function updatePreview() {

            previewTitle.innerHTML = title.value || "Laravel Desktop Notifier";

            previewMessage.innerHTML = message.value || "Your notification preview will appear here.";

            previewType.innerHTML = type.value;

            previewDelay.innerHTML = delay.value + " Seconds";

            switch (type.value) {

                case "success":

                    previewIcon.innerHTML = "✅";

                    break;

                case "warning":

                    previewIcon.innerHTML = "⚠️";

                    break;

                case "error":

                    previewIcon.innerHTML = "❌";

                    break;

                default:

                    previewIcon.innerHTML = "🔔";

            }

        }

        // -------------------------

        title.addEventListener("input", updatePreview);

        message.addEventListener("input", updatePreview);

        type.addEventListener("change", updatePreview);

        delay.addEventListener("change", updatePreview);

        // -------------------------

        template.addEventListener("change", function() {

            if (this.value === "custom") {

                title.value = "";
                message.value = "";
                updatePreview();
                return;

            }

            if (!templates[this.value]) return;

            title.value = templates[this.value].title;

            message.value = templates[this.value].message;

            type.value = templates[this.value].type;

            updatePreview();

        });

        // -------------------------

        document.getElementById("randomBtn").addEventListener("click", function() {

            let item = randomNotifications[
                Math.floor(Math.random() * randomNotifications.length)
            ];

            title.value = item.title;

            message.value = item.message;

            type.value = item.type;

            updatePreview();

        });

        // -------------------------

        document.getElementById("browserBtn").addEventListener("click", function() {

            if (!("Notification" in window)) {

                alert("Browser notifications are not supported.");

                return;

            }

            Notification.requestPermission().then(permission => {

                if (permission === "granted") {

                    new Notification(

                        title.value || "Laravel Notification",

                        {

                            body: message.value || "Notification Message",

                            icon: "/logo.png"

                        }

                    );

                } else {

                    alert("Notification permission denied.");

                }

            });

        });

        // -------------------------

        updatePreview();
    </script>

</body>

</html>
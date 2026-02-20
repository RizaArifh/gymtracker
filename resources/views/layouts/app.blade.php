<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Gym Tracker' }}</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --text: #1b2430;
            --muted: #5f6c7b;
            --line: #dde3ea;
            --accent: #0e7c66;
            --danger: #b83b5e;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
        .topbar {
            background: linear-gradient(120deg, #153448, #1f6e8c);
            color: #fff;
            padding: 14px 0;
        }
        .topbar .container {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .brand {
            font-weight: 700;
            margin-right: 12px;
        }
        .nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
            padding: 8px 10px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 8px;
            display: inline-block;
            font-size: 14px;
        }
        .nav a:hover { background: rgba(255,255,255,.1); }
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 5px 12px rgba(0,0,0,.04);
        }
        .grid {
            display: grid;
            gap: 14px;
        }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        h1, h2, h3 { margin-top: 0; }
        .muted { color: var(--muted); }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border-bottom: 1px solid var(--line);
            text-align: left;
            padding: 10px 8px;
            vertical-align: top;
        }
        .btn {
            background: var(--accent);
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
        }
        .btn.secondary { background: #415a77; }
        .btn.danger { background: var(--danger); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .actions.between { justify-content: space-between; align-items: center; }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        .help {
            display: block;
            margin-top: -8px;
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 12px;
        }
        input, select, textarea {
            width: 100%;
            padding: 9px;
            border: 1px solid var(--line);
            border-radius: 8px;
            margin-bottom: 12px;
            background: #fff;
        }
        input.is-invalid, select.is-invalid, textarea.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 2px rgba(184, 59, 94, .14);
        }
        .field-error {
            display: block;
            color: var(--danger);
            font-size: 12px;
            margin-top: -8px;
            margin-bottom: 10px;
        }
        .flash {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            border: 1px solid #b7dfd6;
            background: #e7f7f2;
            color: #155e52;
        }
        .error-box {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            border: 1px solid #f0b8bf;
            background: #fff1f3;
            color: #8d1d3b;
        }
        .pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
        }
        .pill.ok { background: #daf6e8; color: #157347; }
        .pill.wait { background: #ffe9b8; color: #8a5a00; }
        .task-cards {
            display: none;
            gap: 10px;
            flex-direction: column;
        }
        .task-card {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
        }
        .progress-wrap {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #d9e2ec;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #00a896, #028090);
            transition: width .25s ease;
        }
        @media (max-width: 720px) {
            .container { padding: 12px; }
            .topbar .container { display: block; }
            .nav a { margin-bottom: 8px; }
            .desktop-table { display: none; }
            .task-cards { display: flex; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="container">
        <div class="brand">Gym Tracker Pro</div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('body-measurements.index') }}">Body Measurements</a>
            <a href="{{ route('calorie-entries.index') }}">Calorie Logs</a>
            <a href="{{ route('daily-tasks.index') }}">Daily Tasks</a>
        </nav>
    </div>
</header>
<main class="container">
    @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error-box">
            <strong>Validation errors ditemukan. Cek field yang ditandai merah.</strong>
        </div>
    @endif

    @yield('content')
</main>
<script>
    document.querySelectorAll('input, select, textarea').forEach((field) => {
        field.addEventListener('invalid', () => field.classList.add('is-invalid'));
        field.addEventListener('input', () => {
            if (field.checkValidity()) field.classList.remove('is-invalid');
        });
        field.addEventListener('change', () => {
            if (field.checkValidity()) field.classList.remove('is-invalid');
        });
    });

    document.querySelectorAll('form[data-auto-submit]').forEach((form) => {
        const checkbox = form.querySelector('input[type="checkbox"][name="is_completed"]');
        if (!checkbox) return;
        checkbox.addEventListener('change', () => {
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        });
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();
                const row = form.closest('[data-task-item]');
                if (row) {
                    const pill = row.querySelector('[data-task-pill]');
                    if (pill) {
                        pill.className = 'pill ' + (data.is_completed ? 'ok' : 'wait');
                        pill.textContent = data.is_completed ? 'Done' : 'Pending';
                    }
                }
                const summary = document.querySelector('[data-task-summary]');
                if (summary) {
                    summary.textContent = data.completed_count + '/' + data.total_count;
                }
                const bar = document.querySelector('[data-task-progress]');
                if (bar) {
                    bar.style.width = data.completion_percentage + '%';
                }
                const percent = document.querySelector('[data-task-percent]');
                if (percent) {
                    percent.textContent = data.completion_percentage + '%';
                }
            } catch (_) {
                window.location.reload();
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        }
        .btn.secondary { background: #415a77; }
        .btn.danger { background: var(--danger); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        input, select, textarea {
            width: 100%;
            padding: 9px;
            border: 1px solid var(--line);
            border-radius: 8px;
            margin-bottom: 12px;
            background: #fff;
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
            <strong>Validation errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>

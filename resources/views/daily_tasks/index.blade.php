@extends('layouts.app', ['title' => 'Daily Tasks'])

@section('content')
    <div class="card">
        <div class="actions between">
            <h1 style="margin:0;">Daily Tasks & Activity</h1>
            <a class="btn" href="{{ route('daily-tasks.create') }}">+ Tambah Task</a>
        </div>
    </div>

    <div class="card">
        <form method="GET" class="grid grid-4" style="align-items: end;">
            <div>
                <label for="q">Cari task</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="Judul / kategori">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="done" @selected(request('status') === 'done')>Done</option>
                </select>
            </div>
            <div>
                <label for="task_date">Tanggal</label>
                <input type="date" id="task_date" name="task_date" value="{{ request('task_date') }}">
            </div>
            <div class="actions">
                <button class="btn" type="submit">Filter</button>
                <a class="btn secondary" href="{{ route('daily-tasks.index') }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="actions between" style="margin-bottom: 10px;">
            <strong>Completion Progress</strong>
            <span>
                <strong data-task-summary>{{ $completedTasks }}/{{ $totalTasks }}</strong>
                (<span data-task-percent>{{ $completionPercentage }}%</span>)
            </span>
        </div>
        <div class="progress-wrap">
            <div class="progress-bar" data-task-progress style="width: {{ $completionPercentage }}%;"></div>
        </div>
    </div>

    <div class="card">
        <table class="desktop-table">
            <thead>
            <tr>
                <th>Tanggal</th>
                <th>Task</th>
                <th>Kategori</th>
                <th>Kalori Burn</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr data-task-item>
                    <td>{{ $task->task_date->format('d M Y') }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong><br>
                        <span class="muted">{{ $task->duration_minutes ?? 0 }} menit</span>
                    </td>
                    <td>{{ $task->category ?? '-' }}</td>
                    <td>{{ $task->calories_burned }} kcal</td>
                    <td>
                        <span class="pill {{ $task->is_completed ? 'ok' : 'wait' }}" data-task-pill>
                            {{ $task->is_completed ? 'Done' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <form method="POST" action="{{ route('daily-tasks.toggle', $task) }}" data-auto-submit>
                                @csrf
                                <input type="hidden" name="is_completed" value="0">
                                <label style="display:flex; align-items:center; gap:6px; margin:0;">
                                    <input style="width:auto; margin:0;" type="checkbox" name="is_completed" value="1" @checked($task->is_completed)>
                                    Done
                                </label>
                            </form>
                            <a class="btn secondary" href="{{ route('daily-tasks.edit', $task) }}">Edit</a>
                            <form method="POST" action="{{ route('daily-tasks.destroy', $task) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="task-cards">
            @forelse($tasks as $task)
                <div class="task-card" data-task-item>
                    <div class="actions between">
                        <strong>{{ $task->title }}</strong>
                        <span class="pill {{ $task->is_completed ? 'ok' : 'wait' }}" data-task-pill>
                            {{ $task->is_completed ? 'Done' : 'Pending' }}
                        </span>
                    </div>
                    <p class="muted" style="margin:8px 0;">{{ $task->task_date->format('d M Y') }} | {{ $task->category ?? '-' }}</p>
                    <p style="margin:0 0 10px 0;">{{ $task->calories_burned }} kcal | {{ $task->duration_minutes ?? 0 }} menit</p>
                    <div class="actions">
                        <form method="POST" action="{{ route('daily-tasks.toggle', $task) }}" data-auto-submit>
                            @csrf
                            <input type="hidden" name="is_completed" value="0">
                            <label style="display:flex; align-items:center; gap:6px; margin:0;">
                                <input style="width:auto; margin:0;" type="checkbox" name="is_completed" value="1" @checked($task->is_completed)>
                                Done
                            </label>
                        </form>
                        <a class="btn secondary" href="{{ route('daily-tasks.edit', $task) }}">Edit</a>
                        <form method="POST" action="{{ route('daily-tasks.destroy', $task) }}" onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="muted">Belum ada data.</div>
            @endforelse
        </div>

        <div style="margin-top: 12px;">{{ $tasks->links() }}</div>
    </div>
@endsection

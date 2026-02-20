@extends('layouts.app', ['title' => 'Daily Tasks'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between;">
            <h1 style="margin:0;">Daily Tasks & Activity</h1>
            <a class="btn" href="{{ route('daily-tasks.create') }}">+ Tambah Task</a>
        </div>
    </div>

    <div class="card">
        <table>
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
                    <tr>
                        <td>{{ $task->task_date->format('d M Y') }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->category ?? '-' }}</td>
                        <td>{{ $task->calories_burned }} kcal</td>
                        <td>
                            @if($task->is_completed)
                                <span class="pill ok">Done</span>
                            @else
                                <span class="pill wait">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('daily-tasks.show', $task) }}">Detail</a>
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

        <div style="margin-top: 12px;">{{ $tasks->links() }}</div>
    </div>
@endsection

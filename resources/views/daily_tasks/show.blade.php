@extends('layouts.app', ['title' => 'Detail Daily Task'])

@section('content')
    <div class="card">
        <h1>Detail Daily Task</h1>
        <table>
            <tr><th>Tanggal</th><td>{{ $dailyTask->task_date->format('d M Y') }}</td></tr>
            <tr><th>Judul</th><td>{{ $dailyTask->title }}</td></tr>
            <tr><th>Kategori</th><td>{{ $dailyTask->category ?? '-' }}</td></tr>
            <tr><th>Durasi</th><td>{{ $dailyTask->duration_minutes ?? '-' }} menit</td></tr>
            <tr><th>Kalori Terbakar</th><td>{{ $dailyTask->calories_burned }} kcal</td></tr>
            <tr><th>Target</th><td>{{ $dailyTask->target_value ?? '-' }} {{ $dailyTask->target_unit ?? '' }}</td></tr>
            <tr><th>Status</th><td>{{ $dailyTask->is_completed ? 'Completed' : 'Pending' }}</td></tr>
            <tr><th>Catatan</th><td>{{ $dailyTask->notes ?? '-' }}</td></tr>
        </table>
        <div class="actions" style="margin-top:12px;">
            <a class="btn secondary" href="{{ route('daily-tasks.edit', $dailyTask) }}">Edit</a>
            <a class="btn secondary" href="{{ route('daily-tasks.index') }}">Kembali</a>
        </div>
    </div>
@endsection

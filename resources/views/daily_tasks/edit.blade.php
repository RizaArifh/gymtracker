@extends('layouts.app', ['title' => 'Edit Daily Task'])

@section('content')
    <div class="card">
        <h1>Edit Daily Task</h1>
        <form method="POST" action="{{ route('daily-tasks.update', $dailyTask) }}">
            @method('PUT')
            @include('daily_tasks._form')
        </form>
    </div>
@endsection

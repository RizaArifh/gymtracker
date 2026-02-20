@extends('layouts.app', ['title' => 'Tambah Daily Task'])

@section('content')
    <div class="card">
        <h1>Tambah Daily Task</h1>
        <form method="POST" action="{{ route('daily-tasks.store') }}">
            @include('daily_tasks._form')
        </form>
    </div>
@endsection

@extends('layouts.app', ['title' => 'Tambah Calorie Log'])

@section('content')
    <div class="card">
        <h1>Tambah Calorie Log</h1>
        <form method="POST" action="{{ route('calorie-entries.store') }}">
            @include('calorie_entries._form')
        </form>
    </div>
@endsection

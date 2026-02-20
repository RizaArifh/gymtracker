@extends('layouts.app', ['title' => 'Edit Calorie Log'])

@section('content')
    <div class="card">
        <h1>Edit Calorie Log</h1>
        <form method="POST" action="{{ route('calorie-entries.update', $calorieEntry) }}">
            @method('PUT')
            @include('calorie_entries._form')
        </form>
    </div>
@endsection

@extends('layouts.app', ['title' => 'Edit Measurement'])

@section('content')
    <div class="card">
        <h1>Edit Measurement</h1>
        <form method="POST" action="{{ route('body-measurements.update', $bodyMeasurement) }}">
            @method('PUT')
            @include('body_measurements._form')
        </form>
    </div>
@endsection

@extends('layouts.app', ['title' => 'Tambah Measurement'])

@section('content')
    <div class="card">
        <h1>Tambah Measurement</h1>
        <form method="POST" action="{{ route('body-measurements.store') }}">
            @include('body_measurements._form')
        </form>
    </div>
@endsection

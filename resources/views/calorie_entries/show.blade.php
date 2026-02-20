@extends('layouts.app', ['title' => 'Detail Calorie Log'])

@section('content')
    <div class="card">
        <h1>Detail Calorie Log</h1>
        <table>
            <tr><th>Tanggal</th><td>{{ $calorieEntry->entry_date->format('d M Y') }}</td></tr>
            <tr><th>Meal Type</th><td>{{ $calorieEntry->meal_type }}</td></tr>
            <tr><th>Makanan</th><td>{{ $calorieEntry->food_name }}</td></tr>
            <tr><th>Kalori</th><td>{{ $calorieEntry->calories }} kcal</td></tr>
            <tr><th>Protein</th><td>{{ $calorieEntry->protein_grams ?? '-' }} g</td></tr>
            <tr><th>Karbohidrat</th><td>{{ $calorieEntry->carbs_grams ?? '-' }} g</td></tr>
            <tr><th>Lemak</th><td>{{ $calorieEntry->fat_grams ?? '-' }} g</td></tr>
            <tr><th>Catatan</th><td>{{ $calorieEntry->notes ?? '-' }}</td></tr>
        </table>
        <div class="actions" style="margin-top:12px;">
            <a class="btn secondary" href="{{ route('calorie-entries.edit', $calorieEntry) }}">Edit</a>
            <a class="btn secondary" href="{{ route('calorie-entries.index') }}">Kembali</a>
        </div>
    </div>
@endsection

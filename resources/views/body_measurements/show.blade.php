@extends('layouts.app', ['title' => 'Detail Measurement'])

@section('content')
    <div class="card">
        <h1>Detail Measurement</h1>
        <table>
            <tr><th>Tanggal</th><td>{{ $bodyMeasurement->measurement_date->format('d M Y') }}</td></tr>
            <tr><th>Tinggi</th><td>{{ $bodyMeasurement->height_cm ?? '-' }} cm</td></tr>
            <tr><th>Berat</th><td>{{ $bodyMeasurement->weight_kg }} kg</td></tr>
            <tr><th>Body Fat</th><td>{{ $bodyMeasurement->body_fat_percentage ?? '-' }} %</td></tr>
            <tr><th>Muscle Mass</th><td>{{ $bodyMeasurement->muscle_mass_kg ?? '-' }} kg</td></tr>
            <tr><th>BMI</th><td>{{ $bodyMeasurement->bmi ?? '-' }}</td></tr>
            <tr><th>Visceral Fat Grade</th><td>{{ $bodyMeasurement->visceral_fat_grade ?? '-' }}</td></tr>
            <tr><th>Fat Mass</th><td>{{ $bodyMeasurement->fat_mass_kg ?? '-' }} kg</td></tr>
            <tr><th>Lean Body Mass</th><td>{{ $bodyMeasurement->lean_body_mass_kg ?? '-' }} kg</td></tr>
            <tr><th>Evaluation Score</th><td>{{ $bodyMeasurement->evaluation_score ?? '-' }}</td></tr>
            <tr><th>Physical Age</th><td>{{ $bodyMeasurement->physical_age ?? '-' }}</td></tr>
            <tr><th>Dada</th><td>{{ $bodyMeasurement->chest_cm ?? '-' }} cm</td></tr>
            <tr><th>Pinggang</th><td>{{ $bodyMeasurement->waist_cm ?? '-' }} cm</td></tr>
            <tr><th>Pinggul</th><td>{{ $bodyMeasurement->hips_cm ?? '-' }} cm</td></tr>
            <tr><th>Catatan</th><td>{{ $bodyMeasurement->notes ?? '-' }}</td></tr>
            <tr><th>OCR Source</th><td>{{ $bodyMeasurement->source_image_path ? 'Ada (tersimpan)' : '-' }}</td></tr>
        </table>
        <div class="actions" style="margin-top:12px;">
            <a class="btn secondary" href="{{ route('body-measurements.edit', $bodyMeasurement) }}">Edit</a>
            <a class="btn secondary" href="{{ route('body-measurements.index') }}">Kembali</a>
        </div>
    </div>
@endsection

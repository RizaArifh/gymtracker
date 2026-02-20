@extends('layouts.app', ['title' => 'Body Measurements'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between;">
            <h1 style="margin:0;">Body Measurements</h1>
            <a class="btn" href="{{ route('body-measurements.create') }}">+ Tambah Measurement</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Berat</th>
                    <th>Body Fat</th>
                    <th>Muscle Mass</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($measurements as $item)
                    <tr>
                        <td>{{ $item->measurement_date->format('d M Y') }}</td>
                        <td>{{ $item->weight_kg }} kg</td>
                        <td>{{ $item->body_fat_percentage ?? '-' }} %</td>
                        <td>{{ $item->muscle_mass_kg ?? '-' }} kg</td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('body-measurements.show', $item) }}">Detail</a>
                                <a class="btn secondary" href="{{ route('body-measurements.edit', $item) }}">Edit</a>
                                <form method="POST" action="{{ route('body-measurements.destroy', $item) }}" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 12px;">{{ $measurements->links() }}</div>
    </div>
@endsection

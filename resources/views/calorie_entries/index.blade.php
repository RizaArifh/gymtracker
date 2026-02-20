@extends('layouts.app', ['title' => 'Calorie Logs'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between;">
            <h1 style="margin:0;">Calorie Logs</h1>
            <a class="btn" href="{{ route('calorie-entries.create') }}">+ Tambah Log</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Meal</th>
                    <th>Makanan</th>
                    <th>Kalori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->entry_date->format('d M Y') }}</td>
                        <td>{{ $entry->meal_type }}</td>
                        <td>{{ $entry->food_name }}</td>
                        <td>{{ $entry->calories }} kcal</td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary" href="{{ route('calorie-entries.show', $entry) }}">Detail</a>
                                <a class="btn secondary" href="{{ route('calorie-entries.edit', $entry) }}">Edit</a>
                                <form method="POST" action="{{ route('calorie-entries.destroy', $entry) }}" onsubmit="return confirm('Hapus data ini?')">
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

        <div style="margin-top: 12px;">{{ $entries->links() }}</div>
    </div>
@endsection

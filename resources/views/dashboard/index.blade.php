@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="card">
        <h1>Dashboard Gym Monitoring</h1>
        <p class="muted">Tanggal: {{ \Illuminate\Support\Carbon::parse($today)->format('d M Y') }}</p>
    </div>

    <div class="grid grid-4">
        <div class="card">
            <h3>Kalori Masuk Hari Ini</h3>
            <p><strong>{{ number_format($todayCaloriesIn) }} kcal</strong></p>
        </div>
        <div class="card">
            <h3>Kalori Terbakar Hari Ini</h3>
            <p><strong>{{ number_format($todayCaloriesBurned) }} kcal</strong></p>
        </div>
        <div class="card">
            <h3>Net Kalori</h3>
            <p><strong>{{ number_format($todayNetCalories) }} kcal</strong></p>
        </div>
        <div class="card">
            <h3>Task Selesai</h3>
            <p><strong>{{ $todayTaskCompleted }}/{{ $todayTaskTotal }}</strong></p>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Body Measurement Terbaru</h2>
            @if($latestMeasurement)
                <p><strong>{{ $latestMeasurement->measurement_date->format('d M Y') }}</strong></p>
                <p>Berat: {{ $latestMeasurement->weight_kg }} kg</p>
                <p>Body Fat: {{ $latestMeasurement->body_fat_percentage ?? '-' }} %</p>
                <p>Muscle Mass: {{ $latestMeasurement->muscle_mass_kg ?? '-' }} kg</p>
                <div class="actions">
                    <a class="btn secondary" href="{{ route('body-measurements.show', $latestMeasurement) }}">Detail</a>
                </div>
            @else
                <p class="muted">Belum ada data measurement.</p>
            @endif
        </div>

        <div class="card">
            <h2>Trend Berat (7 hari)</h2>
            @if($weeklyMeasurements->isEmpty())
                <p class="muted">Belum ada data 7 hari terakhir.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Berat (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklyMeasurements as $item)
                            <tr>
                                <td>{{ $item->measurement_date->format('d M Y') }}</td>
                                <td>{{ $item->weight_kg }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Calorie Logs Terbaru</h2>
            @if($recentCalorieEntries->isEmpty())
                <p class="muted">Belum ada log kalori.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Makanan</th>
                            <th>Kcal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCalorieEntries as $entry)
                            <tr>
                                <td>{{ $entry->entry_date->format('d M Y') }}</td>
                                <td>{{ $entry->meal_type }} - {{ $entry->food_name }}</td>
                                <td>{{ $entry->calories }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <h2>Daily Tasks Terbaru</h2>
            @if($recentTasks->isEmpty())
                <p class="muted">Belum ada daily task.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Task</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTasks as $task)
                            <tr>
                                <td>{{ $task->task_date->format('d M Y') }}</td>
                                <td>{{ $task->title }}</td>
                                <td>
                                    @if($task->is_completed)
                                        <span class="pill ok">Done</span>
                                    @else
                                        <span class="pill wait">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@csrf
<div class="grid grid-2">
    <div>
        <label for="task_date">Tanggal Task</label>
        <input type="date" id="task_date" name="task_date" value="{{ old('task_date', optional($dailyTask->task_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required>
    </div>
    <div>
        <label for="title">Judul Task</label>
        <input type="text" id="title" name="title" value="{{ old('title', $dailyTask->title ?? '') }}" required>
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="category">Kategori</label>
        <input type="text" id="category" name="category" value="{{ old('category', $dailyTask->category ?? '') }}" placeholder="Contoh: Cardio / Strength / Recovery">
    </div>
    <div>
        <label for="duration_minutes">Durasi (menit)</label>
        <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $dailyTask->duration_minutes ?? '') }}">
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="calories_burned">Kalori Terbakar</label>
        <input type="number" id="calories_burned" name="calories_burned" value="{{ old('calories_burned', $dailyTask->calories_burned ?? 0) }}">
    </div>
    <div>
        <label for="target_value">Target Nilai</label>
        <input type="number" step="0.01" id="target_value" name="target_value" value="{{ old('target_value', $dailyTask->target_value ?? '') }}">
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="target_unit">Satuan Target</label>
        <input type="text" id="target_unit" name="target_unit" value="{{ old('target_unit', $dailyTask->target_unit ?? '') }}" placeholder="contoh: reps, km, sets">
    </div>
    <div>
        <label for="is_completed">Status</label>
        @php($isCompleted = old('is_completed', (string)($dailyTask->is_completed ?? 0)))
        <select id="is_completed" name="is_completed">
            <option value="0" @selected($isCompleted === '0')>Pending</option>
            <option value="1" @selected($isCompleted === '1')>Completed</option>
        </select>
    </div>
</div>

<div>
    <label for="notes">Catatan</label>
    <textarea id="notes" name="notes" rows="4">{{ old('notes', $dailyTask->notes ?? '') }}</textarea>
</div>

<div class="actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('daily-tasks.index') }}">Kembali</a>
</div>

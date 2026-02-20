@csrf
<div class="grid grid-2">
    <div>
        <label for="task_date">Tanggal Task</label>
        <input type="date" id="task_date" name="task_date" class="@error('task_date') is-invalid @enderror" value="{{ old('task_date', optional($dailyTask->task_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required>
        @error('task_date')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="title">Judul Task</label>
        <input type="text" id="title" name="title" class="@error('title') is-invalid @enderror" value="{{ old('title', $dailyTask->title ?? '') }}" required>
        @error('title')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="category">Kategori</label>
        <input type="text" id="category" name="category" class="@error('category') is-invalid @enderror" value="{{ old('category', $dailyTask->category ?? '') }}" placeholder="Contoh: Cardio / Strength / Recovery">
        @error('category')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="duration_minutes">Durasi (menit)</label>
        <input type="number" id="duration_minutes" name="duration_minutes" class="@error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $dailyTask->duration_minutes ?? '') }}">
        @error('duration_minutes')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="calories_burned">Kalori Terbakar</label>
        <input type="number" id="calories_burned" name="calories_burned" class="@error('calories_burned') is-invalid @enderror" value="{{ old('calories_burned', $dailyTask->calories_burned ?? 0) }}">
        @error('calories_burned')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="target_value">Target Nilai</label>
        <input type="number" step="0.01" id="target_value" name="target_value" class="@error('target_value') is-invalid @enderror" value="{{ old('target_value', $dailyTask->target_value ?? '') }}">
        @error('target_value')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="target_unit">Satuan Target</label>
        <input type="text" id="target_unit" name="target_unit" class="@error('target_unit') is-invalid @enderror" value="{{ old('target_unit', $dailyTask->target_unit ?? '') }}" placeholder="contoh: reps, km, sets">
        @error('target_unit')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="is_completed">Status</label>
        @php($isCompleted = old('is_completed', (string)($dailyTask->is_completed ?? 0)))
        <select id="is_completed" name="is_completed" class="@error('is_completed') is-invalid @enderror">
            <option value="0" @selected($isCompleted === '0')>Pending</option>
            <option value="1" @selected($isCompleted === '1')>Completed</option>
        </select>
        @error('is_completed')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div>
    <label for="notes">Catatan</label>
    <textarea id="notes" name="notes" rows="4" class="@error('notes') is-invalid @enderror">{{ old('notes', $dailyTask->notes ?? '') }}</textarea>
    @error('notes')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('daily-tasks.index') }}">Kembali</a>
</div>

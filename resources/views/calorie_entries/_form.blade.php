@csrf
<div class="grid grid-2">
    <div>
        <label for="entry_date">Tanggal</label>
        <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', optional($calorieEntry->entry_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required>
    </div>
    <div>
        <label for="meal_type">Meal Type</label>
        @php($mealType = old('meal_type', $calorieEntry->meal_type ?? 'Breakfast'))
        <select id="meal_type" name="meal_type" required>
            @foreach(['Breakfast', 'Lunch', 'Dinner', 'Snack'] as $type)
                <option value="{{ $type }}" @selected($mealType === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="food_name">Nama Makanan</label>
        <input type="text" id="food_name" name="food_name" value="{{ old('food_name', $calorieEntry->food_name ?? '') }}" required>
    </div>
    <div>
        <label for="calories">Kalori (kcal)</label>
        <input type="number" id="calories" name="calories" value="{{ old('calories', $calorieEntry->calories ?? '') }}" required>
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="protein_grams">Protein (g)</label>
        <input type="number" step="0.01" id="protein_grams" name="protein_grams" value="{{ old('protein_grams', $calorieEntry->protein_grams ?? '') }}">
    </div>
    <div>
        <label for="carbs_grams">Karbohidrat (g)</label>
        <input type="number" step="0.01" id="carbs_grams" name="carbs_grams" value="{{ old('carbs_grams', $calorieEntry->carbs_grams ?? '') }}">
    </div>
</div>

<div>
    <label for="fat_grams">Lemak (g)</label>
    <input type="number" step="0.01" id="fat_grams" name="fat_grams" value="{{ old('fat_grams', $calorieEntry->fat_grams ?? '') }}">
</div>

<div>
    <label for="notes">Catatan</label>
    <textarea id="notes" name="notes" rows="4">{{ old('notes', $calorieEntry->notes ?? '') }}</textarea>
</div>

<div class="actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('calorie-entries.index') }}">Kembali</a>
</div>

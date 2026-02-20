@csrf
<div class="grid grid-2">
    <div>
        <label for="measurement_date">Tanggal Measurement</label>
        <input type="date" id="measurement_date" name="measurement_date" value="{{ old('measurement_date', optional($bodyMeasurement->measurement_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required>
    </div>
    <div>
        <label for="weight_kg">Berat (kg)</label>
        <input type="number" step="0.01" id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $bodyMeasurement->weight_kg ?? '') }}" required>
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="body_fat_percentage">Body Fat (%)</label>
        <input type="number" step="0.01" id="body_fat_percentage" name="body_fat_percentage" value="{{ old('body_fat_percentage', $bodyMeasurement->body_fat_percentage ?? '') }}">
    </div>
    <div>
        <label for="muscle_mass_kg">Muscle Mass (kg)</label>
        <input type="number" step="0.01" id="muscle_mass_kg" name="muscle_mass_kg" value="{{ old('muscle_mass_kg', $bodyMeasurement->muscle_mass_kg ?? '') }}">
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="chest_cm">Dada (cm)</label>
        <input type="number" step="0.01" id="chest_cm" name="chest_cm" value="{{ old('chest_cm', $bodyMeasurement->chest_cm ?? '') }}">
    </div>
    <div>
        <label for="waist_cm">Pinggang (cm)</label>
        <input type="number" step="0.01" id="waist_cm" name="waist_cm" value="{{ old('waist_cm', $bodyMeasurement->waist_cm ?? '') }}">
    </div>
</div>

<div>
    <label for="hips_cm">Pinggul (cm)</label>
    <input type="number" step="0.01" id="hips_cm" name="hips_cm" value="{{ old('hips_cm', $bodyMeasurement->hips_cm ?? '') }}">
</div>

<div>
    <label for="notes">Catatan</label>
    <textarea id="notes" name="notes" rows="4">{{ old('notes', $bodyMeasurement->notes ?? '') }}</textarea>
</div>

<div class="actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('body-measurements.index') }}">Kembali</a>
</div>

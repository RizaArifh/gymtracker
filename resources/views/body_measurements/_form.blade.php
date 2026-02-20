@csrf
<div class="grid grid-2">
    <div>
        <label for="measurement_date">Tanggal Measurement</label>
        <input type="date" id="measurement_date" name="measurement_date" class="@error('measurement_date') is-invalid @enderror" value="{{ old('measurement_date', optional($bodyMeasurement->measurement_date ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required>
        @error('measurement_date')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="weight_kg">Berat (kg)</label>
        <input type="number" step="0.01" id="weight_kg" name="weight_kg" class="@error('weight_kg') is-invalid @enderror" value="{{ old('weight_kg', $bodyMeasurement->weight_kg ?? '') }}" required>
        @error('weight_kg')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="body_fat_percentage">Body Fat (%)</label>
        <input type="number" step="0.01" id="body_fat_percentage" name="body_fat_percentage" class="@error('body_fat_percentage') is-invalid @enderror" value="{{ old('body_fat_percentage', $bodyMeasurement->body_fat_percentage ?? '') }}">
        @error('body_fat_percentage')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="muscle_mass_kg">Muscle Mass (kg)</label>
        <input type="number" step="0.01" id="muscle_mass_kg" name="muscle_mass_kg" class="@error('muscle_mass_kg') is-invalid @enderror" value="{{ old('muscle_mass_kg', $bodyMeasurement->muscle_mass_kg ?? '') }}">
        @error('muscle_mass_kg')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div class="grid grid-2">
    <div>
        <label for="chest_cm">Dada (cm)</label>
        <input type="number" step="0.01" id="chest_cm" name="chest_cm" class="@error('chest_cm') is-invalid @enderror" value="{{ old('chest_cm', $bodyMeasurement->chest_cm ?? '') }}">
        @error('chest_cm')<span class="field-error">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="waist_cm">Pinggang (cm)</label>
        <input type="number" step="0.01" id="waist_cm" name="waist_cm" class="@error('waist_cm') is-invalid @enderror" value="{{ old('waist_cm', $bodyMeasurement->waist_cm ?? '') }}">
        @error('waist_cm')<span class="field-error">{{ $message }}</span>@enderror
    </div>
</div>

<div>
    <label for="hips_cm">Pinggul (cm)</label>
    <input type="number" step="0.01" id="hips_cm" name="hips_cm" class="@error('hips_cm') is-invalid @enderror" value="{{ old('hips_cm', $bodyMeasurement->hips_cm ?? '') }}">
    @error('hips_cm')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div>
    <label for="notes">Catatan</label>
    <textarea id="notes" name="notes" rows="4" class="@error('notes') is-invalid @enderror">{{ old('notes', $bodyMeasurement->notes ?? '') }}</textarea>
    @error('notes')<span class="field-error">{{ $message }}</span>@enderror
</div>

<div class="actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn secondary" href="{{ route('body-measurements.index') }}">Kembali</a>
</div>

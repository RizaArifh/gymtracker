@extends('layouts.app', ['title' => 'Body Measurements'])

@section('content')
    <style>
        .mobile-shell { max-width: 560px; margin: 0 auto; }
        .section-title { margin: 0; font-size: 1.1rem; }
        .upload-preview-box {
            border: 1px dashed #b7c2cf;
            border-radius: 10px;
            background: #f9fbfd;
            padding: 10px;
            margin-top: 6px;
        }
        .upload-preview-box img {
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            border-radius: 8px;
            display: none;
        }
        .measure-cards { display: none; gap: 10px; flex-direction: column; }
        .measure-card { border: 1px solid var(--line); border-radius: 10px; padding: 12px; background: #fff; }
        .measure-card h4 { margin: 0 0 8px 0; }
        .measure-meta { font-size: 13px; color: var(--muted); margin-bottom: 8px; }
        .measure-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 14px; margin-bottom: 10px; }
        .modal-panel {
            max-width: 920px;
            width: 95%;
            margin: 4vh auto;
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            max-height: 92vh;
            overflow: auto;
        }
        .review-image {
            width: 100%;
            max-height: 280px;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: #f8fbff;
            margin-bottom: 12px;
        }
        @media (max-width: 720px) {
            .mobile-shell { max-width: 100%; }
            .desktop-table { display: none; }
            .measure-cards { display: flex; }
            .modal-panel {
                width: 100%;
                margin: 16vh 0 0 0;
                border-radius: 16px 16px 0 0;
                max-height: 84vh;
            }
        }
    </style>

<div class="mobile-shell">
    <div class="card">
        <div class="actions between">
            <h1 class="section-title">Body Measurements</h1>
            <a class="btn" href="{{ route('body-measurements.create') }}">+ Tambah Measurement</a>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 8px;">Import dari Foto Mesin</h2>
        <p class="muted">Upload foto hasil body analyzer. Sistem akan OCR, lalu tampilkan popup review sebelum simpan.</p>
        <form id="importPreviewForm" method="POST" action="{{ route('body-measurements.import-image', [], false) }}" enctype="multipart/form-data" class="grid grid-2">
            @csrf
            <div>
                <label for="measurement_date">Tanggal Measurement</label>
                <input type="date" id="measurement_date" name="measurement_date" value="{{ old('measurement_date', now()->toDateString()) }}">
            </div>
            <div>
                <label for="measurement_image">Foto Hasil Scan</label>
                <input type="file" id="measurement_image" name="measurement_image" accept=".jpg,.jpeg,.png,.webp" required>
                <span class="help">Format: JPG/PNG/WEBP, max 8MB</span>
                <div class="upload-preview-box">
                    <img id="uploadImagePreview" alt="Preview upload image">
                    <span id="uploadImageHint" class="muted">Preview gambar akan tampil di sini.</span>
                </div>
            </div>
            <div class="actions">
                <button class="btn" type="submit" id="previewBtn">Upload & Review</button>
                <span id="importStatus" class="muted"></span>
            </div>
        </form>
    </div>

    <div class="card">
        <table class="desktop-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tinggi</th>
                    <th>Berat</th>
                    <th>Body Fat</th>
                    <th>Muscle Mass</th>
                    <th>BMI</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($measurements as $item)
                    <tr>
                        <td>{{ $item->measurement_date->format('d M Y') }}</td>
                        <td>{{ $item->height_cm ?? '-' }} cm</td>
                        <td>{{ $item->weight_kg }} kg</td>
                        <td>{{ $item->body_fat_percentage ?? '-' }} %</td>
                        <td>{{ $item->muscle_mass_kg ?? '-' }} kg</td>
                        <td>{{ $item->bmi ?? '-' }}</td>
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
                    <tr><td colspan="7" class="muted">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="measure-cards">
            @forelse($measurements as $item)
                <div class="measure-card">
                    <h4>{{ $item->measurement_date->format('d M Y') }}</h4>
                    <div class="measure-meta">Scan body composition</div>
                    <div class="measure-grid">
                        <div><strong>Tinggi:</strong> {{ $item->height_cm ?? '-' }} cm</div>
                        <div><strong>Berat:</strong> {{ $item->weight_kg }} kg</div>
                        <div><strong>Body Fat:</strong> {{ $item->body_fat_percentage ?? '-' }} %</div>
                        <div><strong>Muscle:</strong> {{ $item->muscle_mass_kg ?? '-' }} kg</div>
                        <div><strong>BMI:</strong> {{ $item->bmi ?? '-' }}</div>
                    </div>
                    <div class="actions">
                        <a class="btn secondary" href="{{ route('body-measurements.show', $item) }}">Detail</a>
                        <a class="btn secondary" href="{{ route('body-measurements.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('body-measurements.destroy', $item) }}" onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="muted">Belum ada data.</div>
            @endforelse
        </div>

        <div style="margin-top: 12px;">{{ $measurements->links() }}</div>
    </div>

    <div id="previewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000;">
        <div class="modal-panel">
            <div class="actions between" style="margin-bottom:12px;">
                <h2 style="margin:0;">Review Data OCR</h2>
                <button type="button" class="btn danger" id="closePreviewModal">Tutup</button>
            </div>
            <img id="rv_image_preview" class="review-image" alt="Review uploaded image">

            <form method="POST" action="{{ route('body-measurements.store-imported-preview', [], false) }}" id="reviewSaveForm">
                @csrf
                <input type="hidden" name="source_image_path" id="rv_source_image_path">
                <textarea name="source_ocr_text" id="rv_source_ocr_text" style="display:none;"></textarea>

                <div class="grid grid-2">
                    <div>
                        <label>Tanggal Measurement</label>
                        <input type="date" name="measurement_date" id="rv_measurement_date" required>
                    </div>
                    <div>
                        <label>Weight (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" id="rv_weight_kg" required>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div>
                        <label>Height (cm)</label>
                        <input type="number" step="0.01" name="height_cm" id="rv_height_cm">
                    </div>
                    <div>
                        <label>Body Fat (%)</label>
                        <input type="number" step="0.01" name="body_fat_percentage" id="rv_body_fat_percentage">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div>
                        <label>Muscle Mass (kg)</label>
                        <input type="number" step="0.01" name="muscle_mass_kg" id="rv_muscle_mass_kg">
                    </div>
                    <div>
                        <label>BMI</label>
                        <input type="number" step="0.01" name="bmi" id="rv_bmi">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div>
                        <label>Visceral Fat Grade</label>
                        <input type="number" name="visceral_fat_grade" id="rv_visceral_fat_grade">
                    </div>
                    <div>
                        <label>Fat Mass (kg)</label>
                        <input type="number" step="0.01" name="fat_mass_kg" id="rv_fat_mass_kg">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div>
                        <label>Lean Body Mass (kg)</label>
                        <input type="number" step="0.01" name="lean_body_mass_kg" id="rv_lean_body_mass_kg">
                    </div>
                    <div>
                        <label>Evaluation Score</label>
                        <input type="number" step="0.01" name="evaluation_score" id="rv_evaluation_score">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div>
                        <label>Physical Age</label>
                        <input type="number" name="physical_age" id="rv_physical_age">
                    </div>
                    <div>
                        <label>Notes</label>
                        <input type="text" name="notes" id="rv_notes" value="Imported from image OCR">
                    </div>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('importPreviewForm');
        if (!form) return;

        const statusEl = document.getElementById('importStatus');
        const modal = document.getElementById('previewModal');
        const closeBtn = document.getElementById('closePreviewModal');
        const previewBtn = document.getElementById('previewBtn');
        const fileInput = document.getElementById('measurement_image');
        const uploadImagePreview = document.getElementById('uploadImagePreview');
        const uploadImageHint = document.getElementById('uploadImageHint');
        const reviewImage = document.getElementById('rv_image_preview');
        let localPreviewUrl = '';

        const fieldMap = {
            measurement_date: 'rv_measurement_date',
            source_image_path: 'rv_source_image_path',
            source_ocr_text: 'rv_source_ocr_text',
            weight_kg: 'rv_weight_kg',
            height_cm: 'rv_height_cm',
            body_fat_percentage: 'rv_body_fat_percentage',
            muscle_mass_kg: 'rv_muscle_mass_kg',
            bmi: 'rv_bmi',
            visceral_fat_grade: 'rv_visceral_fat_grade',
            fat_mass_kg: 'rv_fat_mass_kg',
            lean_body_mass_kg: 'rv_lean_body_mass_kg',
            evaluation_score: 'rv_evaluation_score',
            physical_age: 'rv_physical_age'
        };

        function setValue(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            el.value = value ?? '';
        }

        function showLocalPreview(file) {
            if (!file) return;
            if (localPreviewUrl) URL.revokeObjectURL(localPreviewUrl);
            localPreviewUrl = URL.createObjectURL(file);
            uploadImagePreview.src = localPreviewUrl;
            uploadImagePreview.style.display = 'block';
            uploadImageHint.style.display = 'none';
        }

        fileInput?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            showLocalPreview(file);
        });

        closeBtn?.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modal?.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            statusEl.textContent = 'Memproses OCR...';
            previewBtn.disabled = true;

            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const data = await response.json();
                if (!response.ok) {
                    statusEl.textContent = data.message || 'Gagal memproses gambar.';
                    return;
                }

                setValue(fieldMap.measurement_date, data.measurement_date);
                setValue(fieldMap.source_image_path, data.source_image_path);
                setValue(fieldMap.source_ocr_text, data.source_ocr_text);
                reviewImage.src = data.source_image_url || localPreviewUrl || '';

                const parsed = data.parsed || {};
                Object.keys(fieldMap).forEach((key) => {
                    if (['measurement_date', 'source_image_path', 'source_ocr_text'].includes(key)) return;
                    setValue(fieldMap[key], parsed[key]);
                });

                statusEl.textContent = 'Preview siap. Silakan review dan simpan.';
                modal.style.display = 'block';
            } catch (err) {
                statusEl.textContent = 'Terjadi kesalahan jaringan saat OCR.';
            } finally {
                previewBtn.disabled = false;
            }
        });
    })();
</script>
@endpush

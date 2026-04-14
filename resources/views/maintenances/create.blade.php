@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ $asset ? route('inventaris.show', $asset->id) : route('maintenances.index') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">
                    {{ $asset ? 'Kembali ke Detail Asset' : 'Kembali ke Jadwal Maintenance' }}
                </span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 text-primary">{{ isset($asset) ? 'Log Maintenance: ' . $asset->display_name : 'Buat Jadwal / Log Maintenance' }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('maintenances.store') }}" method="POST">
                            @csrf
                            @if(isset($asset))
                                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                <div class="mb-4 d-flex align-items-center gap-3 p-3 bg-light rounded-3 shadow-sm border-start border-primary border-4">
                                    <i class="bi bi-laptop fs-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $asset->display_name }}</h6>
                                        <small class="text-muted">{{ ucfirst($asset->category) }}
                                            @if($asset->category === 'computer') ({{ $asset->ipMapping->ip_address ?? '' }}) @endif
                                        </small>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4">
                                    <label for="asset_id" class="form-label fw-bold">Pilih Asset <span class="text-danger">*</span></label>
                                    <select class="form-select @error('asset_id') is-invalid @enderror" id="asset_id" name="asset_id" required>
                                        <option value="">-- Cari & Pilih Asset --</option>
                                        @foreach($assets as $a)
                                            <option value="{{ $a->id }}" {{ old('asset_id') == $a->id ? 'selected' : '' }}>
                                                {{ $a->display_name }} ({{ ucfirst($a->category) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asset_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Judul / Nama Kegiatan (Opsional)</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title') }}" 
                                    placeholder="Contoh: Maintenance Bulanan, Perbaikan Layar, dll.">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-bold">Tipe Input <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required onchange="toggleFields()">
                                        <option value="completed" {{ old('status', request('status')) == 'completed' ? 'selected' : '' }}>Log (Sudah Selesai)</option>
                                        <option value="pending" {{ old('status', request('status')) == 'pending' ? 'selected' : '' }}>Jadwal (Mendatang)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="maintenance_date" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('maintenance_date') is-invalid @enderror"
                                        id="maintenance_date" name="maintenance_date"
                                        value="{{ old('maintenance_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Detail Pekerjaan / Rencana <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="4"
                                    placeholder="Jelaskan apa yang akan/sudah dilakukan..."
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="cost_field">
                                <label for="cost" class="form-label fw-bold">Biaya (Opsional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost"
                                        name="cost" value="{{ old('cost', 0) }}" min="0">
                                </div>
                                @error('cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary py-3 fw-bold shadow-sm rounded-3" id="submit_btn">
                                    <i class="bi bi-check-lg me-1"></i> Simpan
                                </button>
                            </div>
                        </form>

                        <script>
                            function toggleFields() {
                                const status = document.getElementById('status').value;
                                const costField = document.getElementById('cost_field');
                                const submitBtn = document.getElementById('submit_btn');
                                
                                if (status === 'pending') {
                                    costField.style.display = 'none';
                                    submitBtn.className = 'btn btn-warning py-3 fw-bold shadow-sm rounded-3';
                                    submitBtn.innerHTML = '<i class="bi bi-calendar-check me-1"></i> Buat Jadwal Maintenance';
                                } else {
                                    costField.style.display = 'block';
                                    submitBtn.className = 'btn btn-primary py-3 fw-bold shadow-sm rounded-3';
                                    submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan Log Maintenance';
                                }
                            }
                            toggleFields(); // Run on load
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
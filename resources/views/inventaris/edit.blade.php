@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="mb-4">
            <a href="{{ route('inventaris.index') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">Kembali ke Daftar Aset</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 text-warning">Edit Data Asset</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('inventaris.update', $asset->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nama Asset <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $asset->name) }}"
                                    placeholder="Contoh: Printer EPSON L3110" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label fw-bold">Lokasi / Ruangan</label>
                                <select class="form-select @error('location') is-invalid @enderror" id="location"
                                    name="location">
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach(App\Models\Asset::ZONES as $floor => $zones)
                                        <optgroup label="{{ $floor }}">
                                            @foreach($zones as $zone)
                                                <option value="{{ $zone }}" {{ old('location', $asset->location) == $zone ? 'selected' : '' }}>
                                                    {{ $zone }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Status Asset <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                    required>
                                    <option value="active" {{ old('status', $asset->status) === 'active' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="broken" {{ old('status', $asset->status) === 'broken' ? 'selected' : '' }}>
                                        Rusak</option>
                                    <option value="disposed" {{ old('status', $asset->status) === 'disposed' ? 'selected' : '' }}>Dibuang /
                                        Tidak digunakan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">Keterangan / Spesifikasi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="3"
                                    placeholder="Tambahkan informasi tambahan...">{{ old('description', $asset->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-display me-2"></i>Informasi Remote Desktop
                                (Opsional)</h5>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="remote_app_name" class="form-label fw-bold small text-muted">Aplikasi
                                        Remote</label>
                                    <input type="text" class="form-control @error('remote_app_name') is-invalid @enderror"
                                        id="remote_app_name" name="remote_app_name"
                                        value="{{ old('remote_app_name', $asset->remote_app_name) }}"
                                        placeholder="Contoh: AnyDesk, TeamViewer">
                                    @error('remote_app_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="remote_address" class="form-label fw-bold small text-muted">ID /
                                        Alamat</label>
                                    <input type="text" class="form-control @error('remote_address') is-invalid @enderror"
                                        id="remote_address" name="remote_address"
                                        value="{{ old('remote_address', $asset->remote_address) }}"
                                        placeholder="Contoh: 123 456 789">
                                    @error('remote_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="remote_password"
                                        class="form-label fw-bold small text-muted">Password</label>
                                    <input type="text" class="form-control @error('remote_password') is-invalid @enderror"
                                        id="remote_password" name="remote_password"
                                        value="{{ old('remote_password', $asset->remote_password) }}"
                                        placeholder="Password Remote">
                                    @error('remote_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-warning py-2 fw-bold shadow-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Perbarui Data Asset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

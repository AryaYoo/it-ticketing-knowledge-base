@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="mb-4">
            <a href="{{ route('ip-mappings.index') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small"
                    style="letter-spacing: 0.1em;">{{ __('Back to IP Mappings') }}</span>
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add New IP Mapping') }}</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('ip-mappings.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                                <input id="ip_address" type="text" 
                                    class="form-control @error('ip_address') is-invalid @enderror" 
                                    name="ip_address" 
                                    value="{{ old('ip_address') }}" 
                                    placeholder="192.168.100.x"
                                    pattern="192\.168\.100\.\d{1,3}"
                                    required 
                                    autofocus>
                                <small class="form-text text-muted">
                                    Format: 192.168.100.x (where x is between 1-254)
                                </small>
                                @error('ip_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input id="display_name" type="text" 
                                    class="form-control @error('display_name') is-invalid @enderror" 
                                    name="display_name" 
                                    value="{{ old('display_name') }}" 
                                    placeholder="e.g., Computer Lab 1, Reception Desk"
                                    required>
                                <small class="form-text text-muted">
                                    A friendly name to identify this IP address
                                </small>
                                @error('display_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi / Ruangan</label>
                                <select id="location" class="form-select @error('location') is-invalid @enderror" name="location">
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach(App\Models\Asset::ZONES as $floor => $zones)
                                        <optgroup label="{{ $floor }}">
                                            @foreach($zones as $zone)
                                                <option value="{{ $zone }}" {{ old('location') == $zone ? 'selected' : '' }}>
                                                    {{ $zone }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Physical location of this asset
                                </small>
                                @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Allow IP-based authentication)
                                </label>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="is_hospital_asset" name="is_hospital_asset" value="1" 
                                    {{ old('is_hospital_asset') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-primary" for="is_hospital_asset">
                                    <i class="bi bi-box-seam me-1"></i> Aset Rumah Sakit
                                </label>
                                <div class="form-text">
                                    Jika dicentang, IP ini akan otomatis masuk ke daftar inventaris Aset IT.
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> When you create this IP mapping, a user account will be automatically created 
                                with the role "Client". Users from this IP address will be able to access the system without login.
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Create IP Mapping
                                </button>
                                <a href="{{ route('ip-mappings.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

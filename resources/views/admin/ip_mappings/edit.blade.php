@extends('layouts.app')

@section('content')
    <div class="container py-4">
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
                    <div class="card-header">{{ __('Edit IP Mapping') }}</div>

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

                        <form method="POST" action="{{ route('ip-mappings.update', $ipMapping->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input id="ip_address" type="text" 
                                    class="form-control bg-light" 
                                    value="{{ $ipMapping->ip_address }}" 
                                    readonly>
                                <small class="form-text text-muted">
                                    IP address cannot be changed after creation
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input id="display_name" type="text" 
                                    class="form-control @error('display_name') is-invalid @enderror" 
                                    name="display_name" 
                                    value="{{ old('display_name', $ipMapping->display_name) }}" 
                                    required 
                                    autofocus>
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
                                                <option value="{{ $zone }}" {{ old('location', $ipMapping->location) == $zone ? 'selected' : '' }}>
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
                                    {{ old('is_active', $ipMapping->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Allow IP-based authentication)
                                </label>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="is_hospital_asset" name="is_hospital_asset" value="1" 
                                    {{ old('is_hospital_asset', $ipMapping->is_hospital_asset) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-primary" for="is_hospital_asset">
                                    <i class="bi bi-box-seam me-1"></i> Aset Rumah Sakit
                                </label>
                                <div class="form-text">
                                    Jika dicentang, IP ini akan otomatis masuk ke daftar inventaris Aset IT sebagai kategori Komputer.
                                </div>
                            </div>

                            @if($ipMapping->user)
                                <div class="mb-3">
                                    <label class="form-label">Associated User</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">User ID:</small>
                                                    <div><strong>{{ $ipMapping->user->id }}</strong></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">Email:</small>
                                                    <div><code>{{ $ipMapping->user->email }}</code></div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <small class="text-muted">Role:</small>
                                                    <div><span class="badge bg-secondary">{{ ucfirst($ipMapping->user->role) }}</span></div>
                                                </div>
                                                <div class="col-md-6 mt-2">
                                                    <small class="text-muted">Created:</small>
                                                    <div>{{ $ipMapping->user->created_at->format('Y-m-d H:i') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($ipMapping->last_used_at)
                                <div class="mb-3">
                                    <label class="form-label">Last Used</label>
                                    <div class="form-control bg-light" readonly>
                                        {{ $ipMapping->last_used_at->format('Y-m-d H:i:s') }} 
                                        ({{ $ipMapping->last_used_at->diffForHumans() }})
                                    </div>
                                </div>
                            @endif

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> Changing the display name will also update the associated user's name. 
                                Disabling this IP mapping will prevent authentication from this IP address.
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Update IP Mapping
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

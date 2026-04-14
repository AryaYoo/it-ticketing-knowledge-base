@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('dashboard') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small"
                    style="letter-spacing: 0.1em;">{{ __('Back to Dashboard') }}</span>
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('IP Mapping Management') }}</span>
                        <a href="{{ route('ip-mappings.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Add New IP Mapping
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <form action="{{ route('ip-mappings.index') }}" method="GET" class="d-flex gap-2"
                                style="max-width: 400px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search"
                                        class="form-control border-2 border-start-0 bg-white ps-2"
                                        placeholder="{{ __('Search IP or display name...') }}" value="{{ $search ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                                @if($search ?? false)
                                    <a href="{{ route('ip-mappings.index') }}"
                                        class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                                @endif
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Display Name</th>
                                        <th>Status</th>
                                        <th>Asset</th>
                                        <th>User ID</th>
                                        <th>Last Used</th>
                                        <th>Created At</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ipMappings as $mapping)
                                        <tr>
                                            <td>
                                                <code class="text-primary">{{ $mapping->ip_address }}</code>
                                            </td>
                                            <td>
                                                <strong>{{ $mapping->display_name }}</strong>
                                            </td>
                                            <td>
                                                @if($mapping->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($mapping->is_hospital_asset)
                                                    <span class="badge bg-primary">
                                                        <i class="bi bi-box-seam me-1"></i>Aset RS
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($mapping->user)
                                                    <span class="badge bg-info text-dark">{{ $mapping->user->id }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($mapping->last_used_at)
                                                    <small class="text-muted">
                                                        {{ $mapping->last_used_at->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ $mapping->created_at->format('Y-m-d H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('ip-mappings.edit', $mapping->id) }}"
                                                        class="btn btn-sm btn-outline-warning text-dark" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('ip-mappings.destroy', $mapping->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger confirm-delete"
                                                            data-confirm="Apakah Anda yakin ingin menghapus mapping IP ini? User terkait juga akan ikut terhapus."
                                                            title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No IP mappings found. Click "Add New IP Mapping" to create one.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $ipMappings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
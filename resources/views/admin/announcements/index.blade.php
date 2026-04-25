@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">{{ __('Back to Dashboard') }}</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                        <h5 class="mb-0 fw-bold">{{ __('Announcement Management') }}</h5>
                        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm col-12 col-sm-auto py-2">
                            <span class="d-flex align-items-center justify-content-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                {{ __('Add New Announcement') }}
                            </span>
                        </a>
                    </div>

                    <div class="card-body p-0">
                        <div class="px-4 py-3">
                            <form action="{{ route('announcements.index') }}" method="GET" class="d-flex gap-2" style="max-width: 400px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-2 border-start-0 bg-white ps-2"

                                        placeholder="{{ __('Search title or content...') }}"
                                        value="{{ $search ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                                @if($search ?? false)
                                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                                @endif
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="px-4 py-3">{{ __('Title') }}</th>
                                        <th class="py-3 text-center">{{ __('Status') }}</th>
                                        <th class="py-3">{{ __('Author') }}</th>
                                        <th class="py-3">{{ __('Created At') }}</th>
                                        <th class="px-4 py-3 text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $announcement)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($announcement->image_path)
                                                        <img src="{{ Storage::url($announcement->image_path) }}" class="rounded shadow-sm" style="width: 48px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 32px;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $announcement->title }}</div>
                                                        <div class="small text-muted text-truncate" style="max-width: 250px;">{{ Str::limit($announcement->content, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($announcement->is_active)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 small">{{ $announcement->user->name }}</td>
                                            <td class="py-3 small text-muted">{{ $announcement->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-3 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary border-2">
                                                        {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger border-2 confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus pengumuman ini?">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-5 text-center text-muted">
                                                {{ __('No announcements found.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($announcements->hasPages())
                            <div class="p-4 border-top">
                                {{ $announcements->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

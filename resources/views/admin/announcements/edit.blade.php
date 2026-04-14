@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('announcements.index') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small"
                    style="letter-spacing: 0.1em;">{{ __('Back to Announcement Management') }}</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">{{ __('Edit Announcement') }}</h5>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('announcements.update', $announcement) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold small text-uppercase text-muted"
                                    style="letter-spacing: 0.05em;">{{ __('Title') }}</label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold small text-uppercase text-muted"
                                    style="letter-spacing: 0.05em;">{{ __('Content') }}</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                    name="content" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label fw-bold small text-uppercase text-muted"
                                    style="letter-spacing: 0.05em;">{{ __('New Banner Image (Optional)') }}</label>
                                @if($announcement->image_path)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($announcement->image_path) }}"
                                            class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                        <div class="small text-muted mt-1">{{ __('Current Image') }}</div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                    name="image" accept="image/*">
                                <div class="form-text small">
                                    {{ __('Recommended size: 1200x400px. Max 2MB. Leave blank to keep current.') }}
                                </div>
                                @error('image')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $announcement->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold"
                                        for="is_active">{{ __('Active & Visible') }}</label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary p-3 fw-bold text-uppercase"
                                    style="letter-spacing: 0.1em;">
                                    {{ __('Update Announcement') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
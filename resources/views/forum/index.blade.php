@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Announcements Section --}}
        @if($announcements->count() > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-4 text-muted text-uppercase small" style="letter-spacing: 0.1em;">
                    {{ __('Announcements') }}
                </h5>
                <div class="row g-4">
                    @foreach($announcements as $announcement)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px;">
                                <div class="row g-0">
                                    @if($announcement->image_path)
                                        <div class="col-12 col-md-5 col-lg-4">
                                            <img src="{{ Storage::url($announcement->image_path) }}" class="img-fluid h-100 w-100"
                                                style="object-fit: cover; min-height: 250px;" alt="{{ $announcement->title }}">
                                        </div>
                                    @endif
                                    <div class="{{ $announcement->image_path ? 'col-12 col-md-7 col-lg-8' : 'col-12' }}">
                                        <div class="card-body p-4 p-md-5">
                                            <h2 class="fw-bold mb-3 h3">{{ $announcement->title }}</h2>
                                            <p class="text-secondary fs-5 mb-4" style="line-height: 1.6;">
                                                {{ $announcement->content }}
                                            </p>
                                            <div class="pt-3 border-top d-flex flex-wrap align-items-center gap-3 text-muted small">
                                                <div class="d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                    </svg>
                                                    {{ $announcement->created_at->diffForHumans() }}
                                                </div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="opacity-50">•</span>
                                                    <span>{{ __('Posted by') }}
                                                        <strong>{{ $announcement->user->name }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr class="my-5 opacity-10">
        @endif

        <div class="row">
            <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                <div class="section-spacing sticky-top" style="top: 100px; z-index: 10;">
                    <h1 class="mb-2 fw-bold">{{ __('Community Forum') }}</h1>
                    <p class="text-muted mb-4 small">{{ __('Join the conversation with other users and staff.') }}</p>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <form action="{{ route('forum.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="content" class="form-label fw-bold small text-uppercase text-muted"
                                        style="letter-spacing: 0.1em;">{{ __("What's on your mind?") }}</label>
                                    <textarea class="form-control" id="content" name="content" rows="4"
                                        placeholder="Type your message here..."></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit"
                                        class="btn btn-primary py-2 fw-bold shadow-sm">{{ __('Post Message') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Recent Discussions -->
            <div class="col-12 col-lg-8">
                <div class="content-gap d-flex flex-column ps-lg-4">
                    <h4 class="mb-4 text-muted text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 0.1em;">
                        {{ __('Recent Discussions') }}
                    </h4>

                    <div class="mb-4">
                        <form action="{{ route('forum.index') }}" method="GET" class="d-flex gap-2"
                            style="max-width: 400px;">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-2 border-start-0 bg-white ps-2"

                                    placeholder="{{ __('Search posts...') }}" value="{{ $search ?? '' }}">
                            </div>
                            <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Search') }}</button>
                            @if($search ?? false)
                                <a href="{{ route('forum.index') }}"
                                    class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                            @endif
                        </form>
                    </div>

                    @foreach($posts as $post)

                        <div class="card shadow-sm border mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm border"
                                            style="width: 48px; height: 48px; background-color: rgba(var(--primary-rgb), 0.1);">
                                            <span class="fw-bold text-primary">{{ substr($post->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold fs-5">{{ $post->user->name }}</span>
                                                @if($post->user->role == 'admin')
                                                    <span class="badge bg-danger">Admin</span>
                                                @elseif($post->user->role == 'staff')
                                                    <span class="badge bg-primary">Staff</span>
                                                @else
                                                    <span class="badge bg-secondary">Client</span>
                                                @endif
                                            </div>
                                            <small class="text-muted d-block">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-4 fs-5 lh-base">{{ $post->content }}</p>

                                <div class="d-flex align-items-center border-top pt-3">
                                    <form action="{{ route('forum.react', $post) }}" method="POST">
                                        @csrf
                                        @php
                                            $liked = $post->reactions->contains('user_id', auth()->id());
                                            $count = $post->reactions->count();
                                        @endphp
                                        <button type="submit"
                                            class="btn {{ $liked ? 'btn-danger' : 'btn-outline-secondary' }} border-0 shadow-none d-flex align-items-center gap-2 px-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                                </path>
                                            </svg>
                                            <span class="fw-bold fs-6">{{ $count }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center mt-5 pt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection